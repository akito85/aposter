<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <!--
            <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            -->
            <a class="navbar-brand" href="javascript:;">APOSTER</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
        @if (Auth::check())
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        @endif
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
        @if (Auth::check())
            <ul class="navbar-nav">
                @if(Route::currentRouteName() == 'dashboard')
                <li class="nav-item active">
                @else
                <li class="nav-item">
                @endif
                    <a class="nav-link" href="/dashboard">
                        <i class="nc-icon nc-chart-bar-32"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if(Route::currentRouteName() == 'upload')
                <li class="nav-item active">
                @else
                <li class="nav-item">
                @endif
                    <a class="nav-link" href="/upload">
                        <i class="nc-icon nc-share-66"></i>
                        <p>Upload</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ secure_url('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        <i class="nc-icon nc-user-run"></i>
                        <p>{{ __('Logout') }}</p>
                    </a>

                    <form id="logout-form" action="{{ secure_url('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        @endif
        </div>
    </div>
</nav>