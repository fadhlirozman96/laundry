<!-- Mobile Responsive Styles v5.0 - CLEAR CACHE & HARD REFRESH (Ctrl+Shift+R) -->
<style>
/* Prevent horizontal scroll on all mobile devices */
* {
    box-sizing: border-box !important;
}

html, body {
    overflow-x: hidden !important;
    max-width: 100vw !important;
}

.page-wrapper {
    overflow-x: hidden !important;
    max-width: 100vw !important;
}

.header,
.header.header-responsive {
    max-width: 100vw !important;
    box-sizing: border-box !important;
    width: 100% !important;
}

/* Force all header children to not overflow */
.header * {
    box-sizing: border-box !important;
}

.header .header-left,
.header .user-menu > li,
.header #mobile_btn,
.header .mobile-user-menu {
    flex-shrink: 0 !important;
}

/* Fix z-index layering */
.header #mobile_btn {
    z-index: 10 !important;
    position: relative !important;
}

.header .header-left {
    z-index: 9 !important;
    position: relative !important;
}

.header .user-menu {
    z-index: 8 !important;
    position: relative !important;
}

.header .mobile-user-menu {
    z-index: 11 !important;
    position: relative !important;
}

/* Mobile Header Responsive Fixes */
@media only screen and (max-width: 991px) {
    /* Header adjustments */
    .header.header-responsive,
    .header {
        padding: 8px 10px !important;
        flex-wrap: nowrap !important;
        overflow: visible !important;
        width: 100% !important;
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 6px !important;
    }
    
    /* Logo section - bigger and beside hamburger */
    .header .header-left,
    .header-left {
        flex: 0 0 auto !important;
        max-width: 180px !important;
        min-width: 140px !important;
        margin-right: 0 !important;
        margin-left: 8px !important;
        padding: 0 !important;
        order: 1 !important;
        background: none !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    .header .header-left .logo,
    .header-left .logo {
        margin: 0 !important;
        padding: 0 !important;
        background: none !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    .header .header-left .logo img,
    .header-left .logo img {
        max-height: 45px !important;
        width: auto !important;
        height: auto !important;
        display: block !important;
    }
    
    .header .header-left #toggle_btn,
    .header-left #toggle_btn {
        display: none !important;
    }
    
    .header .header-left a:not(.logo),
    .header-left a:not(.logo) {
        display: none !important;
    }
    
    .header .header-left.active {
        margin-left: 0 !important;
        padding-left: 0 !important;
    }
    
    /* Mobile menu button */
    .header #mobile_btn,
    #mobile_btn {
        order: 0 !important;
        margin-right: 0 !important;
        margin-left: 0 !important;
        flex: 0 0 auto !important;
        width: 32px !important;
        padding: 5px !important;
    }
    
    .header #mobile_btn .bar-icon,
    #mobile_btn .bar-icon {
        width: 100% !important;
    }
    
    /* User menu container */
    .header .user-menu.user-menu-responsive,
    .header .user-menu,
    .user-menu {
        flex: 1 1 auto !important;
        overflow: visible !important;
        justify-content: flex-end !important;
        min-width: 0 !important;
        margin-left: auto !important;
        flex-wrap: nowrap !important;
        order: 2 !important;
        padding-left: 10px !important;
    }
    
    .header .user-menu > li,
    .user-menu > li {
        flex-shrink: 0 !important;
        white-space: nowrap !important;
        margin-left: 0 !important;
    }
    
    /* Mobile user menu button - pushed to right edge */
    .header .mobile-user-menu,
    .mobile-user-menu {
        display: flex !important;
        align-items: center !important;
        flex-shrink: 0 !important;
        order: 3 !important;
        margin-left: 0 !important;
        margin-right: -5px !important;
        padding: 0 !important;
    }
    
    .header .mobile-user-menu .nav-link,
    .mobile-user-menu .nav-link {
        padding: 2px 5px !important;
        display: flex !important;
        align-items: center !important;
        margin: 0 !important;
    }
    
    .header .mobile-user-menu .nav-link i,
    .mobile-user-menu .nav-link i {
        font-size: 20px !important;
    }
    
    /* Hide POS button text on mobile, show only icon */
    .header .pos-btn-nav .btn .pos-text,
    .pos-btn-nav .btn .pos-text {
        display: none !important;
    }
    
    .header .pos-btn-nav .btn,
    .pos-btn-nav .btn {
        padding: 6px 10px !important;
        min-width: auto !important;
        width: auto !important;
    }
    
    .header .pos-btn-nav .btn .pos-icon,
    .pos-btn-nav .btn .pos-icon {
        margin: 0 !important;
        width: 18px !important;
        height: 18px !important;
    }
    
    .header .pos-btn-nav,
    .pos-btn-nav {
        margin-right: 5px !important;
        margin-left: 0 !important;
        flex-shrink: 0 !important;
    }
    
    /* Make store dropdown more compact - hide on mobile */
    .header .select-store-dropdown,
    .select-store-dropdown {
        display: none !important;
    }
    
    /* Compact user menu */
    .header .user-menu,
    .user-menu {
        gap: 5px !important;
        display: flex !important;
        align-items: center !important;
    }
    
    .header .user-menu .me-2,
    .header .user-menu .me-3,
    .user-menu .me-2,
    .user-menu .me-3 {
        margin-right: 0 !important;
    }
    
    .header .user-menu > *,
    .user-menu > * {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    /* Hide user profile on mobile - show only in 3-dot menu */
    .header .userset,
    .userset {
        display: none !important;
    }
    
    /* On desktop, keep everything visible */
    @media only screen and (min-width: 992px) {
        .header .userset,
        .userset {
            display: flex !important;
        }
        
        .header .select-store-dropdown,
        .select-store-dropdown {
            display: flex !important;
        }
        
        .header .mobile-user-menu,
        .mobile-user-menu {
            display: none !important;
        }
        
        .header .header-left,
        .header-left {
            margin-left: 0 !important;
            padding-left: 0 !important;
            max-width: 200px !important;
        }
        
        .header .header-left .logo img,
        .header-left .logo img {
            max-height: 50px !important;
        }
    }
    
    /* Adjust settings icon */
    .header .nav-item-box,
    .nav-item-box {
        margin-right: 5px !important;
        margin-left: 0 !important;
        flex-shrink: 0 !important;
    }
    
    .header .nav-item-box a,
    .nav-item-box a {
        padding: 5px !important;
        font-size: 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 32px !important;
        height: 32px !important;
    }
}

@media only screen and (max-width: 767px) {
    /* Extra compact for smaller phones */
    .header.header-responsive,
    .header {
        padding: 6px 8px !important;
        gap: 5px !important;
    }
    
    .header .header-left,
    .header-left {
        max-width: 160px !important;
        margin-left: 6px !important;
        padding: 0 !important;
    }
    
    .header .header-left .logo img,
    .header-left .logo img {
        max-height: 42px !important;
    }
    
    .header #mobile_btn,
    #mobile_btn {
        margin: 0 !important;
        width: 30px !important;
        padding: 4px !important;
    }
    
    .header .mobile-user-menu,
    .mobile-user-menu {
        margin-right: -3px !important;
    }
    
    .header .pos-btn-nav .btn,
    .pos-btn-nav .btn {
        padding: 5px 8px !important;
    }
    
    .header .pos-btn-nav .btn svg,
    .pos-btn-nav .btn svg,
    .header .pos-btn-nav .btn .pos-icon,
    .pos-btn-nav .btn .pos-icon {
        width: 16px !important;
        height: 16px !important;
    }
    
    .header .user-menu,
    .user-menu {
        gap: 5px !important;
        padding-left: 6px !important;
    }
    
    .header .user-menu .me-2,
    .header .user-menu .me-3,
    .user-menu .me-2,
    .user-menu .me-3,
    .header .pos-btn-nav,
    .pos-btn-nav,
    .header .nav-item-box,
    .nav-item-box {
        margin: 0 !important;
    }
    
    .header .nav-item-box a,
    .nav-item-box a {
        padding: 5px !important;
        font-size: 16px !important;
    }
}

@media only screen and (max-width: 575px) {
    .header.header-responsive,
    .header {
        padding: 5px 6px !important;
        gap: 4px !important;
    }
    
    .header .header-left,
    .header-left {
        max-width: 140px !important;
        margin-left: 5px !important;
        padding: 0 !important;
    }
    
    .header .header-left .logo img,
    .header-left .logo img {
        max-height: 38px !important;
    }
    
    .header #mobile_btn,
    #mobile_btn {
        margin: 0 !important;
        width: 28px !important;
        padding: 3px !important;
    }
    
    .header .user-menu,
    .user-menu {
        gap: 4px !important;
        padding-left: 5px !important;
    }
    
    .header .user-menu .me-2,
    .header .user-menu .me-3,
    .user-menu .me-2,
    .user-menu .me-3,
    .header .pos-btn-nav,
    .pos-btn-nav {
        margin: 0 !important;
    }
    
    .header .mobile-user-menu,
    .mobile-user-menu {
        margin-right: -2px !important;
    }
    
    /* Hide settings icon on very small screens */
    .header .nav-item-box,
    .nav-item-box {
        display: none !important;
    }
}

@media only screen and (max-width: 480px) {
    /* Hide POS button on small screens */
    .header .pos-btn-nav,
    .pos-btn-nav {
        display: none !important;
    }
}

@media only screen and (max-width: 400px) {
    /* Extra small screens */
    .header.header-responsive,
    .header {
        padding: 4px 5px !important;
        gap: 3px !important;
    }
    
    .header .header-left,
    .header-left {
        max-width: 110px !important;
        min-width: 90px !important;
        margin-left: 4px !important;
        padding: 0 !important;
    }
    
    .header .header-left .logo img,
    .header-left .logo img {
        max-height: 34px !important;
    }
    
    .header #mobile_btn,
    #mobile_btn {
        margin: 0 !important;
        width: 24px !important;
        padding: 2px !important;
    }
    
    .header #mobile_btn .bar-icon span,
    #mobile_btn .bar-icon span {
        width: 18px !important;
        height: 2px !important;
    }
    
    .header .user-menu,
    .user-menu {
        gap: 3px !important;
        padding-left: 4px !important;
    }
    
    .header .user-menu .me-2,
    .header .user-menu .me-3,
    .user-menu .me-2,
    .user-menu .me-3 {
        margin: 0 !important;
    }
    
    .header .mobile-user-menu,
    .mobile-user-menu {
        margin-right: -1px !important;
    }
}
</style>

<!-- Header -->
<div class="header header-responsive" style="display: flex; align-items: center;">

    <!-- Logo -->
    <div class="header-left active">
        <a href="{{ url('index') }}" class="logo logo-normal">
            <img src="{{ URL::asset('/build/img/logo.png') }}" alt="">
        </a>
        <a href="{{ url('index') }}" class="logo logo-white">
            <img src="{{ URL::asset('/build/img/logo-white.png') }}" alt="">
        </a>
        <a href="{{ url('index') }}" class="logo-small">
            <img src="{{ URL::asset('/build/img/logo-small.png') }}" alt="">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <!-- /Logo -->

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <!-- Header Menu -->
    <ul class="nav user-menu user-menu-responsive" style="margin-left: auto; display: flex; align-items: center; justify-content: flex-end; flex: 1;">

        <!-- POS Button -->
        <li class="nav-item pos-btn-nav me-3">
            <a href="{{ url('pos') }}" class="btn btn-primary d-flex align-items-center" style="padding: 8px 20px; white-space: nowrap;" title="POS System">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 pos-icon">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="pos-text" style="color: white; font-weight: 600;">POS SYSTEM</span>
            </a>
        </li>
        <!-- /POS Button -->

        <!-- Select Store (Hidden for Superadmin) -->
        @auth
        @if(!auth()->user()->isSuperAdmin())
        @php
            $userStores = auth()->user()->getAccessibleStores();
            $selectedStore = session('selected_store_id') ? \App\Models\Store::find(session('selected_store_id')) : ($userStores->first() ?? null);
        @endphp
        <li class="nav-item dropdown has-arrow main-drop select-store-dropdown me-2">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link select-store" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="{{ URL::asset('/build/img/store/store-01.png') }}" alt="Store Logo" class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name">{{ $selectedStore ? $selectedStore->name : 'Select Store' }}</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @forelse($userStores as $store)
                @if($selectedStore && $selectedStore->id == $store->id)
                <span class="dropdown-item active">
                    <img src="{{ URL::asset('/build/img/store/store-01.png') }}" alt="Store Logo" class="img-fluid">
                    {{ $store->name }}
                </span>
                @else
                <a href="javascript:void(0);" onclick="confirmStoreChange({{ $store->id }}, '{{ $store->name }}')" class="dropdown-item">
                    <img src="{{ URL::asset('/build/img/store/store-01.png') }}" alt="Store Logo" class="img-fluid">
                    {{ $store->name }}
                </a>
                @endif
                @empty
                <span class="dropdown-item text-muted">No stores available</span>
                @endforelse
            </div>
        </li>
        @endif
        @endauth
        <!-- /Select Store -->

        <li class="nav-item nav-item-box me-2">
            <a href="{{ url('general-settings') }}"><i data-feather="settings"></i></a>
        </li>
        
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="{{ URL::asset('/build/img/profiles/avator1.jpg') }}" alt="" class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</span>
                        <span class="user-role">{{ auth()->check() && auth()->user()->role ? auth()->user()->role->display_name : '' }}</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img"><img src="{{ URL::asset('/build/img/profiles/avator1.jpg') }}" alt="">
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>{{ auth()->check() ? auth()->user()->name : 'Guest' }}</h6>
                            <h5>{{ auth()->check() && auth()->user()->role ? auth()->user()->role->display_name : '' }}</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="{{ url('profile') }}"> <i class="me-2" data-feather="user"></i> My Profile</a>
                    @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isBusinessOwner()))
                    <a class="dropdown-item" href="{{ url('general-settings') }}"><i class="me-2" data-feather="settings"></i>Settings</a>
                    @endif
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="{{ route('logout') }}"><img src="{{ URL::asset('/build/img/icons/log-out.svg') }}" class="me-2" alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            @auth
            @if(!auth()->user()->isSuperAdmin())
            @php
                $userStores = auth()->user()->getAccessibleStores();
                $selectedStore = session('selected_store_id') ? \App\Models\Store::find(session('selected_store_id')) : ($userStores->first() ?? null);
            @endphp
            @if($userStores->isNotEmpty())
            <div class="dropdown-header"><strong>Current Store</strong></div>
            <div class="dropdown-item active">
                <img src="{{ URL::asset('/build/img/store/store-01.png') }}" alt="Store Logo" style="width: 20px; height: 20px; margin-right: 8px;">
                {{ $selectedStore ? $selectedStore->name : 'Select Store' }}
            </div>
            @if($userStores->count() > 1)
            <div class="dropdown-divider"></div>
            <div class="dropdown-header"><strong>Switch Store</strong></div>
            @foreach($userStores as $store)
                @if(!$selectedStore || $selectedStore->id != $store->id)
                <a href="javascript:void(0);" onclick="confirmStoreChange({{ $store->id }}, '{{ $store->name }}')" class="dropdown-item">
                    <img src="{{ URL::asset('/build/img/store/store-01.png') }}" alt="Store Logo" style="width: 20px; height: 20px; margin-right: 8px;">
                    {{ $store->name }}
                </a>
                @endif
            @endforeach
            @endif
            <div class="dropdown-divider"></div>
            @endif
            @endif
            @endauth
            <a class="dropdown-item" href="{{ url('profile') }}">My Profile</a>
            <a class="dropdown-item" href="{{ url('general-settings') }}">Settings</a>
            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
        </div>
    </div>
    <!-- /Mobile Menu -->
</div>
<!-- /Header -->
