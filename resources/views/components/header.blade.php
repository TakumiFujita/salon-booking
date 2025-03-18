<header class="navbar navbar-expand-lg" style="background-color: #a1887f;">
    <div class="container">
        <a class="navbar-brand"
            href="{{ Auth::guard('web')->check() ? route('user.reservation.home') : (Auth::guard('stylist')->check() ? route('stylist.home') : route('login')) }}"
            style="color: #ffffff; font-weight: bold;">
            MySalon
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @php
                    $isUserLoggedIn = Auth::guard('web')->check();
                    $isStylistLoggedIn = Auth::guard('stylist')->check();
                @endphp

                @if ($isUserLoggedIn)
                    @include('components.user_header')
                @elseif ($isStylistLoggedIn)
                    @include('components.stylist_header')
                @else
                    <!-- ログインしていない場合の処理 -->
                @endif
            </ul>
        </div>
    </div>
</header>
