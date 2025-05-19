// Verificar se todas as dependências estão carregadas
if (typeof tf === 'undefined' || typeof handpose === 'undefined') {
    throw new Error('Dependências não carregadas');
}

// Configurações do piano
const PIANO_CONFIG = {
    whiteKeys: ['A0', 'B0', 'C1', 'D1', 'E1', 'F1', 'G1', 'A1', 'B1', 'C2', 'D2', 'E2', 'F2', 'G2', 'A2', 'B2', 'C3', 'D3', 'E3', 'F3', 'G3', 'A3', 'B3', 'C4', 'D4', 'E4', 'F4', 'G4', 'A4', 'B4', 'C5', 'D5', 'E5', 'F5', 'G5', 'A5', 'B5', 'C6', 'D6', 'E6', 'F6', 'G6', 'A6', 'B6', 'C7', 'D7', 'E7', 'F7', 'G7', 'A7', 'B7', 'C8'],
    blackKeys: ['A#0', 'C#1', 'D#1', 'F#1', 'G#1', 'A#1', 'C#2', 'D#2', 'F#2', 'G#2', 'A#2', 'C#3', 'D#3', 'F#3', 'G#3', 'A#3', 'C#4', 'D#4', 'F#4', 'G#4', 'A#4', 'C#5', 'D#5', 'F#5', 'G#5', 'A#5', 'C#6', 'D#6', 'F#6', 'G#6', 'A#6', 'C#7', 'D#7', 'F#7', 'G#7', 'A#7'],
    keyDisplayDuration: 200 // ms
};

class VirtualPiano {
    constructor() {
        this.audioContext = null;
        this.handposeModel = null;
        this.videoElement = document.getElementById('webcam');
        this.canvas = document.getElementById('pianoCanvas');
        this.ctx = this.canvas.getContext('2d');
        this.activeKeys = {};
        this.keyRects = { white: [], black: [] };
        this.previousHandPos = { x: 0, y: 0 };
        
        this.init();
    }
    
    async init() {
        try {
            await this.setupWebcam();
            await this.setupAudio();
            await this.loadHandposeModel();
            this.setupCanvas();
            this.setupEventListeners();
            this.drawPiano();
        } catch (error) {
            console.error('Erro na inicialização:', error);
            document.getElementById('loading-text').innerHTML = 
                `<div class="alert alert-danger">Erro: ${error.message}</div>`;
        }
    }
    
    async setupWebcam() {
        if (!navigator.mediaDevices?.getUserMedia) {
            throw new Error('Webcam não suportada neste navegador');
        }
        
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 1280 }, 
                height: { ideal: 720 }, 
                facingMode: 'user' 
            }, 
            audio: false 
        });
        
        this.videoElement.srcObject = stream;
        return new Promise((resolve) => {
            this.videoElement.onloadedmetadata = () => {
                this.videoElement.play();
                resolve();
            };
        });
    }
    
    async setupAudio() {
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
    }
    
    async loadHandposeModel() {
        this.handposeModel = await handpose.load();
        console.log('Modelo HandPose carregado');
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
    
    setupEventListeners() {
        document.getElementById('startBtn').addEventListener('click', () => {
            this.detectHands();
        });
    }
    
    // ... (implemente os outros métodos: drawPiano, detectHands, playNote, etc.)
}

// Inicializar o piano quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new VirtualPiano();
});