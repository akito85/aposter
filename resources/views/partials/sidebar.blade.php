<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="" class="simple-text logo-mini">
            <div class="logo-image-small">
            <!--
                <img src="../assets/img/logo-small.png">
            -->
            </div>
            <!-- <p>CT</p> -->
        </a>
        <a href="" class="simple-text logo-normal">
            APOSTER
            <!-- <div class="logo-image-big">
            <img src="../assets/img/logo-big.png">
            </div> -->
        </a>
    </div>
    <div class="sidebar-wrapper" style="overflow:hidden;">
        <ul class="nav">
            @if(Route::currentRouteName() == 'dashboard')
            <li class="active">
            @else
            <li>
            @endif
                <a href="/dashboard">
                    <i class="nc-icon nc-chart-bar-32"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            @if(Route::currentRouteName() == 'upload')
            <li class="active">
            @else
            <li>
            @endif
                <a href="/upload">
                    <i class="nc-icon nc-share-66"></i>
                    <p>Upload</p>
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    <i class="nc-icon nc-user-run"></i>
                    <p>{{ __('Logout') }}</p>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>