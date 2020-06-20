<nav class="navbar navbar-expand-lg navbar-light" >
    <button style="background-color:#fccc04 !important;" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span  class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav page-links" >
            <li class="nav-item active">
                <a class="nav-link" href="{{route('bustravel.homepage')}}" style="color:white !important">
                    Home <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{route('bustravel.bus.times')}}" style="color:white !important">
                    Bus Times <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('bustravel.bus.stations')}}" style="color:white !important">Stations </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('bustravel.bus.faqs')}}" style="color:white !important">Help </a>
            </li>
        </ul>
        @if (session('cart'))
        <ul class="navbar-nav">
            <li class="nav-item cart-ico-area">
                <a class="nav-link" href="{{route('bustravel.cart')}}">
                    <span class="cart-ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M10 19.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5zm3.5-1.5c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm1.336-5l1.977-7h-16.813l2.938 7h11.898zm4.969-10l-3.432 12h-12.597l.839 2h13.239l3.474-12h1.929l.743-2h-4.195z" />
                        </svg>
                    </span>
                    <span class="cart-items-number">{{count(session('cart.items'))}}</span>
                </a>
            </li>
        </ul>
        @endif
        @if (Route::has('login'))
        <div class="dropdown accnt-area-menu">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="account-ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm7.753 18.305c-.261-.586-.789-.991-1.871-1.241-2.293-.529-4.428-.993-3.393-2.945 3.145-5.942.833-9.119-2.489-9.119-3.388 0-5.644 3.299-2.489 9.119 1.066 1.964-1.148 2.427-3.393 2.945-1.084.25-1.608.658-1.867 1.246-1.405-1.723-2.251-3.919-2.251-6.31 0-5.514 4.486-10 10-10s10 4.486 10 10c0 2.389-.845 4.583-2.247 6.305z" />
                    </svg>
                </span>
                <span>Your Account</span>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @auth


                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>


                @else
                <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                <a class="dropdown-item" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
        @endif
    </div>
</nav>
