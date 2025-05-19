document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('pianoCanvas');
    const ctx = canvas.getContext('2d');
    const videoElement = document.getElementById('videoFeed');
    const infoStatus = document.getElementById('infoStatus');

    let WIDTH = window.innerWidth * 0.9;
    let HEIGHT = window.innerHeight * 0.8;
    canvas.width = WIDTH;
    canvas.height = HEIGHT;

    const NUM_WHITE_KEYS = 52;
    const NUM_BLACK_KEYS = 36;
    const PIANO_DISPLAY_HEIGHT = HEIGHT * 0.45;
    const WHITE_KEY_HEIGHT = PIANO_DISPLAY_HEIGHT;
    const BLACK_KEY_HEIGHT = WHITE_KEY_HEIGHT * 0.6;
    const BLACK_KEY_WIDTH_FACTOR = 0.65;

    let FONT_SMALL = `${Math.max(10, Math.floor(WIDTH / 100))}px Terserah`;
    let FONT_REAL_SMALL = `${Math.max(8, Math.floor(WIDTH / 130))}px Terserah`;

    let whiteSounds = {};
    let blackSounds = {};
    let activeWhites = [];
    let activeBlacks = [];

    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    if (!audioContext) {
        infoStatus.textContent = "Web Audio API não suportada.";
        console.error("Web Audio API não suportada.");
        return;
    }

    // --- Constantes para Detecção de Toque Aprimorada ---
    const Y_AXIS_PRESS_THRESHOLD = 3; // Movimento descendente da TIP para "armar"
    const XY_AXIS_STABILIZE_THRESHOLD = 7; // Velocidade máxima em XY para "tocar"
    const PRESS_COOLDOWN_MILLISECONDS = 200;
    const LANDMARK_RADIUS = 6; // Reduzido um pouco para melhor visualização do esqueleto
    const CONNECTION_LINE_WIDTH = 4;


    // Índices das landmarks do MediaPipe Hands
    // Adaptação para incluir todas as juntas para desenho
    const FINGER_LANDMARKS = {
        WRIST: 0,
        THUMB: { CMC: 1, MCP: 2, IP: 3, TIP: 4 }, // CMC = Carpometacarpal, IP = Interphalangeal (para polegar)
        INDEX: { MCP: 5, PIP: 6, DIP: 7, TIP: 8 },
        MIDDLE: { MCP: 9, PIP: 10, DIP: 11, TIP: 12 },
        RING: { MCP: 13, PIP: 14, DIP: 15, TIP: 16 },
        PINKY: { MCP: 17, PIP: 18, DIP: 19, TIP: 20 }
    };
    // Dedos para rastrear toque (incluindo polegar agora)
    const FINGER_TYPES_TO_TRACK_TOUCH = ['THUMB', 'INDEX', 'MIDDLE', 'RING', 'PINKY'];
    const CURVATURE_SLACK_Y = 18; // Ajuste este valor para sensibilidade da curvatura

    // Estado da ponta do dedo
    let fingerTipStates = {}; // Ex: 'hand0_INDEX_TIP': { lastX, lastY, isPrimedToPress, lastPressTime }


    async function loadSound(noteName) {
        const fileName = getNoteFileName(noteName);
        const soundPath = `/sounds/${fileName}`;
        try {
            const response = await fetch(soundPath);
            if (!response.ok) throw new Error(`Falha ao carregar ${soundPath}: ${response.statusText}`);
            const arrayBuffer = await response.arrayBuffer();
            return await audioContext.decodeAudioData(arrayBuffer);
        } catch (error) {
            console.error(`Erro ao carregar som ${noteName} (${soundPath}):`, error);
            infoStatus.textContent = `Erro ao carregar: ${fileName}`;
            return null;
        }
    }

    async function loadAllSounds() {
        infoStatus.textContent = "Carregando sons...";
        const soundPromises = whiteNotes.map(note => loadSound(note).then(buffer => whiteSounds[note] = buffer))
            .concat(blackNotes.map(note => loadSound(note).then(buffer => blackSounds[note] = buffer)));
        await Promise.all(soundPromises);
        infoStatus.textContent = "Sons carregados. Iniciando câmera...";
        console.log("Todos os sons carregados.");
    }

    function playSound(audioBuffer) {
        if (!audioBuffer) return;
        if (audioContext.state === 'suspended') {
            audioContext.resume().then(() => playActually(audioBuffer));
        } else {
            playActually(audioBuffer);
        }
    }

    function playActually(audioBuffer) {
        const source = audioContext.createBufferSource();
        source.buffer = audioBuffer;
        source.connect(audioContext.destination);
        source.start(0);
    }

    let whiteKeyRects = [];
    let blackKeyRects = [];

    function drawPiano() {
        ctx.clearRect(0, 0, WIDTH, HEIGHT); // Limpa o canvas inteiro
        // Desenha um fundo para a área do vídeo, se desejado
        // ctx.fillStyle = "#444"; // Cor de fundo para o feed da câmera (se não estiver desenhando a imagem da câmera)
        // ctx.fillRect(0, 0, WIDTH, HEIGHT - PIANO_DISPLAY_HEIGHT);


        ctx.fillStyle = '#ddd'; // Fundo da área do piano
        ctx.fillRect(0, HEIGHT - PIANO_DISPLAY_HEIGHT, WIDTH, PIANO_DISPLAY_HEIGHT);

        whiteKeyRects = [];
        blackKeyRects = [];
        const whiteKeyWidth = WIDTH / NUM_WHITE_KEYS;

        for (let i = 0; i < NUM_WHITE_KEYS; i++) {
            const x = i * whiteKeyWidth;
            const y = HEIGHT - WHITE_KEY_HEIGHT;
            ctx.fillStyle = 'white';
            ctx.fillRect(x, y, whiteKeyWidth, WHITE_KEY_HEIGHT);
            ctx.strokeStyle = 'black';
            ctx.lineWidth = 1;
            ctx.strokeRect(x, y, whiteKeyWidth, WHITE_KEY_HEIGHT);
            whiteKeyRects.push({ x, y, width: whiteKeyWidth, height: WHITE_KEY_HEIGHT, note: whiteNotes[i], index: i });

            // ctx.fillStyle = 'black';
            // ctx.font = FONT_SMALL;
            // ctx.textAlign = "center";
            // ctx.fillText(whiteNotes[i], x + whiteKeyWidth / 2, HEIGHT - 15);
        }

        const blackKeyActualWidth = whiteKeyWidth * BLACK_KEY_WIDTH_FACTOR;
        let currentBlackKeyIndex = 0;
        for (let i = 0; i < NUM_WHITE_KEYS - 1; i++) {
            const whiteNoteName = whiteNotes[i].substring(0, 1);
            if (whiteNoteName !== 'E' && whiteNoteName !== 'B') {
                if (currentBlackKeyIndex < NUM_BLACK_KEYS) {
                    const x = (i + 1) * whiteKeyWidth - (blackKeyActualWidth / 2);
                    const y = HEIGHT - WHITE_KEY_HEIGHT;
                    ctx.fillStyle = 'black';
                    ctx.fillRect(x, y, blackKeyActualWidth, BLACK_KEY_HEIGHT);
                    ctx.strokeStyle = '#333';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(x, y, blackKeyActualWidth, BLACK_KEY_HEIGHT);
                    blackKeyRects.push({ x, y, width: blackKeyActualWidth, height: BLACK_KEY_HEIGHT, note: blackNotes[currentBlackKeyIndex], label: blackLabels[currentBlackKeyIndex], index: currentBlackKeyIndex });

                    // ctx.fillStyle = 'white';
                    // ctx.font = FONT_REAL_SMALL;
                    // ctx.textAlign = "center";
                    // ctx.fillText(blackLabels[currentBlackKeyIndex], x + blackKeyActualWidth / 2, HEIGHT - WHITE_KEY_HEIGHT + BLACK_KEY_HEIGHT - 10);
                    currentBlackKeyIndex++;
                }
            }
        }

        activeWhites.forEach(active => {
            const key = whiteKeyRects[active.keyIndex];
            if (key && active.timeLeft > 0) {
                ctx.fillStyle = 'rgba(0, 255, 0, 0.5)';
                ctx.fillRect(key.x, key.y, key.width, key.height);
                active.timeLeft--;
            }
        });
        activeWhites = activeWhites.filter(active => active.timeLeft > 0);

        activeBlacks.forEach(active => {
            const key = blackKeyRects.find(k => k.index === active.keyIndex);
            if (key && active.timeLeft > 0) {
                ctx.fillStyle = 'rgba(100, 255, 100, 0.6)';
                ctx.fillRect(key.x, key.y, key.width, key.height);
                active.timeLeft--;
            }
        });
        activeBlacks = activeBlacks.filter(active => active.timeLeft > 0);
    }

    let handsMP;
    async function setupMediaPipeHands() {
        handsMP = new Hands({
            locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands@0.4/${file}`
        });
        handsMP.setOptions({
            maxNumHands: 2,
            modelComplexity: 1,
            minDetectionConfidence: 0.6, // Levemente reduzido para mais detecções
            minTrackingConfidence: 0.55
        });
        handsMP.onResults(onResults);

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { width: { ideal: 640 }, height: { ideal: 480 } } });
            videoElement.srcObject = stream;
            videoElement.onloadedmetadata = () => {
                videoElement.play();
                const camera = new Camera(videoElement, {
                    onFrame: async () => await handsMP.send({ image: videoElement }),
                });
                camera.start();
                infoStatus.textContent = "Câmera iniciada. Posicione suas mãos!";
            };
        } catch (err) {
            console.error("Erro ao acessar a câmera: ", err);
            infoStatus.textContent = "Erro ao acessar a câmera. Verifique as permissões.";
        }
    }

    function onResults(results) {
        drawPiano(); // Limpa o canvas e desenha o piano

        // Desenhar o feed da câmera no fundo (opcional, pode pesar na performance)
        // ctx.save();
        // ctx.scale(-1, 1); // Espelhar horizontalmente
        // ctx.drawImage(results.image, -WIDTH, 0, WIDTH, HEIGHT - PIANO_DISPLAY_HEIGHT);
        // ctx.restore();

        if (results.multiHandLandmarks) {
            for (let handIndex = 0; handIndex < results.multiHandLandmarks.length; handIndex++) {
                const landmarks = results.multiHandLandmarks[handIndex];

                // Desenhar o esqueleto da mão
                // Usar as conexões padrão do MediaPipe Hands
                // HAND_CONNECTIONS é um array de pares de índices de landmarks
                if (window.drawConnectors && window.HAND_CONNECTIONS) { // Verifica se drawing_utils está carregado
                    // Inverter as coordenadas X para o desenho também
                    const mirroredLandmarks = landmarks.map(lm => ({
                        ...lm, // Copia x, y, z, visibility
                        x: 1 - lm.x // Inverte o x
                    }));

                    drawConnectors(ctx, mirroredLandmarks, HAND_CONNECTIONS, {
                        color: 'rgba(255, 255, 255, 0.6)', // Cor das conexões (branco translúcido)
                        lineWidth: CONNECTION_LINE_WIDTH
                    });
                    // Desenhar as landmarks (círculos nas juntas)
                    // drawLandmarks(ctx, mirroredLandmarks, {
                    //     color: 'rgba(255, 0, 0, 0.8)', // Cor das juntas (vermelho)
                    //     lineWidth: 1,
                    //     radius: LANDMARK_RADIUS / 2
                    // });
                }


                FINGER_TYPES_TO_TRACK_TOUCH.forEach(fingerType => {
                    const fingerLMIndices = FINGER_LANDMARKS[fingerType];
                    let tipLM, pipLM, dipLM;

                    tipLM = landmarks[fingerLMIndices.TIP];

                    if (fingerType === 'THUMB') {
                        // Para o polegar, PIP e DIP não são usados da mesma forma para curvatura.
                        // Usaremos a junta IP (Interphalangeal) como análoga à DIP e MCP como análoga à PIP para uma lógica simplificada.
                        pipLM = landmarks[fingerLMIndices.MCP]; // MCP do polegar
                        dipLM = landmarks[fingerLMIndices.IP];  // IP do polegar
                    } else {
                        pipLM = landmarks[fingerLMIndices.PIP];
                        dipLM = landmarks[fingerLMIndices.DIP];
                    }

                    if (!tipLM || !pipLM || !dipLM) {
                        return; // Pular se alguma landmark crucial não for detectada
                    }

                    const tipX = (1 - tipLM.x) * WIDTH; // Inverter X
                    const tipY = tipLM.y * HEIGHT;
                    const pipY = pipLM.y * HEIGHT;
                    const dipY = dipLM.y * HEIGHT;

                    // Visualização da ponta do dedo (pode ser removida se o esqueleto for suficiente)
                    ctx.beginPath();
                    ctx.arc(tipX, tipY, LANDMARK_RADIUS, 0, 2 * Math.PI);
                    ctx.fillStyle = 'rgba(0, 150, 255, 0.7)'; // Azul para a ponta
                    ctx.fill();

                    const tipId = `hand${handIndex}_${fingerType}`;
                    if (!fingerTipStates[tipId]) {
                        fingerTipStates[tipId] = { lastX: tipX, lastY: tipY, isPrimedToPress: false, lastPressTime: 0 };
                    }
                    let state = fingerTipStates[tipId];

                    const deltaX = tipX - state.lastX;
                    const deltaY = tipY - state.lastY;
                    const speedXY = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

                    let isOnKey = false;
                    let currentKeyInfo = null;

                    for (const key of blackKeyRects) {
                        if (tipX > key.x && tipX < key.x + key.width && tipY > key.y && tipY < key.y + key.height) {
                            isOnKey = true; currentKeyInfo = { type: 'black', note: key.note, index: key.index }; break;
                        }
                    }
                    if (!isOnKey) {
                        for (const key of whiteKeyRects) {
                            if (tipX > key.x && tipX < key.x + key.width && tipY > key.y && tipY < key.y + key.height) {
                                isOnKey = true; currentKeyInfo = { type: 'white', note: key.note, index: key.index }; break;
                            }
                        }
                    }

                    let isReadyToPress = false;
                    if (fingerType === 'THUMB') {
                        // Lógica simplificada para o polegar: movimento descendente e estabilização
                        if (deltaY > Y_AXIS_PRESS_THRESHOLD && !state.isPrimedToPress) {
                            state.isPrimedToPress = true;
                        }
                        if (state.isPrimedToPress) {
                            isReadyToPress = true; // Polegar está "pronto" se foi armado pelo movimento descendente
                        }
                        // Visualização: Polegar Armado (Amarelo claro)
                        if (state.isPrimedToPress && isOnKey) {
                            ctx.beginPath();
                            ctx.arc(tipX, tipY - (LANDMARK_RADIUS * 1.8), LANDMARK_RADIUS / 1.5, 0, 2 * Math.PI);
                            ctx.fillStyle = 'rgba(255, 255, 200, 0.8)';
                            ctx.fill();
                        }
                    } else {
                        // Lógica de curvatura para outros dedos
                        if (pipY < tipY + CURVATURE_SLACK_Y && dipY < tipY + CURVATURE_SLACK_Y) {
                            if (pipY < dipY + (CURVATURE_SLACK_Y / 1.5)) { // PIP mais acima ou próximo ao DIP
                                isReadyToPress = true; // Dedo está curvado e "pronto"
                                // Visualização: Dedo Curvado (Laranja)
                                if (isOnKey) {
                                    ctx.beginPath();
                                    ctx.arc(tipX, tipY - (LANDMARK_RADIUS * 1.8), LANDMARK_RADIUS / 1.5, 0, 2 * Math.PI);
                                    ctx.fillStyle = 'rgba(255, 165, 0, 0.8)';
                                    ctx.fill();
                                }
                            }
                        }
                    }


                    if (isOnKey && isReadyToPress && tipY > (HEIGHT - PIANO_DISPLAY_HEIGHT - WHITE_KEY_HEIGHT * 0.05) && tipY < HEIGHT) {
                        // Condição de toque: estabilização da ponta do dedo
                        if (speedXY < XY_AXIS_STABILIZE_THRESHOLD) {
                            if (Date.now() - state.lastPressTime > PRESS_COOLDOWN_MILLISECONDS) {
                                if (currentKeyInfo.type === 'white') {
                                    if (whiteSounds[currentKeyInfo.note] && !isActive(activeWhites, currentKeyInfo.index)) {
                                        playSound(whiteSounds[currentKeyInfo.note]);
                                        activeWhites.push({ keyIndex: currentKeyInfo.index, timeLeft: 15 });
                                    }
                                } else if (currentKeyInfo.type === 'black') {
                                    if (blackSounds[currentKeyInfo.note] && !isActive(activeBlacks, currentKeyInfo.index)) {
                                        playSound(blackSounds[currentKeyInfo.note]);
                                        activeBlacks.push({ keyIndex: currentKeyInfo.index, timeLeft: 15 });
                                    }
                                }
                                state.lastPressTime = Date.now();
                                state.isPrimedToPress = false; // Resetar 'primed' para o polegar e outros se aplicável

                                // Visualização: Dedo Tocou (Verde)
                                ctx.beginPath();
                                ctx.arc(tipX, tipY, LANDMARK_RADIUS + 2, 0, 2 * Math.PI);
                                ctx.fillStyle = 'rgba(0, 255, 0, 0.7)';
                                ctx.fill();
                            }
                        }
                    } else {
                        if (fingerType === 'THUMB') state.isPrimedToPress = false; // Resetar se saiu da tecla ou não está pronto
                        // Para outros dedos, isReadyToPress é baseado na curvatura, não precisa resetar 'primed' aqui explicitamente
                    }

                    // Se o dedo (especialmente polegar) fez um movimento ascendente significativo, resetar 'primed'
                    if (deltaY < -Y_AXIS_PRESS_THRESHOLD && fingerType === 'THUMB') {
                        state.isPrimedToPress = false;
                    }

                    state.lastX = tipX;
                    state.lastY = tipY;
                });
            }
        }
    }

    function isActive(activeList, keyIndex) {
        return activeList.some(item => item.keyIndex === keyIndex && item.timeLeft > 0);
    }

    async function init() {
        await loadAllSounds();
        await setupMediaPipeHands();
    }

    init().catch(err => {
        console.error("Erro na inicialização:", err);
        infoStatus.textContent = "Falha ao inicializar. Verifique o console.";
    });

    window.addEventListener('resize', () => {
        WIDTH = window.innerWidth * 0.9;
        HEIGHT = window.innerHeight * 0.8;
        canvas.width = WIDTH;
        canvas.height = HEIGHT;
        FONT_SMALL = `${Math.max(10, Math.floor(WIDTH / 100))}px Terserah`;
        FONT_REAL_SMALL = `${Math.max(8, Math.floor(WIDTH / 130))}px Terserah`;
        drawPiano();
    });

    function resumeAudioContext() {
        if (audioContext && audioContext.state === 'suspended') {
            audioContext.resume();
        }
        document.removeEventListener('click', resumeAudioContext);
        document.removeEventListener('keydown', resumeAudioContext);
    }
    document.addEventListener('click', resumeAudioContext);
    document.addEventListener('keydown', resumeAudioContext);
});