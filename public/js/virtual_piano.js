document.addEventListener('DOMContentLoaded', () => {
    // Elementos da tela de introdução
    const introScreen = document.getElementById('piano-intro-screen');
    const agreeButton = document.getElementById('agree-start-piano');
    const infoStatusIntro = document.getElementById('infoStatusIntro');

    // Elementos da área principal do piano
    const mainPianoArea = document.getElementById('piano-main-area');
    const canvas = document.getElementById('pianoCanvas');
    const ctx = canvas ? canvas.getContext('2d') : null;
    const videoElement = document.getElementById('videoFeed');
    const infoStatusPiano = document.getElementById('infoStatusPiano');

    function updateStatus(message, onPianoScreen = false) {
        const targetStatusElement = onPianoScreen ? infoStatusPiano : infoStatusIntro;
        if (targetStatusElement) {
            targetStatusElement.textContent = message;
        }
        console.log(message);
    }

    if (!canvas || !ctx || !videoElement || !mainPianoArea || !introScreen || !agreeButton) {
        updateStatus("Erro: Elementos essenciais da página do piano não foram encontrados.");
        if (introScreen) introScreen.style.display = 'block';
        if (mainPianoArea) mainPianoArea.style.display = 'none';
        return;
    }

    let WIDTH = canvas.offsetWidth;
    let HEIGHT = canvas.offsetHeight;

    const NUM_WHITE_KEYS = 52;
    const NUM_BLACK_KEYS = 36;
    let PIANO_DISPLAY_HEIGHT = HEIGHT * 0.55;
    let WHITE_KEY_HEIGHT = PIANO_DISPLAY_HEIGHT;
    let BLACK_KEY_HEIGHT = WHITE_KEY_HEIGHT * 0.6;
    const BLACK_KEY_WIDTH_FACTOR = 0.65;

    let FONT_SMALL = '';
    let FONT_REAL_SMALL = '';

    let whiteSounds = {};
    let blackSounds = {};
    let activeWhites = [];
    let activeBlacks = [];

    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    if (!audioContext) {
        updateStatus("Web Audio API não suportada neste navegador.");
        return;
    }

    const Y_AXIS_PRESS_THRESHOLD = 3;
    const XY_AXIS_STABILIZE_THRESHOLD = 7;
    const PRESS_COOLDOWN_MILLISECONDS = 200;
    const LANDMARK_RADIUS = 6;
    const CONNECTION_LINE_WIDTH = 4;

    const FINGER_LANDMARKS = {
        WRIST: 0, THUMB: { CMC: 1, MCP: 2, IP: 3, TIP: 4 }, INDEX: { MCP: 5, PIP: 6, DIP: 7, TIP: 8 }, MIDDLE: { MCP: 9, PIP: 10, DIP: 11, TIP: 12 }, RING: { MCP: 13, PIP: 14, DIP: 15, TIP: 16 }, PINKY: { MCP: 17, PIP: 18, DIP: 19, TIP: 20 }
    };
    const FINGER_TYPES_TO_TRACK_TOUCH = ['THUMB', 'INDEX', 'MIDDLE', 'RING', 'PINKY'];
    const CURVATURE_SLACK_Y = 18;
    let fingerTipStates = {};

    // ++ DEFINIÇÃO DAS FUNÇÕES playSound e playActually ++
    // Colocadas aqui para garantir que estão definidas antes de qualquer possível chamada.
    function playActually(audioBuffer) {
        if (!audioContext || !audioBuffer) return;
        const source = audioContext.createBufferSource();
        source.buffer = audioBuffer;
        source.connect(audioContext.destination);
        source.start(0);
    }

    function playSound(audioBuffer) {
        if (!audioBuffer) return;
        if (audioContext.state === 'suspended') {
            audioContext.resume().then(() => {
                playActually(audioBuffer);
            }).catch(e => console.error("Erro ao resumir AudioContext:", e));
        } else {
            playActually(audioBuffer);
        }
    }
    // -- FIM DAS DEFINIÇÕES playSound e playActually --

    async function loadSound(noteName) {
        const fileName = getNoteFileName(noteName); // de piano_lists.js
        const soundPath = `/sounds/${fileName}`; // Ajuste se o caminho dos seus sons for diferente
        try {
            const response = await fetch(soundPath);
            if (!response.ok) throw new Error(`Falha ao carregar ${soundPath}: ${response.statusText}`);
            const arrayBuffer = await response.arrayBuffer();
            return await audioContext.decodeAudioData(arrayBuffer);
        } catch (error) {
            console.error(`Erro ao carregar som ${noteName} (${soundPath}):`, error);
            throw error;
        }
    }

    let soundsLoaded = false;
    async function loadAllSounds() {
        if (soundsLoaded) return;
        updateStatus("Carregando instrumentos musicais...", false);

        const whiteSoundPromises = whiteNotes.map(note => loadSound(note).then(buffer => whiteSounds[note] = buffer).catch(e => console.warn(`Não foi possível carregar ${note}`)));
        const blackSoundPromises = blackNotes.map(note => loadSound(note).then(buffer => blackSounds[note] = buffer).catch(e => console.warn(`Não foi possível carregar ${note}`)));

        try {
            await Promise.allSettled([...whiteSoundPromises, ...blackSoundPromises]); // Usar allSettled para continuar mesmo se alguns sons falharem
            soundsLoaded = true;
            updateStatus("Instrumentos prontos! Aguardando sua permissão para a câmera.", false);
            console.log("Carregamento de sons concluído (alguns podem ter falhado).");
        } catch (error) { // Este catch pode não ser atingido com allSettled, mas é bom ter
            updateStatus("Erro significativo ao carregar instrumentos.", false);
            console.error("Erro final ao carregar sons:", error);
        }
    }

    function resizeAndSetupCanvas() {
        const pianoCardBody = document.querySelector('.piano-card-body-main'); // Usar a classe correta
        if (!pianoCardBody) {
            // console.error("Contêiner .piano-card-body-main não encontrado para o dimensionamento.");
            // Fallback para dimensões da janela se o contêiner não for encontrado
            WIDTH = window.innerWidth * 0.8; // Um pouco menor para caber
            HEIGHT = window.innerHeight * 0.4;
            canvas.width = WIDTH;
            canvas.height = HEIGHT;
        }
        // else {
        //     // Tenta usar as dimensões do CSS para max-width e max-height do canvas
        //     const canvasStyle = getComputedStyle(canvas);
        //     WIDTH = Math.min(pianoCardBody.offsetWidth - (parseFloat(getComputedStyle(pianoCardBody).paddingLeft) + parseFloat(getComputedStyle(pianoCardBody).paddingRight)), parseFloat(canvasStyle.maxWidth) || pianoCardBody.offsetWidth);
        //     HEIGHT = Math.min(pianoCardBody.offsetHeight - (parseFloat(getComputedStyle(pianoCardBody).paddingTop) + parseFloat(getComputedStyle(pianoCardBody).paddingBottom)), parseFloat(canvasStyle.maxHeight) || pianoCardBody.offsetHeight);

        //     canvas.width = WIDTH;
        //     canvas.height = HEIGHT;
        // }

        PIANO_DISPLAY_HEIGHT = HEIGHT * 0.85;
        WHITE_KEY_HEIGHT = PIANO_DISPLAY_HEIGHT;
        BLACK_KEY_HEIGHT = WHITE_KEY_HEIGHT * 0.6;

        FONT_SMALL = `${Math.max(9, Math.floor(WIDTH / 70))}px Terserah`;
        FONT_REAL_SMALL = `${Math.max(7, Math.floor(WIDTH / 90))}px Terserah`;
    }

    function drawPiano() {
        if (!ctx) return;
        // resizeAndSetupCanvas(); // Chamado antes de drawPiano quando necessário (init, resize, startPianoExperience)

        ctx.clearRect(0, 0, WIDTH, HEIGHT);
        ctx.fillStyle = '#dddddd';
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
                    currentBlackKeyIndex++;
                }
            }
        }
        activeWhites.forEach(active => {
            const key = whiteKeyRects[active.keyIndex];
            if (key && active.timeLeft > 0) { ctx.fillStyle = 'rgba(0,255,0,0.5)'; ctx.fillRect(key.x, key.y, key.width, key.height); active.timeLeft--; }
        });
        activeWhites = activeWhites.filter(active => active.timeLeft > 0);
        activeBlacks.forEach(active => {
            const key = blackKeyRects.find(k => k.index === active.keyIndex);
            if (key && active.timeLeft > 0) { ctx.fillStyle = 'rgba(100,255,100,0.6)'; ctx.fillRect(key.x, key.y, key.width, key.height); active.timeLeft--; }
        });
        activeBlacks = activeBlacks.filter(active => active.timeLeft > 0);
    }

    let handsMP;
    async function setupMediaPipeHands() {
        if (handsMP) return;
        updateStatus("Configurando câmera e detecção...", true);

        handsMP = new Hands({
            locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands@0.4/${file}`
        });
        handsMP.setOptions({
            maxNumHands: 2, modelComplexity: 1, minDetectionConfidence: 0.6, minTrackingConfidence: 0.55
        });
        handsMP.onResults(onResults);

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { width: { ideal: 640 }, height: { ideal: 480 } } });
            videoElement.srcObject = stream;
            await new Promise((resolve, reject) => {
                videoElement.onloadedmetadata = () => {
                    videoElement.play().then(resolve).catch(reject);
                };
                videoElement.onerror = reject; // Adicionar tratamento de erro para o vídeo
            });

            const camera = new Camera(videoElement, {
                onFrame: async () => {
                    if (videoElement.readyState >= HTMLMediaElement.HAVE_ENOUGH_DATA && videoElement.videoWidth > 0) { // Checagem mais robusta
                        await handsMP.send({ image: videoElement });
                    }
                },
            });
            await camera.start();
            updateStatus("Câmera iniciada. Posicione suas mãos!", true);
        } catch (err) {
            console.error("Erro ao acessar ou iniciar a câmera: ", err);
            updateStatus("Erro ao acessar a câmera. Verifique as permissões e recarregue a página.", true);
            if (mainPianoArea) mainPianoArea.style.display = 'none';
            if (introScreen) {
                introScreen.style.display = 'block';
                updateStatus("Falha ao iniciar a câmera. Verifique as permissões e tente novamente.", false);
                if (agreeButton) {
                    agreeButton.disabled = false;
                    agreeButton.textContent = "Concordo e Iniciar o Piano!";
                }
            }
        }
    }

    function onResults(results) {
        drawPiano(); // Desenha o piano base primeiro

        if (results.multiHandLandmarks) {
            for (let handIndex = 0; handIndex < results.multiHandLandmarks.length; handIndex++) {
                const landmarks = results.multiHandLandmarks[handIndex];
                if (window.drawConnectors && window.HAND_CONNECTIONS) {
                    const mirroredLandmarks = landmarks.map(lm => ({ ...lm, x: 1 - lm.x }));
                    drawConnectors(ctx, mirroredLandmarks, HAND_CONNECTIONS, { color: 'rgba(255, 255, 255, 0.6)', lineWidth: CONNECTION_LINE_WIDTH });
                }

                FINGER_TYPES_TO_TRACK_TOUCH.forEach(fingerType => {
                    const fingerLMIndices = FINGER_LANDMARKS[fingerType];
                    let tipLM, pipLM, dipLM;
                    tipLM = landmarks[fingerLMIndices.TIP];
                    if (fingerType === 'THUMB') {
                        pipLM = landmarks[fingerLMIndices.MCP]; dipLM = landmarks[fingerLMIndices.IP];
                    } else {
                        pipLM = landmarks[fingerLMIndices.PIP]; dipLM = landmarks[fingerLMIndices.DIP];
                    }
                    if (!tipLM || !pipLM || !dipLM) return;

                    const tipX = (1 - tipLM.x) * WIDTH; const tipY = tipLM.y * HEIGHT;
                    ctx.beginPath(); ctx.arc(tipX, tipY, LANDMARK_RADIUS, 0, 2 * Math.PI);
                    ctx.fillStyle = 'rgba(0, 150, 255, 0.7)'; ctx.fill();

                    const tipId = `hand${handIndex}_${fingerType}`;
                    if (!fingerTipStates[tipId]) {
                        fingerTipStates[tipId] = { lastX: tipX, lastY: tipY, isPrimedToPress: false, lastPressTime: 0 };
                    }
                    let state = fingerTipStates[tipId];
                    const deltaX = tipX - state.lastX; const deltaY = tipY - state.lastY;
                    const speedXY = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

                    let isOnKey = false; let currentKeyInfo = null;
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
                        if (deltaY > Y_AXIS_PRESS_THRESHOLD && !state.isPrimedToPress) state.isPrimedToPress = true;
                        if (state.isPrimedToPress) isReadyToPress = true;
                        if (state.isPrimedToPress && isOnKey) {
                            ctx.beginPath(); ctx.arc(tipX, tipY - (LANDMARK_RADIUS * 1.8), LANDMARK_RADIUS / 1.5, 0, 2 * Math.PI); ctx.fillStyle = 'rgba(255,255,200,0.8)'; ctx.fill();
                        }
                    } else {
                        const pipY = pipLM.y * HEIGHT; const dipY = dipLM.y * HEIGHT;
                        if (pipY < tipY + CURVATURE_SLACK_Y && dipY < tipY + CURVATURE_SLACK_Y) {
                            if (pipY < dipY + (CURVATURE_SLACK_Y / 1.5)) {
                                isReadyToPress = true;
                                if (isOnKey) {
                                    ctx.beginPath(); ctx.arc(tipX, tipY - (LANDMARK_RADIUS * 1.8), LANDMARK_RADIUS / 1.5, 0, 2 * Math.PI); ctx.fillStyle = 'rgba(255,165,0,0.8)'; ctx.fill();
                                }
                            }
                        }
                    }
                    const currentPianoDisplayHeight = HEIGHT * PIANO_DISPLAY_HEIGHT_RATIO; // Use a ratio consistente
                    if (isOnKey && isReadyToPress && tipY > (HEIGHT - currentPianoDisplayHeight - WHITE_KEY_HEIGHT * 0.05) && tipY < HEIGHT) {
                        if (speedXY < XY_AXIS_STABILIZE_THRESHOLD) {
                            if (Date.now() - state.lastPressTime > PRESS_COOLDOWN_MILLISECONDS) {
                                if (currentKeyInfo.type === 'white') {
                                    if (whiteSounds[currentKeyInfo.note] && !isActive(activeWhites, currentKeyInfo.index)) {
                                        playSound(whiteSounds[currentKeyInfo.note]); // CHAMADA A playSound
                                        activeWhites.push({ keyIndex: currentKeyInfo.index, timeLeft: 15 });
                                    }
                                } else if (currentKeyInfo.type === 'black') {
                                    if (blackSounds[currentKeyInfo.note] && !isActive(activeBlacks, currentKeyInfo.index)) {
                                        playSound(blackSounds[currentKeyInfo.note]); // CHAMADA A playSound
                                        activeBlacks.push({ keyIndex: currentKeyInfo.index, timeLeft: 15 });
                                    }
                                }
                                state.lastPressTime = Date.now(); state.isPrimedToPress = false;
                                ctx.beginPath(); ctx.arc(tipX, tipY, LANDMARK_RADIUS + 2, 0, 2 * Math.PI); ctx.fillStyle = 'rgba(0,255,0,0.7)'; ctx.fill();
                            }
                        }
                    } else {
                        if (fingerType === 'THUMB') state.isPrimedToPress = false;
                    }
                    if (deltaY < -Y_AXIS_PRESS_THRESHOLD && fingerType === 'THUMB') state.isPrimedToPress = false;
                    state.lastX = tipX; state.lastY = tipY;
                });
            }
        }
    }
    // Defina PIANO_DISPLAY_HEIGHT_RATIO no escopo global ou passe como parâmetro
    const PIANO_DISPLAY_HEIGHT_RATIO = 0.55; // Ou o valor que você usou consistentemente

    function isActive(activeList, keyIndex) {
        return activeList.some(item => item.keyIndex === keyIndex && item.timeLeft > 0);
    }

    async function startPianoExperience() {
        if (!soundsLoaded) {
            await loadAllSounds(); // Esperar os sons carregarem se ainda não o fizeram
        }
        // Verificar novamente se os sons foram carregados, pois loadAllSounds pode não ter sido chamado antes
        if (!soundsLoaded && audioContext.state !== 'running') {
            updateStatus("Não foi possível carregar os instrumentos. O piano pode não ter som.", true);
        }

        updateStatus("Preparando o piano...", true);
        if (introScreen) introScreen.style.display = 'none';
        if (mainPianoArea) mainPianoArea.style.display = 'block';

        resizeAndSetupCanvas(); // Configura dimensões do canvas
        await setupMediaPipeHands();
        drawPiano(); // Desenha o piano inicial
    }

    if (introScreen && agreeButton) {
        loadAllSounds(); // Pré-carregar sons
        agreeButton.addEventListener('click', () => {
            agreeButton.disabled = true;
            agreeButton.textContent = "Iniciando...";
            startPianoExperience().catch(err => {
                console.error("Erro ao iniciar a experiência do piano:", err);
                updateStatus("Falha ao iniciar o piano. Verifique as permissões e o console.", false);
                if (introScreen) introScreen.style.display = 'block';
                if (mainPianoArea) mainPianoArea.style.display = 'none';
                agreeButton.disabled = false;
                agreeButton.textContent = "Concordo e Iniciar o Piano!";
            });
        });
    } else {
        // Fallback se a tela de introdução não existir
        async function fallbackInit() {
            resizeAndSetupCanvas();
            await loadAllSounds();
            await setupMediaPipeHands();
            drawPiano();
        }
        fallbackInit().catch(err => {
            console.error("Erro na inicialização de fallback:", err);
            updateStatus("Falha crítica ao inicializar.");
        });
    }

    window.addEventListener('resize', () => {
        if (mainPianoArea && mainPianoArea.style.display !== 'none') {
            resizeAndSetupCanvas();
            drawPiano();
        }
    });

    function resumeAudioContext() {
        if (audioContext && audioContext.state === 'suspended') {
            audioContext.resume().catch(e => console.error("Erro ao resumir AudioContext no evento:", e));
        }
        // Remover listeners após a primeira interação para evitar múltiplas execuções
        document.removeEventListener('click', resumeAudioContext);
        document.removeEventListener('keydown', resumeAudioContext);
        document.removeEventListener('touchstart', resumeAudioContext);
    }
    document.addEventListener('click', resumeAudioContext);
    document.addEventListener('keydown', resumeAudioContext);
    document.addEventListener('touchstart', resumeAudioContext); // Adicionar para mobile
});
