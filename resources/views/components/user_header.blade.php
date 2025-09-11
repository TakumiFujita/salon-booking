<li class="nav-item">
    <span class="nav-link" style="color: #ffffff;">ようこそ, {{ Auth::user()->name }} さん</span>
</li>
<li class="nav-item">
    <a href="{{ route('user.profile.edit') }}" class="nav-link text-white">プロフィール編集</a>
</li>
<li class="nav-item">
    <a href="{{ route('user.reservation.index') }}" class="nav-link text-white">予約一覧</a>
</li>
<li class="nav-item">
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">
            ログアウト
        </button>
    </form>
</li>
