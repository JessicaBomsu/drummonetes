// Verifica se TensorFlow está disponível
if (typeof tf === 'undefined' || typeof handpose === 'undefined') {
    console.error('Bibliotecas necessárias não carregadas!');
    alert('Erro: As bibliotecas de visão computacional não carregaram corretamente. Por favor, recarregue a página.');
} else {
    // Configurações do piano
    const WHITE_KEYS = ['A0', 'B0', 'C1', 'D1', 'E1', 'F1', 'G1', 'A1', 'B1', 'C2', 'D2', 'E2', 'F2', 'G2', 'A2', 'B2', 'C3', 'D3', 'E3', 'F3', 'G3', 'A3', 'B3', 'C4', 'D4', 'E4', 'F4', 'G4', 'A4', 'B4', 'C5', 'D5', 'E5', 'F5', 'G5', 'A5', 'B5', 'C6', 'D6', 'E6', 'F6', 'G6', 'A6', 'B6', 'C7', 'D7', 'E7', 'F7', 'G7', 'A7', 'B7', 'C8'];
    const BLACK_KEYS = ['A#0', 'C#1', 'D#1', 'F#1', 'G#1', 'A#1', 'C#2', 'D#2', 'F#2', 'G#2', 'A#2', 'C#3', 'D#3', 'F#3', 'G#3', 'A#3', 'C#4', 'D#4', 'F#4', 'G#4', 'A#4', 'C#5', 'D#5', 'F#5', 'G#5', 'A#5', 'C#6', 'D#6', 'F#6', 'G#6', 'A#6', 'C#7', 'D#7', 'F#7', 'G#7', 'A#7'];
    const BLACK_LABELS = ['A#0', 'C#1', 'D#1', 'F#1', 'G#1', 'A#1', 'C#2', 'D#2', 'F#2', 'G#2', 'A#2', 'C#3', 'D#3', 'F#3', 'G#3', 'A#3', 'C#4', 'D#4', 'F#4', 'G#4', 'A#4', 'C#5', 'D#5', 'F#5', 'G#5', 'A#5', 'C#6', 'D#6', 'F#6', 'G#6', 'A#6', 'C#7', 'D#7', 'F#7', 'G#7', 'A#7'];

    // Variáveis globais
    let audioContext;
    let activeKeys = {};
    let handposeModel;
    let video;
    let canvas;
    let ctx;
    let previousHandPos = { x: 0, y: 0 };
    let keyRects = {
        white: [],
        black: []
    };

    // Aguarda o carregamento completo do DOM
    document.addEventListener('DOMContentLoaded', function () {
        // Elementos do DOM
        video = document.getElementById('webcam');
        canvas = document.getElementById('pianoCanvas');
        ctx = canvas.getContext('2d');
        const startBtn = document.getElementById('startBtn');

        // Configurar tamanho do canvas
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Inicializar AudioContext após interação do usuário
        startBtn.addEventListener('click', async () => {
            try {
                await initWebcam();
                await initAudio();
                await initHandpose();
                startBtn.disabled = true;
                startBtn.innerHTML = '<i class="fas fa-check-circle"></i> Webcam Ativa';
                drawPiano();
                detectHands();
            } catch (error) {
                console.error('Erro na inicialização:', error);
                alert('Erro ao inicializar: ' + error.message);
            }
        });
    });

    // Inicialização
    document.addEventListener('DOMContentLoaded', () => {
        // Elementos do DOM
        video = document.getElementById('webcam');
        canvas = document.getElementById('pianoCanvas');
        ctx = canvas.getContext('2d');
        const startBtn = document.getElementById('startBtn');

        // Configurar tamanho do canvas
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Inicializar AudioContext após interação do usuário
        startBtn.addEventListener('click', async () => {
            try {
                await initWebcam();
                await initAudio();
                await initHandpose();
                startBtn.disabled = true;
                startBtn.textContent = 'Webcam Ativa';
                drawPiano();
                detectHands();
            } catch (error) {
                console.error('Erro na inicialização:', error);
                alert('Erro ao inicializar: ' + error.message);
            }
        });

        // Teclado para sair do modo tela cheia
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.fullscreenElement) {
                document.exitFullscreen();
            }
        });
    });

    // Redimensionar canvas
    function resizeCanvas() {
        const container = document.querySelector('.piano-container');
        canvas.width = container.clientWidth;
        canvas.height = container.clientHeight;
    }

    // Inicializar webcam
    async function initWebcam() {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: 'user'
            },
            audio: false
        });
        video.srcObject = stream;
        return new Promise((resolve) => {
            video.onloadedmetadata = () => {
                video.play();
                resolve();
            };
        });
    }

    // Inicializar áudio
    async function initAudio() {
        audioContext = new (window.AudioContext || window.webkitAudioContext)();

        // Pré-carregar todos os sons (opcional)
        // Na prática, você pode carregar sob demanda quando uma tecla é pressionada
    }

    // Carregar modelo de detecção de mãos
    async function initHandpose() {
        await tf.ready();
        handposeModel = await handpose.load();
        console.log('Modelo HandPose carregado');
    }

    // Desenhar piano no canvas
    function drawPiano() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const whiteKeyWidth = canvas.width / 52;
        const whiteKeyHeight = canvas.height * 0.75;
        const blackKeyWidth = whiteKeyWidth * 0.6;
        const blackKeyHeight = whiteKeyHeight * 0.6;

        keyRects.white = [];
        keyRects.black = [];

        // Desenhar teclas brancas
        for (let i = 0; i < 52; i++) {
            const x = i * whiteKeyWidth;
            const y = canvas.height - whiteKeyHeight;

            // Armazenar retângulo para detecção de colisão
            keyRects.white.push({
                x, y,
                width: whiteKeyWidth,
                height: whiteKeyHeight,
                note: WHITE_KEYS[i],
                index: i
            });

            // Desenhar tecla
            ctx.fillStyle = activeKeys[WHITE_KEYS[i]] ? '#2ecc71' : 'white';
            ctx.fillRect(x, y, whiteKeyWidth, whiteKeyHeight);
            ctx.strokeStyle = 'black';
            ctx.lineWidth = 2;
            ctx.strokeRect(x, y, whiteKeyWidth, whiteKeyHeight);

            // Rótulo da tecla
            ctx.fillStyle = 'black';
            ctx.font = `${Math.max(10, whiteKeyWidth * 0.3)}px Arial`;
            ctx.fillText(WHITE_KEYS[i], x + 5, canvas.height - 10);
        }

        // Desenhar teclas pretas
        let skipCount = 0;
        let lastSkip = 2;
        let skipTrack = 2;

        for (let i = 0; i < 36; i++) {
            const x = 23 + (i * whiteKeyWidth) + (skipCount * whiteKeyWidth);
            const y = canvas.height - whiteKeyHeight;

            // Armazenar retângulo para detecção de colisão
            keyRects.black.push({
                x, y,
                width: blackKeyWidth,
                height: blackKeyHeight,
                note: BLACK_KEYS[i],
                index: i
            });

            // Desenhar tecla
            ctx.fillStyle = activeKeys[BLACK_KEYS[i]] ? '#2ecc71' : 'black';
            ctx.fillRect(x, y, blackKeyWidth, blackKeyHeight);
            ctx.strokeStyle = '#555';
            ctx.lineWidth = 1;
            ctx.strokeRect(x, y, blackKeyWidth, blackKeyHeight);

            // Rótulo da tecla
            ctx.fillStyle = 'white';
            ctx.font = `${Math.max(8, whiteKeyWidth * 0.2)}px Arial`;
            ctx.fillText(BLACK_LABELS[i], x + 5, y + blackKeyHeight - 15);

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

    // Detectar mãos e interagir com o piano
    async function detectHands() {
        async function frame() {
            if (video.readyState >= 2) {
                const predictions = await handposeModel.estimateHands(video);

                // Limpar pontos de mão anteriores
                document.querySelectorAll('.hand-point').forEach(el => el.remove());

                if (predictions.length > 0) {
                    for (const prediction of predictions) {
                        // Desenhar pontos das mãos
                        for (const landmark of prediction.landmarks) {
                            // Inverter coordenada X (porque a webcam está espelhada)
                            const x = canvas.width - (landmark[0] / video.videoWidth * canvas.width);
                            const y = landmark[1] / video.videoHeight * canvas.height;

                            // Criar elemento para o ponto da mão
                            const point = document.createElement('div');
                            point.className = 'hand-point';
                            point.style.left = `${x - 5}px`;
                            point.style.top = `${y - 5}px`;
                            document.body.appendChild(point);

                            // Verificar colisão com teclas
                            checkKeyCollision(x, y);
                        }
                    }
                }

                // Redesenhar piano (para atualizar teclas ativas)
                drawPiano();
            }

            requestAnimationFrame(frame);
        }

        frame();
    }

    // Verificar colisão entre ponto da mão e teclas
    function checkKeyCollision(x, y) {
        const clickThreshold = 15; // Limiar para considerar "clique"
        const currentPos = { x, y };
        const distanceMoved = Math.sqrt(
            Math.pow(currentPos.x - previousHandPos.x, 2) +
            Math.pow(currentPos.y - previousHandPos.y, 2)
        );

        // Só verifica colisão se o movimento for pequeno (para evitar toques acidentais)
        if (distanceMoved < clickThreshold) {
            // Verificar teclas brancas
            for (const key of keyRects.white) {
                if (x > key.x && x < key.x + key.width &&
                    y > key.y && y < key.y + key.height) {
                    playNote(key.note);
                    activeKeys[key.note] = true;
                    setTimeout(() => delete activeKeys[key.note], 200);
                    break;
                }
            }

            // Verificar teclas pretas
            for (const key of keyRects.black) {
                if (x > key.x && x < key.x + key.width &&
                    y > key.y && y < key.y + key.height) {
                    playNote(key.note);
                    activeKeys[key.note] = true;
                    setTimeout(() => delete activeKeys[key.note], 200);
                    break;
                }
            }
        }

        previousHandPos = currentPos;
    }

    // Reproduzir nota musical
    function playNote(note) {
        // Verificar se a nota já está sendo tocada (evitar sobreposição)
        if (activeKeys[note]) return;

        // Carregar e tocar o som (usando Fetch API e AudioContext)
        fetch(`sounds/${note}.wav`)
            .then(response => response.arrayBuffer())
            .then(arrayBuffer => audioContext.decodeAudioData(arrayBuffer))
            .then(audioBuffer => {
                const source = audioContext.createBufferSource();
                source.buffer = audioBuffer;
                source.connect(audioContext.destination);
                source.start(0);

                // Marcar nota como ativa
                activeKeys[note] = true;

                // Limpar após a reprodução
                setTimeout(() => {
                    delete activeKeys[note];
                    drawPiano();
                }, 200);
            })
            .catch(error => console.error('Erro ao carregar som:', error));
    }

    // Dispara evento quando o piano estiver pronto
    document.dispatchEvent(new Event('pianoReady'));
}