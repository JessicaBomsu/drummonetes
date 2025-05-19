// Arquivo: piano.js (corrigido para MediaPipe Hands)

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
        this.soundBuffers = {};
    }

    async setupWebcam() {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { width: 780, height: 720, facingMode: 'user' }
        });
        this.videoElement.srcObject = stream;
        await new Promise(resolve => this.videoElement.onloadedmetadata = resolve);
        this.videoElement.play();
    }

    async loadHandposeModel() {
        this.hands = new Hands({
            locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
        });

        this.hands.setOptions({
            maxNumHands: 2,
            modelComplexity: 1,
            minDetectionConfidence: 0.7,
            minTrackingConfidence: 0.6
        });

        this.hands.onResults(this.onHandsResults.bind(this));
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

    detectHands() {
        const detectFrame = async () => {
            if (this.videoElement.readyState >= 2) {
                await this.hands.send({ image: this.videoElement });
            }
            this.animationFrameId = requestAnimationFrame(detectFrame);
        };
        detectFrame();
    }

    // onHandsResults(results) {
    //     this.removeOldHandPoints();

    //     if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
    //         results.multiHandLandmarks.forEach((landmarks, handIndex) => {
    //             const handColor = handIndex === 0 ? '#00BCD4' : '#E91E63';

    //             landmarks.forEach((landmark, index) => {
    //                 const canvasX = landmark.x * this.canvas.width;
    //                 const canvasY = landmark.y * this.canvas.height;

    //                 const fingerTypes = ['thumb', 'index', 'middle', 'ring', 'pinky'];
    //                 const fingerType = fingerTypes[Math.floor(index / 4)] || 'pinky';

    //                 this.createFingerPoint(canvasX, canvasY, fingerType, handColor);

    //                 // Só considerar pontas de dedos
    //                 if ([4, 8, 12, 16, 20].includes(index)) {
    //                     if (this.isFingerBent(landmarks, index, index - 2)) {
    //                         // Tocar só se o dedo estiver na parte de baixo (piano)
    //                         const pianoTop = this.canvas.height * 0.25; // Considerando que o piano ocupa 75% inferior
    //                         if (canvasY >= pianoTop) {
    //                             this.checkKeyCollision(canvasX, canvasY);
    //                         }
    //                     }
    //                 }
    //             });
    //         });
    //     }

    //     this.cleanupKeys();
    //     this.drawPiano();
    // }

    onHandsResults(results) {
        this.removeOldHandPoints();

        if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
            results.multiHandLandmarks.forEach((landmarks, handIndex) => {
                const handColor = handIndex === 0 ? '#00BCD4' : '#E91E63';

                landmarks.forEach((landmark, index) => {
                    const canvasX = landmark.x * this.canvas.width;
                    const canvasY = landmark.y * this.canvas.height;

                    // Criar pontos para todos os dedos (visual)
                    this.createFingerPoint(canvasX, canvasY,
                        ['thumb', 'index', 'middle', 'ring', 'pinky'][Math.floor(index / 4)] || 'pinky',
                        handColor);

                    // OPCIONAL: Identifica através de cores os pontos das mãos
                    if ([4, 8, 12, 16, 20].includes(index)) {
                        const pip = landmarks[index - 2];
                        const mcp = landmarks[index - 3];

                        // Mostrar pontos de referência
                        this.createFingerPoint(pip.x * this.canvas.width, pip.y * this.canvas.height,
                            'pip', '#FF0000');
                        this.createFingerPoint(mcp.x * this.canvas.width, mcp.y * this.canvas.height,
                            'mcp', '#FFFF00');
                    }

                    // Verificar apenas as pontas dos dedos (4, 8, 12, 16, 20)
                    if ([4, 8, 12, 16, 20].includes(index)) {
                        if (this.isFingerBent(landmarks, index, index - 2)) {
                            this.checkKeyCollision(canvasX, canvasY);
                        }
                    }
                });
            });
        }

        this.cleanupKeys();
        this.drawPiano();
    }

    createFingerPoint(x, y, fingerType, handColor) {
        const pianoContainer = document.querySelector('.piano-container');
        const point = document.createElement('div');
        point.className = `finger-point ${fingerType}`;

        // Ajustar posição relativa ao container do piano
        const rect = pianoContainer.getBoundingClientRect();
        const adjustedX = x - rect.left;
        const adjustedY = y - rect.top;

        point.style.cssText = `
            position: absolute;
            left: ${adjustedX - 10}px;
            top: ${adjustedY - 10}px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: ${handColor};
            border: 2px solid white;
            z-index: 1000;
            pointer-events: none;
            transition: all 0.1s ease;
        `;

        pianoContainer.appendChild(point);
        this.handPoints.push(point);
    }

    // isFingerBent(landmarks, tip, pip) {
    //     const dx1 = landmarks[tip].x - landmarks[pip].x;
    //     const dy1 = landmarks[tip].y - landmarks[pip].y;
    //     const distTipToPip = Math.sqrt(dx1 * dx1 + dy1 * dy1);

    //     const dx2 = landmarks[tip].x - landmarks[tip - 2].x;
    //     const dy2 = landmarks[tip].y - landmarks[tip - 2].y;
    //     const distTipToDip = Math.sqrt(dx2 * dx2 + dy2 * dy2);

    //     return distTipToPip < distTipToDip * 0.85;
    // }

    isFingerBent(landmarks, tipIndex, pipIndex) {
        const tip = landmarks[tipIndex];
        const pip = landmarks[pipIndex];
        const dip = landmarks[tipIndex - 1]; // Junta intermediária

        // Calcula ângulo entre os segmentos
        const angle = Math.atan2(tip.y - dip.y, tip.x - dip.x) -
            Math.atan2(dip.y - pip.y, dip.x - pip.x);
        const degrees = Math.abs(angle * (180 / Math.PI));

        console.log('Ângulo do dedo:', degrees);

        // Ângulo agudo indica dedo dobrado
        return degrees > 40; // Ajuste este valor conforme necessário

        // const mcp = landmarks[tipIndex - 3]; // Junta da base do dedo
        // Calcula distâncias entre as juntas
        // const distTipToPip = Math.sqrt(
        //     Math.pow(tip.x - pip.x, 2) +
        //     Math.pow(tip.y - pip.y, 2)
        // );
        // const distPipToMcp = Math.sqrt(
        //     Math.pow(pip.x - mcp.x, 2) +
        //     Math.pow(pip.y - mcp.y, 2)
        // );

        // Ajuste este valor para ser mais ou menos sensível
        // return (distTipToPip / distPipToMcp) < 0.9;
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

    // checkKeyCollision(x, y) {
    //     // Verifique se as coordenadas estão dentro dos limites do piano
    //     if (x < 0 || x > this.canvas.width || y < 0 || y > this.canvas.height) {
    //         return;
    //     }

    //     const allKeys = [...this.keyRects.white, ...this.keyRects.black];
    //     allKeys.forEach(key => {
    //         if (x > key.x && x < key.x + key.width && y > key.y && y < key.y + key.height) {
    //             this.playNote(key.note);
    //         }
    //     });
    // }

    checkKeyCollision(x, y) {
        console.log('Função checkKeyCollision chamada', x, y); // Debug básico

        const allKeys = [...this.keyRects.white, ...this.keyRects.black];
        console.log('Número de teclas:', allKeys.length); // Debug teclas

        let collisionDetected = false;

        allKeys.forEach((key, i) => {
            console.log(`Verificando tecla ${i}: ${key.note}`, key); // Debug por tecla

            if (x > key.x && x < key.x + key.width &&
                y > key.y && y < key.y + key.height) {
                console.log(`COLISÃO DETECTADA com ${key.note}`); // Debug positivo
                this.playNote(key.note);
                collisionDetected = true;
            }
        });

        if (!collisionDetected) {
            console.log('Nenhuma colisão detectada para coordenadas:', x, y); // Debug negativo
        }
    }

    async playNote(note) {
        if (this.activeKeys[note]) return;
        this.activeKeys[note] = Date.now();

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

            skipTrack++;
            if (lastSkip === 2 && skipTrack === 3) {
                lastSkip = 3;
                skipTrack = 0;
                skipCount++;
            } else if (lastSkip === 3 && skipTrack === 2) {
                lastSkip = 2;
                skipTrack = 0;
                skipCount++;
            }
        }
    }
}

const WHITE_KEYS = ['A0', 'B0', 'C1', 'D1', 'E1', 'F1', 'G1', 'A1', 'B1', 'C2', 'D2', 'E2', 'F2', 'G2', 'A2', 'B2', 'C3', 'D3', 'E3', 'F3', 'G3', 'A3', 'B3', 'C4', 'D4', 'E4', 'F4', 'G4', 'A4', 'B4', 'C5', 'D5', 'E5', 'F5', 'G5', 'A5', 'B5', 'C6', 'D6', 'E6', 'F6', 'G6', 'A6', 'B6', 'C7', 'D7', 'E7', 'F7', 'G7', 'A7', 'B7', 'C8'];
const BLACK_KEYS = ['A#0', 'C#1', 'D#1', 'F#1', 'G#1', 'A#1', 'C#2', 'D#2', 'F#2', 'G#2', 'A#2', 'C#3', 'D#3', 'F#3', 'G#3', 'A#3', 'C#4', 'D#4', 'F#4', 'G#4', 'A#4', 'C#5', 'D#5', 'F#5', 'G#5', 'A#5', 'C#6', 'D#6', 'F#6', 'G#6', 'A#6', 'C#7', 'D#7', 'F#7', 'G#7', 'A#7'];

document.addEventListener('DOMContentLoaded', () => {
    window.piano = new VirtualPiano();
});
