<li class="nav-item">
    <span class="nav-link" style="color: #ffffff;">ようこそ, {{ Auth::guard('stylist')->user()->name }} さん</span>
</li>
<li class="nav-item">
    <form id="logout-form" action="{{ route('stylist.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">
            ログアウト
        </button>
    </form>
</li>
