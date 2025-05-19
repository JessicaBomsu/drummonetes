@extends('layouts.app')

@section('title', 'Piano Virtual')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4">Piano Virtual</h1>
    
    <div class="card shadow">
        <div class="card-body">
            <div id="status" class="alert alert-info">
                <i class="fas fa-info-circle"></i> <span id="status-text">Preparando o piano...</span>
            </div>
            
            <div id="piano-controls" style="display: none;">
                <button id="startBtn" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-camera"></i> Iniciar Webcam
                </button>
            </div>
            
            <div class="piano-container mt-3 position-relative" style="height: 400px; background: #f0f0f0;">
                {{-- <canvas id="pianoCanvas"></canvas> --}}
                <canvas id="pianoCanvas"></canvas>
                {{-- <video id="webcam" playsinline style="display: none;"></video> --}}
                <video id="webcam" autoplay playsinline muted></video>
            </div>
        </div>
    </div>
</div>

{{-- <script>
// Debug completo - mostra todos os passos no console e na interface
console.log('Script iniciado');

function updateStatus(text, isError = false) {
    const statusEl = document.getElementById('status');
    const textEl = document.getElementById('status-text');
    
    textEl.textContent = text;
    statusEl.className = isError ? 'alert alert-danger' : 'alert alert-info';
    console.log(text);
}

// Verificação de dependências
function checkDependencies() {
    updateStatus('Verificando dependências...');
    
    if (!window.tf) {
        updateStatus('Erro: TensorFlow.js não carregado', true);
        return false;
    }
    
    // if (!window.handpose) {
    //     updateStatus('Erro: Handpose não carregado', true);
    //     return false;
    // }
    
    return true;
}

// Inicialização principal
async function initializePiano() {
    if (!checkDependencies()) return;

    try {
        updateStatus('Inicializando componentes...');
        
        // Mostrar controles
        document.getElementById('piano-controls').style.display = 'block';
        
        // Configurar evento do botão
        document.getElementById('startBtn').addEventListener('click', async () => {
            updateStatus('Solicitando acesso à webcam...');
            
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: true 
                });
                
                const video = document.getElementById('webcam');
                video.srcObject = stream;
                video.style.display = 'block';
                video.play();
                
                updateStatus('Webcam ativada com sucesso!');
                console.log('Webcam iniciada');
                
                // Aqui você pode chamar o restante da inicialização do piano
                startPiano();
                
            } catch (error) {
                updateStatus('Erro ao acessar webcam: ' + error.message, true);
                console.error('Webcam error:', error);
            }
        });
        
    } catch (error) {
        updateStatus('Erro na inicialização: ' + error.message, true);
        console.error('Initialization error:', error);
    }
}

// Função para iniciar o piano (será chamada após a webcam)
function startPiano() {
    updateStatus('Preparando o piano...');
    console.log('Iniciando piano...');
    
    // Criar elemento script
    const script = document.createElement('script');
    script.src = "{{ asset('js/piano.js') }}";
    
    // Sucesso no carregamento
    script.onload = () => {
        updateStatus('Piano carregado, inicializando...');
        console.log('piano.js carregado com sucesso');
        
        try {
            // Inicializa o piano
            new VirtualPiano();
            updateStatus('Piano pronto para uso!');
            console.log('Piano inicializado com sucesso');
        } catch (e) {
            updateStatus('Erro ao iniciar piano: ' + e.message, true);
            console.error('Erro na inicialização:', e);
        }
    };
    
    // Tratamento de erros
    script.onerror = () => {
        updateStatus('Falha ao carregar o piano. Recarregue a página.', true);
        console.error('Falha ao carregar piano.js');
    };
    
    // Adiciona ao DOM
    document.body.appendChild(script);
}

// Iniciar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    updateStatus('Página carregada, iniciando...');
    initializePiano();
});
</script> --}}
{{-- ... (código anterior do blade) ... --}}
<script>
    // Debug completo - mostra todos os passos no console e na interface
    console.log('Script do Blade iniciado');
    
    function updateStatus(text, isError = false) {
        const statusEl = document.getElementById('status');
        const textEl = document.getElementById('status-text');
        if (!statusEl || !textEl) {
            console.warn("Elementos de status não encontrados.");
            return;
        }
        textEl.textContent = text;
        statusEl.className = isError ? 'alert alert-danger' : 'alert alert-info';
        console.log(text);
    }
    
    // Verificação de dependências
    function checkDependencies() {
        updateStatus('Verificando dependências...');
        if (!window.tf) { // TensorFlow.js ainda é relevante se MediaPipe Hands o utiliza como backend ou se você planeja usar tfjs
            // updateStatus('Aviso: TensorFlow.js não carregado explicitamente (Pode ser uma dependência do MediaPipe)', false);
            // Nota: MediaPipe Hands agora é mais autônomo, mas é bom estar ciente.
        }
        // Não há 'window.handpose' se estivermos usando MediaPipe Hands diretamente.
        return true;
    }
    
    // Inicialização principal
    async function initializeApp() { // Renomeado para evitar conflito com initializePiano dentro da classe
        if (!checkDependencies()) return;
    
        try {
            updateStatus('Inicializando componentes da UI...');
            document.getElementById('piano-controls').style.display = 'block';
    
            document.getElementById('startBtn').addEventListener('click', async () => {
                updateStatus('Solicitando acesso à webcam...');
                try {
                    // A configuração da webcam agora é feita dentro da classe VirtualPiano
                    // Apenas precisamos iniciar o carregamento do piano.js que contém a classe.
                    loadAndStartPiano();
                } catch (error) {
                    updateStatus('Erro ao preparar para iniciar webcam: ' + error.message, true);
                    console.error('Webcam preparation error:', error);
                }
            });
            updateStatus('Pronto para iniciar a webcam e o piano.');
    
        } catch (error) {
            updateStatus('Erro na inicialização da UI: ' + error.message, true);
            console.error('UI Initialization error:', error);
        }
    }
    
    function loadAndStartPiano() {
        updateStatus('Carregando script do piano...');
        console.log('Iniciando carregamento de piano.js...');
    
        if (window.virtualPianoInstance) {
            updateStatus('Instância do piano já existe. Reiniciando se necessário ou ignorando.');
            console.log('Instância do piano já existe.');
            // Aqui você pode adicionar lógica para reiniciar a detecção se necessário
            // Por exemplo: window.virtualPianoInstance.detectHands();
            // Ou simplesmente informar que já está carregado.
            // Para este exemplo, vamos permitir recarregar o script se o usuário clicar novamente,
            // mas idealmente a classe VirtualPiano lidaria com re-inicialização.
        }
    
        const script = document.createElement('script');
        script.src = "{{ asset('js/piano.js') }}"; // Carrega o piano.js atualizado
        script.async = true; // Carregar assincronamente
    
        script.onload = () => {
            updateStatus('Script do piano carregado. Inicializando VirtualPiano...');
            console.log('piano.js carregado com sucesso');
            try {
                if (typeof VirtualPiano !== 'undefined') {
                     // Garante que não haja múltiplas instâncias se o botão for clicado várias vezes
                    if (!window.virtualPianoInstance) {
                        window.virtualPianoInstance = new VirtualPiano(); // Cria a instância
                        updateStatus('Piano virtual pronto para uso!');
                        console.log('Nova instância de VirtualPiano criada e inicializada.');
                    } else {
                        updateStatus('Piano virtual já inicializado.');
                        console.log('VirtualPiano já instanciado.');
                        // Se você quiser re-chamar algo, como a detecção:
                        // window.virtualPianoInstance.init(); // ou uma função específica de reinício
                    }
                } else {
                    updateStatus('Erro: Classe VirtualPiano não definida após carregar o script.', true);
                    console.error('Classe VirtualPiano não encontrada em piano.js');
                }
            } catch (e) {
                updateStatus('Erro ao instanciar ou inicializar VirtualPiano: ' + e.message, true);
                console.error('Erro na instanciação/inicialização do VirtualPiano:', e);
            }
        };
    
        script.onerror = () => {
            updateStatus('Falha ao carregar o script do piano (piano.js). Verifique o caminho e o console.', true);
            console.error('Falha ao carregar piano.js');
        };
    
        document.body.appendChild(script);
    }
    
    // Iniciar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', () => {
        updateStatus('Página carregada, preparando UI...');
        initializeApp(); // Chama a função que configura o botão "Iniciar"
    });
    </script>
@endsection