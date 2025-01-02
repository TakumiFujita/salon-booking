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
                @auth('web')
                    @include('components.user_header')
                @endauth
                @auth('stylist')
                    @include('components.stylist_header')
                @endauth
            </ul>
        </div>
    </div>
</header>
