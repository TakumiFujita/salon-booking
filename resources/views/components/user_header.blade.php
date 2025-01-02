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
