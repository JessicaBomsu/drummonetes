<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        @if(auth()->user())
            <a class="navbar-brand" href="{{ route('index')}}">
                <img src="{{ asset('img/logo.png')}}" alt="DRUMMONETES Logo" height="40" class="d-inline-block align-middle me-2">
            </a>
        @else
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png')}}" alt="DRUMMONETES Logo" height="40" class="d-inline-block align-middle me-2">
            </a>
        @endif
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('quem-somos') ? 'active' : '' }}" href="{{ route('quem-somos') }}">Quem somos</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('content.*') ? 'active' : '' }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        Conteúdo
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('content.introducao') }}">Introdução</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('content.conceitos') }}">Conceitos</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('content.historia') }}">História</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('content.conceitos-avancados') }}">Conceitos avançados</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('content.aplicacoes') }}">Aplicações</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('content.empregabilidade') }}">Empregabilidade</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('content.conclusao') }}">Conclusão</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('content.links-uteis') }}">Referências</a>
                        </li>
                    </ul>
                </li>
                @if(auth()->user())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('piano') ? 'active' : '' }}" href="{{ route('piano') }}">Projeto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('quiz') ? 'active' : '' }}" href="{{ route('quiz') }}">Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ranking') ? 'active' : '' }}" href="{{ route('ranking') }}">Ranking</a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Registrar</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @auth
                                @if(auth()->user()->is_admin)
                                    <li><a class="dropdown-item" href="#">Usuário</a></li>
                                    <li><a class="dropdown-item" href="{{route('questions.create')}}">Criar questões</a></li>
                                @endif
                            @endauth
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Sair</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>