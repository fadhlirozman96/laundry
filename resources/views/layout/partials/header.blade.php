<!-- Header -->
<div class="header">

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
    <ul class="nav user-menu">

        <!-- POS Button -->
        <li class="nav-item" style="flex: 1; max-width: 400px;">
            
            <a href="{{ url('pos') }}" >
                <button type="button" class="btn btn-primary"><i data-feather="shopping-cart" class="feather-18 me-2"></i>
                POS SYSTEM</button>
                
            </a>
        </li>
        <!-- /POS Button -->


        <!-- Select Store -->
        @auth
        @php
            $userStores = auth()->user()->getAccessibleStores();
            $selectedStore = session('selected_store_id') ? \App\Models\Store::find(session('selected_store_id')) : ($userStores->first() ?? null);
        @endphp
        <li class="nav-item dropdown has-arrow main-drop select-store-dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link select-store" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="{{ URL::asset('/build/img/store/store-01.png') }}" alt="Store Logo"
                            class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name">{{ $selectedStore ? $selectedStore->name : 'Select Store' }}</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @forelse($userStores as $store)
                <a href="{{ route('select-store', $store->id) }}" class="dropdown-item {{ $selectedStore && $selectedStore->id == $store->id ? 'active' : '' }}">
                    <img src="{{ URL::asset('/build/img/store/store-01.png') }}" alt="Store Logo" class="img-fluid">
                    {{ $store->name }}
                </a>
                @empty
                <span class="dropdown-item text-muted">No stores available</span>
                @endforelse
            </div>
        </li>
        @endauth
        <!-- /Select Store -->

       

        <li class="nav-item nav-item-box">
            <a href="{{ url('general-settings') }}"><i data-feather="settings"></i></a>
        </li>
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="{{ URL::asset('/build/img/profiles/avator1.jpg') }}" alt=""
                            class="img-fluid">
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
                        <span class="user-img"><img src="{{ URL::asset('/build/img/profiles/avator1.jpg') }}"
                                alt="">
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>{{ auth()->check() ? auth()->user()->name : 'Guest' }}</h6>
                            <h5>{{ auth()->check() && auth()->user()->role ? auth()->user()->role->display_name : '' }}</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="{{ url('profile') }}"> <i class="me-2"
                            data-feather="user"></i> My Profile</a>
                    @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isBusinessOwner()))
                    <a class="dropdown-item" href="{{ url('general-settings') }}"><i class="me-2"
                            data-feather="settings"></i>Settings</a>
                    @endif
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="{{ route('logout') }}"><img
                            src="{{ URL::asset('/build/img/icons/log-out.svg') }}" class="me-2"
                            alt="img">Logout</a>
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
            <a class="dropdown-item" href="{{ url('profile') }}">My Profile</a>
            <a class="dropdown-item" href="{{ url('general-settings') }}">Settings</a>
            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
        </div>
    </div>
    <!-- /Mobile Menu -->
</div>
<!-- /Header -->
