<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="justify-content-between"></div>
      <div>

      </div>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('topup.index') }}">Topup</a>
              </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{route('profile.edit')}}">@lang('lang.Profile')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('wishlist.view') }}">@lang('lang.Wishlist')</a>
        </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('chat.index')}}">@lang('lang.Chat')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('notifications.index') }}">@lang('lang.Notification')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('explore.search')}}">@lang('lang.Explore')</a>
        </li>
        <div class="dropdown p-1">
            <button class="btn btn-primary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                @lang('lang.Language')
            </button>
            <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                <li><a class="dropdown-item" href="{{ route('set-locale', 'en') }}">@lang('lang.English')</a></li>
                <li><a class="dropdown-item" href="{{ route('set-locale', 'id') }}">@lang('lang.Indonesia')</a></li>
            </ul>
        </div>
          @if (Auth::check())

            <form action="{{route('logout')}}" method="POST">
                @csrf
                <button type="submit" style="background-color: #f799c8; color: white;" class="btn text-white px-4 py-2 rounded-md border-0 hover:bg-pink-600 focus:ring-2 focus:ring-pink-500">
                    Logout
                </button>
            </form>
            @else
            <a
            href="{{ route('login') }}"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >     Login
            </a>

             <a
            href="{{ route('register') }}"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
            Register
            </a>
            @endif
        </ul>
      </div>
    </div>
  </nav>
