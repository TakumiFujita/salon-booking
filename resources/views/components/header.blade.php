<header class="navbar navbar-expand-lg" style="background-color: #a1887f;">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}" style="color: #ffffff; font-weight: bold;">
            MySalon
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <span class="nav-link" style="color: #ffffff;">ようこそ, {{ Auth::user()->name }} さん</span>
                    </li>
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                ログアウト
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}" style="color: #ffffff;">ログイン</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}" style="color: #ffffff;">ユーザー登録</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</header>
