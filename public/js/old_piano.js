class VirtualPiano {
    constructor() {
        this.videoElement = document.getElementById('webcam');
        this.canvas = document.getElementById('pianoCanvas');
        this.ctx = this.canvas.getContext('2d');
        this.activeKeys = {};
        this.keyRects = { white: [], black: [] };
        this.handPoints = [];
        this.animationFrameId = null;
        this.lastKeyCleanup = Date.now();

        this.init();
    }

    async init() {
        try {
            this.setupCanvas();
            await Promise.all([
                this.setupAudio(),
                this.setupWebcam(),
                this.loadHandposeModel()
            ]);
            this.detectHands();
        } catch (error) {
            console.error('Erro na inicialização:', error);
        }
    }

    async setupAudio() {
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        this.soundBuffers = {}; // Cache para os sons
    }

    async setupWebcam() {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { width: 1280, height: 720, facingMode: 'user' }
        });
        this.videoElement.srcObject = stream;
        await new Promise(resolve => this.videoElement.onloadedmetadata = resolve);
        this.videoElement.play();
    }

    async loadHandposeModel() {
        this.handposeModel = await handpose.load();
    }

    setupCanvas() {
        const container = document.querySelector('.piano-container');
        this.canvas.width = container.clientWidth;
        this.canvas.height = container.clientHeight;

        window.addEventListener('resize', () => {
            this.canvas.width = container.clientWidth;
            this.canvas.height = container.clientHeight;
            this.drawPiano();
        });
    }

    removeOldHandPoints() {
        this.handPoints.forEach(point => point.remove());
        this.handPoints = [];
    }

    getLandmarkDistance(a, b) {
        return Math.sqrt(Math.pow(a[0] - b[0], 2) + Math.pow(a[1] - b[1], 2));
    }

    isFingerBent(landmarks, tip, base) {
        const tipToBase = this.getLandmarkDistance(landmarks[tip], landmarks[base]);
        const tipToMid = this.getLandmarkDistance(landmarks[tip], landmarks[tip - 2]);
        return tipToBase < tipToMid * 0.85;
    }

    createFingerPoint(x, y, fingerType) {
        const colors = {
            thumb: '#FF5252', index: '#4CAF50',
            middle: '#2196F3', ring: '#FFC107', pinky: '#9C27B0'
        };

        const point = document.createElement('div');
        point.className = `finger-point ${fingerType}`;
        point.style.cssText = `
            position: absolute;
            left: ${x - 10}px;
            top: ${y - 10}px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: ${colors[fingerType]};
            border: 2px solid white;
            z-index: 1000;
            pointer-events: none;
            transition: all 0.1s ease;
        `;

        document.body.appendChild(point);
        this.handPoints.push(point);
    }

    async detectHands() {
        const detectFrame = async () => {
            try {
                if (this.videoElement.readyState >= 2) {
                    const predictions = await this.handposeModel.estimateHands(this.videoElement);
                    this.removeOldHandPoints();

                    if (predictions.length > 0) {
                        predictions.forEach(prediction => {
                            const fingers = [
                                { type: 'thumb', tip: 4, base: 2 },
                                { type: 'index', tip: 8, base: 5 },
                                { type: 'middle', tip: 12, base: 9 },
                                { type: 'ring', tip: 16, base: 13 },
                                { type: 'pinky', tip: 20, base: 17 }
                            ];

                            fingers.forEach(finger => {
                                if (this.isFingerBent(prediction.landmarks, finger.tip, finger.base)) {
                                    const [x, y] = prediction.landmarks[finger.tip];
                                    const canvasX = this.canvas.width - (x / this.videoElement.videoWidth * this.canvas.width);
                                    const canvasY = y / this.videoElement.videoHeight * this.canvas.height;

                                    this.createFingerPoint(canvasX, canvasY, finger.type);
                                    this.checkKeyCollision(canvasX, canvasY);
                                }
                            });
                        });
                    }

                    this.cleanupKeys();
                    this.drawPiano();
                }
                this.animationFrameId = requestAnimationFrame(detectFrame);
            } catch (error) {
                console.error('Erro na detecção:', error);
            }
        };
        detectFrame();
    }

    cleanupKeys() {
        const now = Date.now();
        if (now - this.lastKeyCleanup > 100) {
            Object.keys(this.activeKeys).forEach(note => {
                if (now - this.activeKeys[note] > 200) {
                    delete this.activeKeys[note];
                }
            });
            this.lastKeyCleanup = now;
        }
    }

    checkKeyCollision(x, y) {
        const allKeys = [...this.keyRects.white, ...this.keyRects.black];
        for (const key of allKeys) {
            if (x > key.x && x < key.x + key.width &&
                y > key.y && y < key.y + key.height) {
                this.playNote(key.note);
                break;
            }
        }
    }

    async playNote(note) {
        if (this.activeKeys[note]) return;

        this.activeKeys[note] = Date.now();

        // Tocar som
        if (!this.soundBuffers[note]) {
            try {
                const response = await fetch(`sounds/${note}.wav`);
                const arrayBuffer = await response.arrayBuffer();
                this.soundBuffers[note] = await this.audioContext.decodeAudioData(arrayBuffer);
            } catch (error) {
                console.error(`Erro ao carregar som ${note}:`, error);
                return;
            }
        }

        const source = this.audioContext.createBufferSource();
        source.buffer = this.soundBuffers[note];
        source.connect(this.audioContext.destination);
        source.start();
    }

    drawPiano() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        const whiteKeyWidth = this.canvas.width / 52;
        const whiteKeyHeight = this.canvas.height * 0.75;
        const blackKeyWidth = whiteKeyWidth * 0.6;
        const blackKeyHeight = whiteKeyHeight * 0.6;

        // Teclas brancas
        for (let i = 0; i < 52; i++) {
            const x = i * whiteKeyWidth;
            const y = this.canvas.height - whiteKeyHeight;

            this.keyRects.white[i] = { x, y, width: whiteKeyWidth, height: whiteKeyHeight, note: WHITE_KEYS[i] };

            this.ctx.fillStyle = this.activeKeys[WHITE_KEYS[i]] ? '#2ecc71' : 'white';
            this.ctx.fillRect(x, y, whiteKeyWidth, whiteKeyHeight);
            this.ctx.strokeStyle = 'black';
            this.ctx.strokeRect(x, y, whiteKeyWidth, whiteKeyHeight);

            this.ctx.fillStyle = 'black';
            this.ctx.font = `${Math.max(10, whiteKeyWidth * 0.3)}px Arial`;
            this.ctx.fillText(WHITE_KEYS[i], x + 5, this.canvas.height - 10);
        }

        // Teclas pretas
        let skipCount = 0, lastSkip = 2, skipTrack = 2;
        for (let i = 0; i < 36; i++) {
            const x = 23 + (i * whiteKeyWidth) + (skipCount * whiteKeyWidth);
            const y = this.canvas.height - whiteKeyHeight;

            this.keyRects.black[i] = { x, y, width: blackKeyWidth, height: blackKeyHeight, note: BLACK_KEYS[i] };

            this.ctx.fillStyle = this.activeKeys[BLACK_KEYS[i]] ? '#2ecc71' : 'black';
            this.ctx.fillRect(x, y, blackKeyWidth, blackKeyHeight);
            this.ctx.strokeStyle = '#555';
            this.ctx.strokeRect(x, y, blackKeyWidth, blackKeyHeight);

            this.ctx.fillStyle = 'white';
            this.ctx.font = `${Math.max(8, whiteKeyWidth * 0.2)}px Arial`;
            this.ctx.fillText(BLACK_KEYS[i], x + 5, y + blackKeyHeight - 15);

            skipTrack += 1;
            if (lastSkip === 2 && skipTrack === 3) {
                lastSkip = 3;
                skipTrack = 0;
                skipCount += 1;
            } else if (lastSkip === 3 && skipTrack === 2) {
                lastSkip = 2;
                skipTrack = 0;
                skipCount += 1;
            }
        }
    }
}

// Constantes
const WHITE_KEYS = ['A0', 'B0', 'C1', 'D1', 'E1', 'F1', 'G1', 'A1', 'B1', 'C2', 'D2', 'E2', 'F2', 'G2', 'A2', 'B2', 'C3', 'D3', 'E3', 'F3', 'G3', 'A3', 'B3', 'C4', 'D4', 'E4', 'F4', 'G4', 'A4', 'B4', 'C5', 'D5', 'E5', 'F5', 'G5', 'A5', 'B5', 'C6', 'D6', 'E6', 'F6', 'G6', 'A6', 'B6', 'C7', 'D7', 'E7', 'F7', 'G7', 'A7', 'B7', 'C8'];
const BLACK_KEYS = ['A#0', 'C#1', 'D#1', 'F#1', 'G#1', 'A#1', 'C#2', 'D#2', 'F#2', 'G#2', 'A#2', 'C#3', 'D#3', 'F#3', 'G#3', 'A#3', 'C#4', 'D#4', 'F#4', 'G#4', 'A#4', 'C#5', 'D#5', 'F#5', 'G#5', 'A#5', 'C#6', 'D#6', 'F#6', 'G#6', 'A#6', 'C#7', 'D#7', 'F#7', 'G#7', 'A#7'];

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    window.piano = new VirtualPiano();
});