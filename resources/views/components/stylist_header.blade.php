<li class="nav-item">
    <span class="nav-link" style="color: #ffffff;">ようこそ, {{ Auth::guard('stylist')->user()->name }} さん</span>
</li>
<li class="nav-item">
    <a href="{{ route('stylist.profile.edit') }}" class="nav-link text-white">プロフィール編集</a>
</li>
<li class="nav-item">
    <a href="{{ route('stylist.schedule.create') }}" class="nav-link text-white">スケジュール登録</a>
</li>
<li class="nav-item">
    <form id="logout-form" action="{{ route('stylist.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">
            ログアウト
        </button>
    </form>
</li>
