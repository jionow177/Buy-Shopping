@php
    $settings = Utility::settings();
    // store RTL
    $s_logo = \App\Models\Utility::get_file('uploads/store_logo/');
    $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
    $data = DB::table('settings');
    $logo1 = \App\Models\Utility::get_file('uploads/logo/');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $meta_image = \App\Models\Utility::get_file('uploads/meta_image');
    $profile = \App\Models\Utility::get_file('uploads/customerprofile/');
    $meta_image = \App\Models\Utility::get_file('uploads/meta_image');
    $meta_img = $store->meta_image;
    if (!isset($meta_img)) {
        $meta_img = 'meta_image.png';
    }
@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ env('SITE_RTL') == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="whatsstore-saas - Workdo.">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>{{ __('Home') }} - {{ $store->tagline ? $store->tagline : env('APP_NAME', ucfirst($store->name)) }}
    </title>
    <meta name="description" content="{{ ucfirst($store->name) }} - {{ ucfirst($store->tagline) }}">
    <meta name="keywords" content="{{ $store->meta_keyword }}">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <!-- Primary Meta Tags -->
    <meta name="title" content="{{ $store->meta_keyword }}">
    <meta name="description" content="{{ ucfirst($store->meta_description) }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ $store->meta_keyword }}">
    <meta property="og:description" content="{{ ucfirst($store->meta_description) }}">
    <meta property="og:image" content="{{ $meta_image . '/' . $meta_img }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ $store->meta_keyword }}">
    <meta property="twitter:description" content="{{ ucfirst($store->meta_description) }}">
    <meta property="twitter:image" content="{{ $meta_image . '/' . $meta_img }}">

    <link rel="icon"
        href="{{ $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?timestamp=' . time() }}"
        type="image" sizes="16x16">

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('custom/css/swiper-bundle.min.css') }}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('custom/libs/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}">
    @if ((isset($settings['SITE_RTL']) && $settings['SITE_RTL'] == 'on') || env('SITE_RTL') == 'on')
        @if (!empty($store->store_theme))
            <link rel="stylesheet" href="{{ asset('custom/css/rtl/rtl-' . $store->store_theme . '.css') }}"
                id="stylesheet">
            <link rel="stylesheet" href="{{ asset('custom/css/rtl/rtl-responsive-' . $store->store_theme . '.css') }}">
        @endif
    @else
        @if (!empty($store->store_theme))
            <link rel="stylesheet" href="{{ asset('custom/css/' . $store->store_theme . '.css') }}" id="stylesheet">
            <link rel="stylesheet" href="{{ asset('custom/css/responsive-' . $store->store_theme . '.css') }}">
        @endif
    @endif

    <link rel="stylesheet" href="{{ asset('custom/libs/animate.css/animate.min.css') }}">
    <link rel='stylesheet' href='{{ asset('assets/css/cookieconsent.css') }}' media="screen" />

    {{-- pwa customer app --}}
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="mobile-wep-app-capable" content="yes">
    <meta name="apple-mobile-wep-app-capable" content="yes">
    <meta name="msapplication-starturl" content="/">
    <link rel="stylesheet" href="{{ asset('custom/css/theme-custom.css') }}" id="stylesheet')}}">

    <link rel="apple-touch-icon"
        href="{{ $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}" />
    @if ($store->enable_pwa_store == 'on')
        <link rel="manifest"
            href="{{ asset('storage/uploads/customer_app/store_' . $store_settings->id . '/manifest.json') }}" />
    @endif
    @if (!empty($store->pwa_store($store->slug)->theme_color))
        <meta name="theme-color" content="{{ $store->pwa_store($store->slug)->theme_color }}" />
    @endif
    @if (!empty($store->pwa_store($store->slug)->background_color))
        <meta name="apple-mobile-web-app-status-bar"
            content="{{ $store->pwa_store($store->slug)->background_color }}" />
    @endif
    @foreach ($pixelScript as $script)
        <?= $script ?>
    @endforeach

    <style>
        .backhome {
            padding: 30px, 20px, 10, 0px !important;
            margin: 7px !important;
        }
    </style>
    {{-- floating whatsapp --}}
    <link rel="stylesheet" href="{{ asset('assets/css/floating-wpp.min.css') }}">



</head>

<body>
    @php
        if (!empty(session()->get('lang'))) {
            $currantLang = session()->get('lang');
        } else {
            $currantLang = $store->lang;
        }

        $languages = \App\Models\Utility::languages();
    @endphp
    <input type="hidden" id="return_url">
    <input type="hidden" id="return_order_id">
    <!--header start here-->
    <header class="site-header header-style-one">
        <div class="main-navigationbar">
            <div class="logo-col">
                <a href="">
                    <img src="{{ $s_logo . (isset($store_settings['logo']) && !empty($store_settings['logo']) ? $store_settings['logo'] : 'logo.png') . '?timestamp=' . time() }}"
                        class="nav_tab_img">
                </a>
            </div>
            <div class="navigationbar-row">
                <div class="menu-items-col">
                    <ul class="main-nav">
                        <li class="menu-lnk has-item">
                            <a href="#">
                                {{-- <span class="avtar"> --}}
                                {{-- <img src="{{ $avatar }}"
                                    class="avtar" /> --}}
                                {{-- </span> --}}
                                <span class="h6">
                                    {{ $store->name }}
                                    <small>{{ $store->storeAddress($store->address) }}
                                        {{ $store->storeAddress($store->city) }}
                                        {{ $store->storeAddress($store->state) }}
                                        {{ $store->storeAddress($store->country, 'country') }}</small>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="header-right">
                <div class="header-search search-bar">
                    <button class="search-drp-btn" id="btn-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-search">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        {{ __('Search') }}
                    </button>
                    <form action="#">
                        <div class="input-wrapper">
                            <input type="search" id="search" placeholder="Search...">
                        </div>
                        <button type="submit" class="submit-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20"
                                viewBox="0 0 21 20" fill="none">
                                <path
                                    d="M18.3481 16.8819L15.2273 13.7611C16.2706 12.5236 16.9019 10.9287 16.9019 9.1875C16.9019 5.26719 13.7128 2.07812 9.79248 2.07812C5.87217 2.07812 2.68311 5.26719 2.68311 9.1875C2.68311 13.1078 5.87217 16.2969 9.79248 16.2969C11.5337 16.2969 13.1286 15.6656 14.3661 14.6223L17.4868 17.7431C17.6055 17.8617 17.7615 17.9219 17.9175 17.9219C18.0735 17.9219 18.2295 17.8626 18.3481 17.7431C18.5862 17.5059 18.5862 17.1199 18.3481 16.8819ZM3.90186 9.1875C3.90186 5.93913 6.54411 3.29688 9.79248 3.29688C13.0409 3.29688 15.6831 5.93913 15.6831 9.1875C15.6831 12.4359 13.0409 15.0781 9.79248 15.0781C6.54411 15.0781 3.90186 12.4359 3.90186 9.1875Z"
                                    fill="#1D1D1D" />
                            </svg>
                        </button>
                    </form>
                </div>
                @if (Utility::CustomerAuthCheck($store->slug) == true)
                    <div class="user-drp">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 20 20" fill="none">
                                <path
                                    d="M19.5615 9.52617C19.5615 4.28747 15.3002 0.0261688 10.0615 0.0261688C4.82283 0.0261688 0.561523 4.28747 0.561523 9.52617C0.561523 12.5927 2.02761 15.3163 4.28994 17.0546C4.29347 17.0582 4.29881 17.0599 4.30234 17.0635C5.90276 18.2892 7.8955 19.0262 10.0624 19.0262C12.2293 19.0262 14.2221 18.2892 15.8225 17.0635C15.8261 17.0599 15.8313 17.0582 15.8348 17.0546C18.0954 15.3163 19.5615 12.5927 19.5615 9.52617ZM1.8871 9.52617C1.8871 5.01831 5.55366 1.35175 10.0615 1.35175C14.5694 1.35175 18.2359 5.01831 18.2359 9.52617C18.2359 11.7558 17.3363 13.7787 15.8835 15.2545C15.4867 13.5745 14.2167 12.0182 11.5727 12.0182H8.5504C5.9063 12.0182 4.63547 13.5745 4.23956 15.2545C2.78672 13.7787 1.8871 11.7558 1.8871 9.52617ZM5.42997 16.2548C5.48123 15.3481 5.8957 13.3438 8.5504 13.3438H11.5727C14.2273 13.3438 14.6418 15.3481 14.6931 16.2548C13.3754 17.1651 11.7804 17.7006 10.0615 17.7006C8.34269 17.7006 6.7476 17.1642 5.42997 16.2548ZM10.0686 11.0727C11.8953 11.0727 13.3826 9.58626 13.3826 7.75873C13.3826 5.93119 11.8953 4.44477 10.0686 4.44477C8.24199 4.44477 6.75469 5.93119 6.75469 7.75873C6.75469 9.58626 8.24111 11.0727 10.0686 11.0727ZM10.0686 5.77035C11.1645 5.77035 12.057 6.66203 12.057 7.75873C12.057 8.85542 11.1645 9.7471 10.0686 9.7471C8.97283 9.7471 8.08027 8.85542 8.08027 7.75873C8.08027 6.66203 8.97283 5.77035 10.0686 5.77035Z"
                                    fill="white" />
                            </svg>
                            {{ ucFirst(Auth::guard('customers')->user()->name) }}
                        </a>
                        <div class="menu-dropdown">
                            <ul>
                                <li><a href="{{ route('store.slug', $store->slug) }}">{{ __('My Dashboard') }}</a>
                                </li>
                                <li>
                                    <a href="#" data-size="lg"
                                        data-url="{{ route('customer.profile', [$store->slug, \Illuminate\Support\Facades\Crypt::encrypt(Auth::guard('customers')->user()->id)]) }}"
                                        data-ajax-popup="true" data-title="{{ __('Edit Profile') }}"
                                        data-toggle="modal">{{ __('My Profile') }}</a>
                                </li>
                                <li><a href="#" id="myproducts"
                                        data-val="myproducts">{{ __('My Orders') }}</a>
                                </li>
                                <li>
                                    {{-- <a href="#">{{('Logout')}}</a></li> --}}
                                    <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('customer-frm-logout').submit();">{{ __('Logout') }}
                                    </a>
                                    <form id="customer-frm-logout"
                                        action="{{ route('customer.logout', $store->slug) }}" method="POST"
                                        class="d-none">
                                        {{ csrf_field() }}
                                    </form>
                            </ul>
                        </div>
                    </div>
                @else
                    <style>
                        .header-right .user-drp>a::before {
                            display: none !important;
                        }
                    </style>
                    <div class="user-drp">
                        <a href="#" data-url="{{ route('customer.login', $store->slug) }}"
                            data-ajax-popup="true" data-title="{{ __('Login') }}" data-toggle="modal"
                            id="login-BTN" data-size="md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 20 20" fill="none">
                                <path
                                    d="M19.5615 9.52617C19.5615 4.28747 15.3002 0.0261688 10.0615 0.0261688C4.82283 0.0261688 0.561523 4.28747 0.561523 9.52617C0.561523 12.5927 2.02761 15.3163 4.28994 17.0546C4.29347 17.0582 4.29881 17.0599 4.30234 17.0635C5.90276 18.2892 7.8955 19.0262 10.0624 19.0262C12.2293 19.0262 14.2221 18.2892 15.8225 17.0635C15.8261 17.0599 15.8313 17.0582 15.8348 17.0546C18.0954 15.3163 19.5615 12.5927 19.5615 9.52617ZM1.8871 9.52617C1.8871 5.01831 5.55366 1.35175 10.0615 1.35175C14.5694 1.35175 18.2359 5.01831 18.2359 9.52617C18.2359 11.7558 17.3363 13.7787 15.8835 15.2545C15.4867 13.5745 14.2167 12.0182 11.5727 12.0182H8.5504C5.9063 12.0182 4.63547 13.5745 4.23956 15.2545C2.78672 13.7787 1.8871 11.7558 1.8871 9.52617ZM5.42997 16.2548C5.48123 15.3481 5.8957 13.3438 8.5504 13.3438H11.5727C14.2273 13.3438 14.6418 15.3481 14.6931 16.2548C13.3754 17.1651 11.7804 17.7006 10.0615 17.7006C8.34269 17.7006 6.7476 17.1642 5.42997 16.2548ZM10.0686 11.0727C11.8953 11.0727 13.3826 9.58626 13.3826 7.75873C13.3826 5.93119 11.8953 4.44477 10.0686 4.44477C8.24199 4.44477 6.75469 5.93119 6.75469 7.75873C6.75469 9.58626 8.24111 11.0727 10.0686 11.0727ZM10.0686 5.77035C11.1645 5.77035 12.057 6.66203 12.057 7.75873C12.057 8.85542 11.1645 9.7471 10.0686 9.7471C8.97283 9.7471 8.08027 8.85542 8.08027 7.75873C8.08027 6.66203 8.97283 5.77035 10.0686 5.77035Z"
                                    fill="white" />
                            </svg>
                            {{ __('Log in') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </header>
    <!--header end here-->
    <!-- Wrapper start -->
    <main class="wrapper" style="margin-top: 73.3906px;">
        <div class="container-fluid">
            <div class="single-page-wrapper">
                <div class="tabs-wrapper">
                    <div class="side-menu-wrapper">
                        <div class="menu-close-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18"
                                viewBox="0 0 20 18">
                                <path fill="#24272a"
                                    d="M19.95 16.75l-.05-.4-1.2-1-5.2-4.2c-.1-.05-.3-.2-.6-.5l-.7-.55c-.15-.1-.5-.45-1-1.1l-.1-.1c.2-.15.4-.35.6-.55l1.95-1.85 1.1-1c1-1 1.7-1.65 2.1-1.9l.5-.35c.4-.25.65-.45.75-.45.2-.15.45-.35.65-.6s.3-.5.3-.7l-.3-.65c-.55.2-1.2.65-2.05 1.35-.85.75-1.65 1.55-2.5 2.5-.8.9-1.6 1.65-2.4 2.3-.8.65-1.4.95-1.9 1-.15 0-1.5-1.05-4.1-3.2C3.1 2.6 1.45 1.2.7.55L.45.1c-.1.05-.2.15-.3.3C.05.55 0 .7 0 .85l.05.35.05.4 1.2 1 5.2 4.15c.1.05.3.2.6.5l.7.6c.15.1.5.45 1 1.1l.1.1c-.2.15-.4.35-.6.55l-1.95 1.85-1.1 1c-1 1-1.7 1.65-2.1 1.9l-.5.35c-.4.25-.65.45-.75.45-.25.15-.45.35-.65.6-.15.3-.25.55-.25.75l.3.65c.55-.2 1.2-.65 2.05-1.35.85-.75 1.65-1.55 2.5-2.5.8-.9 1.6-1.65 2.4-2.3.8-.65 1.4-.95 1.9-1 .15 0 1.5 1.05 4.1 3.2 2.6 2.15 4.3 3.55 5.05 4.2l.2.45c.1-.05.2-.15.3-.3.1-.15.15-.3.15-.45z">
                                </path>
                            </svg>
                        </div>
                        <div class="menu-lbl">{{ __('CATEGORIES') }}</div>
                        <ul class="tabs pro_category">
                            @php
                                $key = 0;
                            @endphp
                            @foreach ($products as $item => $product)
                                @php
                                    $total_product = count($product);
                                @endphp
                                @if($total_product != 0)
                                    <li data-href="{{ $loop->iteration }}{!! str_replace(' ', '_', $item) !!}"
                                        class="tab-link custom-list-group-item productTab {{ $key == 0 ? 'active' : '' }}"
                                        data-tab="tab-{{ $key }}">
                                        <a>
                                            <span class="app-mtext">{{ __($item) }}</span>
                                            <span class="count-badge">
                                                {{ __($total_product) }}
                                            </span>
                                            <span class="arrow"><svg xmlns="http://www.w3.org/2000/svg" width="5"
                                                    height="7" viewBox="0 0 5 7" fill="none">
                                                    <path
                                                        d="M1.50012 7C1.35867 7 1.2172 6.94624 1.10965 6.83795C0.89379 6.62209 0.89379 6.27213 1.10965 6.05627L3.666 3.49992L1.10965 0.943564C0.89379 0.727709 0.89379 0.377747 1.10965 0.161892C1.3255 -0.0539638 1.67546 -0.0539638 1.89132 0.161892L4.83815 3.10872C5.054 3.32458 5.054 3.67454 4.83815 3.8904L1.89132 6.83723C1.78302 6.94626 1.64157 7 1.50012 7Z"
                                                        fill="#1D1D1D" />
                                                </svg>
                                            </span>
                                        </a>
                                    </li>
                                @endif
                                @php
                                    $key++;
                                @endphp
                            @endforeach
                        </ul>
                    </div>
                    <div class="single-page-main">
                        <div class="tabs-container">
                            <div class="tab-header">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#">{{ __('DASHBOARD') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="#" class="text-uppercase">{{ __($store->name) }}</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        {{ __('PRODUCTS') }}
                                    </li>
                                </ul>
                                <div class="d-flex align-items-center">
                                    <button class="menu-toggle-btn">
                                        <div class="hamburger-inner"></div>
                                    </button>
                                    <h1>{{ __('Products') }}</h1>
                                </div>
                                <div class="tab-header-fillter">
                                    <div class="sorting-menu" id="sort_by">
                                        {{ __('Sort by') }}:
                                        <div class="sorting-btn">
                                            <a href="{{ route('store.slug', [$store->slug, 'grid']) }}"
                                                class="grid" data-val="grid" id="grid">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                    viewBox="0 0 10 10" fill="none">
                                                    <path
                                                        d="M3.26391 5.39346H1.69011C0.950953 5.39346 0.509766 4.95228 0.509766 4.21312V1.59013C0.509766 0.850966 0.950953 0.409779 1.69011 0.409779H3.26391C4.00307 0.409779 4.44425 0.850966 4.44425 1.59013V4.21312C4.44425 4.95228 4.00307 5.39346 3.26391 5.39346ZM1.69011 1.19668C1.38112 1.19668 1.29666 1.28114 1.29666 1.59013V4.21312C1.29666 4.52211 1.38112 4.60657 1.69011 4.60657H3.26391C3.5729 4.60657 3.65736 4.52211 3.65736 4.21312V1.59013C3.65736 1.28114 3.5729 1.19668 3.26391 1.19668H1.69011ZM3.26391 9.59025H1.69011C0.950953 9.59025 0.509766 9.14906 0.509766 8.4099V7.88531C0.509766 7.14615 0.950953 6.70496 1.69011 6.70496H3.26391C4.00307 6.70496 4.44425 7.14615 4.44425 7.88531V8.4099C4.44425 9.14906 4.00307 9.59025 3.26391 9.59025ZM1.69011 7.49186C1.38112 7.49186 1.29666 7.57632 1.29666 7.88531V8.4099C1.29666 8.71889 1.38112 8.80335 1.69011 8.80335H3.26391C3.5729 8.80335 3.65736 8.71889 3.65736 8.4099V7.88531C3.65736 7.57632 3.5729 7.49186 3.26391 7.49186H1.69011ZM8.50989 9.59025H6.9361C6.19694 9.59025 5.75575 9.14906 5.75575 8.4099V5.78691C5.75575 5.04775 6.19694 4.60657 6.9361 4.60657H8.50989C9.24905 4.60657 9.69024 5.04775 9.69024 5.78691V8.4099C9.69024 9.14906 9.24905 9.59025 8.50989 9.59025ZM6.9361 5.39346C6.62711 5.39346 6.54265 5.47792 6.54265 5.78691V8.4099C6.54265 8.71889 6.62711 8.80335 6.9361 8.80335H8.50989C8.81888 8.80335 8.90334 8.71889 8.90334 8.4099V5.78691C8.90334 5.47792 8.81888 5.39346 8.50989 5.39346H6.9361ZM8.50989 3.29507H6.9361C6.19694 3.29507 5.75575 2.85388 5.75575 2.11472V1.59013C5.75575 0.850966 6.19694 0.409779 6.9361 0.409779H8.50989C9.24905 0.409779 9.69024 0.850966 9.69024 1.59013V2.11472C9.69024 2.85388 9.24905 3.29507 8.50989 3.29507ZM6.9361 1.19668C6.62711 1.19668 6.54265 1.28114 6.54265 1.59013V2.11472C6.54265 2.42371 6.62711 2.50817 6.9361 2.50817H8.50989C8.81888 2.50817 8.90334 2.42371 8.90334 2.11472V1.59013C8.90334 1.28114 8.81888 1.19668 8.50989 1.19668H6.9361Z"
                                                        fill="white" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('store.slug', [$store->slug, 'list']) }}"
                                                class="list" data-val="list" id="list">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 14 14" fill="none">
                                                    <path
                                                        d="M10.6788 6.34427H3.85906C3.1199 6.34427 2.67871 5.90308 2.67871 5.16392V3.59013C2.67871 2.85097 3.1199 2.40978 3.85906 2.40978H10.6788C11.418 2.40978 11.8592 2.85097 11.8592 3.59013V5.16392C11.8592 5.90308 11.418 6.34427 10.6788 6.34427ZM3.85906 3.19668C3.55007 3.19668 3.46561 3.28114 3.46561 3.59013V5.16392C3.46561 5.47291 3.55007 5.55737 3.85906 5.55737H10.6788C10.9878 5.55737 11.0723 5.47291 11.0723 5.16392V3.59013C11.0723 3.28114 10.9878 3.19668 10.6788 3.19668H3.85906ZM10.6788 11.5903H3.85906C3.1199 11.5903 2.67871 11.1491 2.67871 10.4099V8.83611C2.67871 8.09695 3.1199 7.65576 3.85906 7.65576H10.6788C11.418 7.65576 11.8592 8.09695 11.8592 8.83611V10.4099C11.8592 11.1491 11.418 11.5903 10.6788 11.5903ZM3.85906 8.44266C3.55007 8.44266 3.46561 8.52712 3.46561 8.83611V10.4099C3.46561 10.7189 3.55007 10.8034 3.85906 10.8034H10.6788C10.9878 10.8034 11.0723 10.7189 11.0723 10.4099V8.83611C11.0723 8.52712 10.9878 8.44266 10.6788 8.44266H3.85906Z"
                                                        fill="#999999" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="price-fillter">
                                        {{ __('Filter by :') }}
                                        <div class="nice-select" tabindex="0">
                                            <span class="current">{{ __('Select Price') }} </span>
                                            <ul class="list" id="product_sort">
                                                <li data-val="hightolow" class="option hightolow">
                                                    {{ __('High To Low') }}</li>
                                                <li data-val="lowtohigh" class="option lowtohigh">
                                                    {{ __('Low To High') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="tab-content-fillter col-6">
                                        <ul>
                                            <li class="active ">
                                                <a href="#">{{ __('Products') }}</a>
                                                {{-- <a href="#">Featured Products</a> --}}
                                            </li>
                                            {{-- <li>
                                        <a href="#">New Products</a>
                                    </li> --}}
                                        </ul>
                                    </div>
                                    <div class="tab-content-fillter col-6 text-right">
                                        <a href=""
                                            class="btn btn-primary backhome">{{ __('Back to home') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div id="product_view"></div>
                        </div>

                        <div class="checkout-wrapper" id="card-summary">
                            <div class="cart-wrapper">
                                <div class="coupon-box">
                                    <div class="subtitle">
                                        <h2 class="h5">{{ __('Coupon Code') }}</h2>
                                    </div>
                                    {{-- <div class="subtitle"><h5>{{ __('Coupon Code ') }}</h5></div> --}}
                                    <div>
                                        <div class="input-wrapper">
                                            <input type="text" id="stripe_coupon" name="coupon"
                                                class="form-control coupon hidd_val"
                                                placeholder="{{ __('Enter Coupon Code') }}" value="">
                                            <input type="hidden" name="coupon" class="form-control hidden_coupon"
                                                value="">
                                        </div>
                                        <button class="apply-btn btn apply-coupon"> <svg
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="15"
                                                viewBox="0 0 16 15" fill="none">
                                                <path opacity="0.4"
                                                    d="M8.46289 13.75C11.9147 13.75 14.7129 10.9518 14.7129 7.5C14.7129 4.04822 11.9147 1.25 8.46289 1.25C5.01111 1.25 2.21289 4.04822 2.21289 7.5C2.21289 10.9518 5.01111 13.75 8.46289 13.75Z"
                                                    fill="white"></path>
                                                <path
                                                    d="M7.73336 9.42689C7.61336 9.42689 7.49336 9.38125 7.40211 9.28937L5.94398 7.83128C5.76086 7.64816 5.76086 7.35126 5.94398 7.16814C6.12711 6.98501 6.42399 6.98501 6.60711 7.16814L7.73399 8.295L10.319 5.71001C10.5021 5.52688 10.799 5.52688 10.9821 5.71001C11.1652 5.89313 11.1652 6.19003 10.9821 6.37315L8.06524 9.29002C7.97337 9.38127 7.85336 9.42689 7.73336 9.42689Z"
                                                    fill="white"></path>
                                            </svg> {{ __('Accept') }}</button>
                                    </div>
                                </div>
                                <div class="cart-body">
                                    <div class="title">
                                        <h2 class="h5">{{ __('Cart') }}</h2>
                                    </div>
                                    @if (!empty($pro_cart) && count($pro_cart['products']) > 0)
                                        @php
                                            $sub_tax = 0;
                                            $total = 0;
                                            $sub_total = 0;
                                        @endphp
                                        @foreach ($pro_cart['products'] as $key => $product)
                                            @if ($product['variant_id'] != 0)
                                                <div class="cart-item"
                                                    id="product-variant-id-{{ $product['variant_id'] }}">
                                                    <div class="cart-item-img">
                                                        <a href="#">
                                                            <img src="{{ asset($product['image']) }}">
                                                        </a>
                                                    </div>
                                                    <div class="cart-item-content"
                                                        id="product-variant-id-{{ $product['variant_id'] }}">
                                                        <div class="item-content-top">
                                                            <h6><a
                                                                    href="#">{{ $product['product_name'] . ' - ' . $product['variant_name'] }}</a>
                                                            </h6>
                                                        </div>
                                                        @php
                                                            $total_tax = 0;
                                                        @endphp
                                                        <p>{{ __('Price per product:') }}
                                                            <b><ins>{{ \App\Models\Utility::priceFormat($product['variant_price']) }}</ins></b>
                                                        </p>
                                                        @if ($product['tax'] > 0)
                                                            @foreach ($product['tax'] as $k => $tax)
                                                                @php
                                                                    $sub_tax = ($product['variant_price'] * $product['quantity'] * $tax['tax']) / 100;
                                                                    $total_tax += $sub_tax;
                                                                @endphp
                                                                <small
                                                                    class="title_name ml-0  variant_tax_{{ $k }}">
                                                                    {{ $tax['tax_name'] . ' ' . $tax['tax'] . '%' . ' (' . $sub_tax . ')' }}
                                                                </small>
                                                            @endforeach
                                                            @php

                                                                $totalprice = $product['variant_price'] * $product['quantity'] + $total_tax;
                                                                $subprice = $product['variant_price'] * $product['quantity'];
                                                                $total += $totalprice;
                                                                $sub_total += $subprice;
                                                            @endphp
                                                        @endif
                                                    </div>

                                                    <div class="cart-item-right">
                                                        {{-- <div class="subtotal"
                                                            id="product-variant-id-{{ $product['variant_id'] }}"> --}}
                                                        <div>
                                                            <ins class="subtotal"
                                                                id="product-variant-id-{{ $product['variant_id'] }}">{{ \App\Models\Utility::priceFormat($product['variant_price'] * $product['quantity']) }}<span
                                                                    class="currency-type"></span></ins>
                                                        </div>
                                                        <div class="cart-item-cntr">
                                                            <div class="qty-spinner" data-id="{{ $key }}">
                                                                <button type="button"
                                                                    class="quantity-decrement product_qty"
                                                                    data-id="{{ $product['id'] }}"
                                                                    value="{{ $product['quantity'] }}"
                                                                    data-option="decrease" min="0">
                                                                    <svg width="12" height="2"
                                                                        viewBox="0 0 12 2" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M0 0.251343V1.74871H12V0.251343H0Z"
                                                                            fill="#61AFB3">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                                <input type="text"
                                                                    class="quantity pro_variant_id product_qty_input"
                                                                    add_to_cart_variant="pro_variant_id"
                                                                    data-id="{{ $product['variant_id'] }}"
                                                                    data-cke-saved-name="quantity" name="quantity"
                                                                    id="product_qty" id="product_qty_input"
                                                                    value="{{ $product['quantity'] }}"
                                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">

                                                                <button type="button"
                                                                    class="quantity-increment product_qty"
                                                                    data-id="{{ $product['id'] }}"
                                                                    value="{{ $product['quantity'] }}"
                                                                    data-option="increase">
                                                                    <svg width="12" height="12"
                                                                        viewBox="0 0 12 12" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M6.74868 5.25132V0H5.25132V5.25132H0V6.74868H5.25132V12H6.74868V6.74868H12V5.25132H6.74868Z"
                                                                            fill="#61AFB3"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>

                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['delete.cart_item', $store->slug, $product['id'], $product['variant_id']],
                                                            ]) !!}
                                                            <a href="#" class="item-remove show_confirm"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Delete') }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="15" viewBox="0 0 16 15"
                                                                    fill="none">
                                                                    <path opacity="0.4"
                                                                        d="M7.99985 13.5005C11.3882 13.5005 14.1349 10.7537 14.1349 7.36539C14.1349 3.97706 11.3882 1.23029 7.99985 1.23029C4.61152 1.23029 1.86475 3.97706 1.86475 7.36539C1.86475 10.7537 4.61152 13.5005 7.99985 13.5005Z"
                                                                        fill="white" />
                                                                    <path
                                                                        d="M8.65079 7.3654L10.1662 5.85005C10.3459 5.67029 10.3459 5.37885 10.1662 5.19909C9.98641 5.01933 9.69499 5.01933 9.51523 5.19909L7.99986 6.71448L6.48449 5.19909C6.30473 5.01933 6.01331 5.01933 5.83355 5.19909C5.65379 5.37885 5.65379 5.67029 5.83355 5.85005L7.34892 7.3654L5.83355 8.88075C5.65379 9.06051 5.65379 9.35195 5.83355 9.53171C5.92312 9.62128 6.04091 9.66666 6.15871 9.66666C6.2765 9.66666 6.3943 9.62189 6.48387 9.53171L7.99924 8.01632L9.51461 9.53171C9.60418 9.62128 9.72198 9.66666 9.83977 9.66666C9.95756 9.66666 10.0754 9.62189 10.1649 9.53171C10.3447 9.35195 10.3447 9.06051 10.1649 8.88075L8.65079 7.3654Z"
                                                                        fill="white" />
                                                                </svg>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>

                                                    </div>
                                                </div>
                                            @else
                                                <div class="cart-item" id="product-id-{{ $product['product_id'] }}">
                                                    <div class="cart-item-img">
                                                        <a href="#">
                                                            <img src="{{ asset($product['image']) }}">
                                                        </a>
                                                    </div>
                                                    <div class="cart-item-content"
                                                        id="product-id-{{ $product['product_id'] }}">
                                                        <div class="item-content-top">
                                                            <h6><a href="#">{{ $product['product_name'] }}</a>
                                                            </h6>
                                                            {{-- <p>SKU: UE75AU7192UXXH</p> --}}
                                                        </div>
                                                        @php
                                                            $total_tax = 0;
                                                        @endphp
                                                        <p>{{ __('Price per product:') }}
                                                            <b><ins>{{ \App\Models\Utility::priceFormat($product['price']) }}</ins></b>
                                                        </p>
                                                        @if ($product['tax'] > 0)
                                                            @foreach ($product['tax'] as $k => $tax)
                                                                @php
                                                                    $sub_tax = ($product['price'] * $product['quantity'] * $tax['tax']) / 100;
                                                                    $total_tax += $sub_tax;
                                                                @endphp
                                                                <small
                                                                    class="title_name ml-0 tax_{{ $k }}">
                                                                    {{ $tax['tax_name'] . ' ' . $tax['tax'] . '%' . ' (' . $sub_tax . ')' }}
                                                                </small>
                                                            @endforeach
                                                            @php
                                                                $totalprice = $product['price'] * $product['quantity'] + $total_tax;
                                                                $subprice = $product['price'] * $product['quantity'];
                                                                $total += $totalprice;
                                                                $sub_total += $subprice;
                                                            @endphp
                                                        @endif
                                                    </div>
                                                    <div class="cart-item-right">
                                                        <div>
                                                            <ins class="subtotal"
                                                                id="product-id-{{ $product['product_id'] }}">{{ \App\Models\Utility::priceFormat($product['price'] * $product['quantity']) }}<span
                                                                    class="currency-type"></span></ins>
                                                        </div>
                                                        <div class="cart-item-cntr">
                                                            <div class="qty-spinner" data-id="{{ $key }}">
                                                                <button type="button"
                                                                    class="quantity-decrement product_qty"
                                                                    data-id="{{ $product['id'] }}"
                                                                    value="{{ $product['quantity'] }}"
                                                                    data-option="decrease" min="0">
                                                                    <svg width="12" height="2"
                                                                        viewBox="0 0 12 2" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M0 0.251343V1.74871H12V0.251343H0Z"
                                                                            fill="#61AFB3">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                                <input type="text"
                                                                    class="quantity pro_variant_id product_qty_input"
                                                                    add_to_cart_variant="pro_variant_id"
                                                                    data-id="{{ $product['variant_id'] }}"
                                                                    data-cke-saved-name="quantity" name="quantity"
                                                                    id="product_qty_input"
                                                                    value="{{ $product['quantity'] }}"
                                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">

                                                                <button type="button"
                                                                    class="quantity-increment product_qty"
                                                                    data-id="{{ $product['id'] }}"
                                                                    value="{{ $product['quantity'] }}"
                                                                    data-option="increase">
                                                                    <svg width="12" height="12"
                                                                        viewBox="0 0 12 12" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M6.74868 5.25132V0H5.25132V5.25132H0V6.74868H5.25132V12H6.74868V6.74868H12V5.25132H6.74868Z"
                                                                            fill="#61AFB3"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>

                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['delete.cart_item', $store->slug, $product['id'], $product['variant_id']],
                                                            ]) !!}
                                                            <a href="#" class="item-remove  show_confirm"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Delete') }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="15" viewBox="0 0 16 15"
                                                                    fill="none">
                                                                    <path opacity="0.4"
                                                                        d="M7.99985 13.5005C11.3882 13.5005 14.1349 10.7537 14.1349 7.36539C14.1349 3.97706 11.3882 1.23029 7.99985 1.23029C4.61152 1.23029 1.86475 3.97706 1.86475 7.36539C1.86475 10.7537 4.61152 13.5005 7.99985 13.5005Z"
                                                                        fill="white" />
                                                                    <path
                                                                        d="M8.65079 7.3654L10.1662 5.85005C10.3459 5.67029 10.3459 5.37885 10.1662 5.19909C9.98641 5.01933 9.69499 5.01933 9.51523 5.19909L7.99986 6.71448L6.48449 5.19909C6.30473 5.01933 6.01331 5.01933 5.83355 5.19909C5.65379 5.37885 5.65379 5.67029 5.83355 5.85005L7.34892 7.3654L5.83355 8.88075C5.65379 9.06051 5.65379 9.35195 5.83355 9.53171C5.92312 9.62128 6.04091 9.66666 6.15871 9.66666C6.2765 9.66666 6.3943 9.62189 6.48387 9.53171L7.99924 8.01632L9.51461 9.53171C9.60418 9.62128 9.72198 9.66666 9.83977 9.66666C9.95756 9.66666 10.0754 9.62189 10.1649 9.53171C10.3447 9.35195 10.3447 9.06051 10.1649 8.88075L8.65079 7.3654Z"
                                                                        fill="white" />
                                                                </svg>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                </div>

                                <div class="cart-total">
                                    <ul>
                                        <li>
                                            <span class="cart-sum-left"> {{ __('Subtotal') }}</span>
                                            <span class="cart-sum-right sub_total_price"
                                                data-value="{{ $total }}">{{ App\Models\Utility::priceFormat($sub_total) }}
                                            </span>
                                        </li>
                                        <li>
                                            <span class="cart-sum-left"> {{ __('Coupon') }}</span>
                                            <span class="cart-sum-right dicount_price">{{ __('0.00') }}</span>
                                        </li>
                                        <li>
                                            @if (!empty($pro_cart) && count($pro_cart['products']) > 0)
                                                <span class="cart-sum-left">{{ __('Shipping') }}</span>
                                                <span
                                                    class="cart-sum-right shipping_price">{{ __('0.00') }}</span>
                                            @endif
                                        </li>
                                        @if (!empty($taxArr))
                                            @foreach ($taxArr['tax'] as $k => $tax)
                                                @if ($product['variant_id'] != 0)
                                                    <li id="product-variant-id-{{ $product['variant_id'] }}">
                                                    @else
                                                    <li id="product-id-{{ $product['product_id'] }}">
                                                @endif
                                                <span class="cart-sum-left"> {{ $tax }}</span>

                                                <span
                                                    class="cart-sum-right total_tax_{{ $k }}">{{ \App\Models\Utility::priceFormat($taxArr['rate'][$k]) }}</span>
                                                </li>
                                            @endforeach
                                        @endif
                                        <li>
                                            <span class="cart-sum-left"><b>{{ __('Total (Incl Tax)') }}</b></span>
                                            <span class="cart-sum-right total-amount" data-original="$0.00">
                                                <input type="hidden" class="product_total"
                                                    value="{{ $total }}">
                                                <input type="hidden" class="total_pay_price"
                                                    value="{{ App\Models\Utility::priceFormat($total) }}">
                                                <b class="final_total_price pro_total_price" id="displaytotal"
                                                    data-original="{{ \App\Models\Utility::priceFormat(!empty($total) ? $total : 0) }}">
                                                    {{ App\Models\Utility::priceFormat($total) }}</b></span>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="cart-total">
                                    <ul>
                                        <li>
                                            <span class="cart-sum-left"> {{ __('Subtotal') }}</span>
                                            <span class="cart-sum-right">0.00</span>
                                        </li>
                                        <li>
                                            <span class="cart-sum-left"> {{ __('Coupon') }}</span>
                                            <span class="cart-sum-right dicount_price">{{ __('0.00') }}</span>
                                        </li>
                                        <li>
                                            @if (!empty($pro_cart) && count($pro_cart['products']) > 0)
                                                <span class="cart-sum-left">{{ __('Shipping') }}</span>
                                                <span
                                                    class="cart-sum-right shipping_price">{{ __('0.00') }}</span>
                                            @endif
                                        </li>
                                        <li>
                                            @if (!empty($taxArr))
                                                @foreach ($taxArr['tax'] as $k => $tax)
                                                    <span class="cart-sum-left"> {{ $tax }}</span>
                                                    <span
                                                        class="cart-sum-right">{{ \App\Models\Utility::priceFormat($taxArr['rate'][$k]) }}</span>
                                                @endforeach
                                            @endif
                                        </li>
                                        <li>
                                            <span class="cart-sum-left"><b>{{ __('Total (Incl Tax)') }}</b></span>
                                            <span class="cart-sum-right total-amount final_total_price pro_total_price"
                                                id="displaytotal"
                                                data-original="{{ \App\Models\Utility::priceFormat(!empty($total) ? $total : 0) }}">
                                                <b>0.00</b>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                            <div class="checkout-fileds">
                                <div class="title">
                                    <h6>{{ __('Delivery Details') }}</h6>
                                </div>
                                <div class="row detail-form">
                                    <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            {{ Form::label('name', __('Name'), ['class' => 'form-control-label']) }}
                                            {{ Form::text('name', old('name'), ['class' => 'active fname', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            {{ Form::label('email', __('Email'), ['class' => 'form-control-label']) }}
                                            {{ Form::email('email', old('email'), ['class' => 'active email', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                                        <div class="form-group">
                                            {{ Form::label('phone', __('Phone'), ['class' => 'form-control-label']) }}
                                            {{ Form::text('phone', old('phone'), ['class' => 'active phone', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    @if (!empty($store->custom_field_title_1))
                                        <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('custom_field_title_1', $store->custom_field_title_1, ['class' => 'form-control-label']) }}
                                                {{ Form::text('custom_field_title_1', '+1 (776) 912-8656', ['class' => 'active custom_field_title_1']) }}
                                            </div>
                                        </div>
                                    @endif
                                    @if (!empty($store->custom_field_title_2))
                                        <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('custom_field_title_2', $store->custom_field_title_2, ['class' => 'form-control-label']) }}
                                                {{ Form::text('custom_field_title_2', 'United Kingdom', ['class' => 'active custom_field_title_2']) }}
                                            </div>
                                        </div>
                                    @endif
                                    @if (!empty($store->custom_field_title_3))
                                        <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('custom_field_title_3', $store->custom_field_title_3, ['class' => 'form-control-label']) }}
                                                {{ Form::text('custom_field_title_3', 'Pariatur Voluptas q', ['class' => 'active custom_field_title_3']) }}
                                            </div>
                                        </div>
                                    @endif
                                    @if (!empty($store->custom_field_title_4))
                                        <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                                            <div class="form-group">
                                                {{ Form::label('custom_field_title_4', $store->custom_field_title_4, ['class' => 'form-control-label']) }}
                                                {{ Form::text('custom_field_title_4', '10001', ['class' => 'active custom_field_title_4']) }}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{ Form::label('billingaddress', __('Address line 1'), ['class' => 'form-control-label']) }}
                                            {{ Form::text('billing_address', old('billing_address'), ['class' => 'active billing_address', 'required' => 'required']) }}
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            {{ Form::label('shipping_address', __('Address line 2'), ['class' => 'form-control-label']) }}
                                            {{ Form::text('shipping_address', old('shipping_address'), ['class' => 'active shipping_address']) }}
                                        </div>
                                    </div>

                                    @if (!empty($pro_cart) && count($pro_cart['products']) > 0)
                                        {{-- @dd($pro_cart['products']) --}}
                                        @if ($store->enable_shipping == 'on')
                                            @if (count($locations) != 1)
                                                @if (count($shippings) != 0)
                                                    <div class="col-12">
                                                        <div class="title">
                                                            <h6>{{ __('Shipping Location') }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __('Location:') }}</label>
                                                            {{ Form::select('location_id', $locations, null, ['class' => 'active acticard-titleve form-control change_location', 'required' => 'required']) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="d-flex align-items-center"
                                                                id="shipping_location_content">

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                    <div class="col-12">
                                        <div class="title">
                                            <h6>{{ __('Order Notes') }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{ Form::textarea('special_instruct', null, ['class' => 'special_instruct form-control', 'rows' => 3]) }}
                                            {{-- <textarea class="form-control" name="message" placeholder="Description" rows="8"></textarea> --}}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        @if (
                                            $store_settings['is_checkout_login_required'] == null ||
                                                ($store_settings['is_checkout_login_required'] == 'off' && !Auth::guard('customers')->user()))
                                            <a class="btn checkoutBtn btn-submit" data-toggle="modal" id="checkoutBtn"
                                                data-target="#checkoutModal" data-title="CheckOut Model">
                                                {{ 'Proceed to checkout' }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    viewBox="0 0 25 25" fill="none">
                                                    <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M19.6285 11.7407L18.8785 19.1207C18.7285 20.6507 18.0886 21.8207 15.8986 21.8207H8.32861C6.08861 21.8207 5.49862 20.6507 5.33862 19.1207L4.57861 11.7507C4.73861 11.8007 4.90862 11.8207 5.08862 11.8207H19.0886C19.2796 11.8207 19.4595 11.7907 19.6285 11.7407Z"
                                                        fill="white" />
                                                    <path
                                                        d="M19.1045 7.82066H17.3815L14.7675 3.43665C14.5555 3.08065 14.0945 2.96465 13.7395 3.17565C13.3835 3.38765 13.2664 3.84865 13.4784 4.20365L15.6344 7.81965H8.5835L10.7644 4.20765C10.9794 3.85365 10.8655 3.39265 10.5105 3.17765C10.1555 2.96265 9.69545 3.07765 9.48145 3.43165L6.8324 7.81965H5.10449C4.00049 7.81965 3.10449 8.71565 3.10449 9.81965C3.10449 10.9236 4.00049 11.8196 5.10449 11.8196H19.1045C20.2085 11.8196 21.1045 10.9236 21.1045 9.81965C21.1045 8.71565 20.2085 7.82066 19.1045 7.82066Z"
                                                        fill="white" />
                                                    <path
                                                        d="M11.4187 18.5466C11.2197 18.5466 11.0287 18.4676 10.8887 18.3266L9.55569 16.9936C9.26269 16.7006 9.26269 16.2256 9.55569 15.9326C9.84869 15.6396 10.3237 15.6406 10.6167 15.9326L11.4197 16.7356L13.5567 14.5996C13.8497 14.3066 14.3247 14.3066 14.6177 14.5996C14.9107 14.8926 14.9107 15.3676 14.6177 15.6606L11.9507 18.3276C11.8087 18.4676 11.6177 18.5466 11.4187 18.5466Z"
                                                        fill="white" />
                                                </svg>
                                            </a>
                                        @else
                                            <button class="btn checkoutBtn authUser btn-submit">
                                                {{ __('Proceed to checkout') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                    viewBox="0 0 25 25" fill="none">
                                                    <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M19.6285 11.7407L18.8785 19.1207C18.7285 20.6507 18.0886 21.8207 15.8986 21.8207H8.32861C6.08861 21.8207 5.49862 20.6507 5.33862 19.1207L4.57861 11.7507C4.73861 11.8007 4.90862 11.8207 5.08862 11.8207H19.0886C19.2796 11.8207 19.4595 11.7907 19.6285 11.7407Z"
                                                        fill="white" />
                                                    <path
                                                        d="M19.1045 7.82066H17.3815L14.7675 3.43665C14.5555 3.08065 14.0945 2.96465 13.7395 3.17565C13.3835 3.38765 13.2664 3.84865 13.4784 4.20365L15.6344 7.81965H8.5835L10.7644 4.20765C10.9794 3.85365 10.8655 3.39265 10.5105 3.17765C10.1555 2.96265 9.69545 3.07765 9.48145 3.43165L6.8324 7.81965H5.10449C4.00049 7.81965 3.10449 8.71565 3.10449 9.81965C3.10449 10.9236 4.00049 11.8196 5.10449 11.8196H19.1045C20.2085 11.8196 21.1045 10.9236 21.1045 9.81965C21.1045 8.71565 20.2085 7.82066 19.1045 7.82066Z"
                                                        fill="white" />
                                                    <path
                                                        d="M11.4187 18.5466C11.2197 18.5466 11.0287 18.4676 10.8887 18.3266L9.55569 16.9936C9.26269 16.7006 9.26269 16.2256 9.55569 15.9326C9.84869 15.6396 10.3237 15.6406 10.6167 15.9326L11.4197 16.7356L13.5567 14.5996C13.8497 14.3066 14.3247 14.3066 14.6177 14.5996C14.9107 14.8926 14.9107 15.3676 14.6177 15.6606L11.9507 18.3276C11.8087 18.4676 11.6177 18.5466 11.4187 18.5466Z"
                                                        fill="white" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="payment-method" id="asGuest">
                                    <div class="title">
                                        <h6>{{ __('Payment Method') }}</h6>
                                    </div>

                                    <div class="row">
                                        @if ($store->enable_whatsapp == 'on')
                                            <div class="col-sm-6 col-12">
                                                <a id="owner-whatsapp" data-toggle="modal"
                                                    data-target="#checkoutModal"
                                                    class="third-party-payment whatsap-btn payment whatsapp">
                                                    <img src="{{ asset('custom/images/whatsapp.png') }}"
                                                        alt="">
                                                    <span>{{ __('Order on') }} <b>{{ __('WhatsApp') }}</b></span>
                                                </a>
                                            </div>
                                        @endif
                                        @if ($store->enable_telegram == 'on')
                                            <div class="col-sm-6 col-12">
                                                <a href="#" class="third-party-payment telegram telegram-btn"
                                                    id="owner-telegram">
                                                    <img src="{{ asset('custom/images/telegram.png') }}"
                                                        alt="">
                                                    <span>{{ __('Order on') }} <b>{{ __('Telegram') }}</b></span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        @if ($store['enable_cod'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <button type="submit" class="payment-btn" id="cash_on_delivery">
                                                    {{ __('Order on COD') }}
                                                </button>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_stripe_enabled']) && $store_payments['is_stripe_enabled'] == 'on')
                                            <div class="col-sm-6 col-12" id="paymentsBtn">
                                                <form role="form"
                                                    action="{{ route('stripe.post', $store->slug) }}" method="post"
                                                    class="require-validation" id="payment-form">
                                                    @csrf
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id" class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <button type="submit" class="payment-btn" id="owner-stripe">
                                                        {{ __('Pay via Stripe') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_paypal_enabled']) && $store_payments['is_paypal_enabled'] == 'on')
                                            <div class="col-sm-6 col-12 pay_online_btn">
                                                <form method="POST"
                                                    action="{{ route('pay.with.paypal', $store->slug) }}"
                                                    id="payment-paypal-form">
                                                    @csrf
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id" class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <button type="submit" class="payment-btn" id="owner-paypal">
                                                        {{ __('Pay via PayPal') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                        @php
                                            $total_price_1 = \App\Models\Utility::priceFormat(!empty($total) ? $total : 0);
                                            $toal_price_1 = str_replace(' ', '', str_replace(',', '', str_replace($store->currency, '', $total_price_1)));
                                        @endphp

                                        @if (isset($store_payments['is_paystack_enabled']) && $store_payments['is_paystack_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <script src="https://checkout.paystack.com/service-worker.js"></script>
                                                <script>
                                                    function payWithPaystack() {
                                                        var paystack_callback = "{{ url('/paystack') }}";
                                                        var order_id = '{{ $order_id = time() }}';
                                                        var slug = '{{ $store->slug }}';
                                                        var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

                                                        // var total_price = $('#Subtotal .total_price').attr('data-value');
                                                        var t_price = $('.final_total_price').html();
                                                        var total_price = t_price.replace("{{ $store->currency }}", "");
                                                        console.log(total_price);
                                                        var coupon_id = $('.hidden_coupon').attr('data_id');
                                                        var dicount_price = $('.dicount_price').html();
                                                        var shipping_price = $('.shipping_price').html();
                                                        var shipping_name = $('.change_location').find(":selected").text();
                                                        var shipping_id = $("input[name='shipping_id']:checked").val();

                                                        var name = $('.detail-form .fname').val();
                                                        var email = $('.detail-form .email').val();
                                                        var phone = $('.detail-form .phone').val();

                                                        var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
                                                        var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
                                                        var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
                                                        var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

                                                        var billing_address = $('.detail-form .billing_address').val();
                                                        var shipping_address = $('.detail-form .shipping_address').val();
                                                        var special_instruct = $('.special_instruct').val();
                                                        var ajaxData = {
                                                            type: 'paystack',
                                                            coupon_id: coupon_id,
                                                            dicount_price: dicount_price,
                                                            shipping_price: shipping_price,
                                                            shipping_name: shipping_name,
                                                            shipping_id: shipping_id,
                                                            total_price: total_price,
                                                            order_id: order_id,
                                                            name: name,
                                                            email: email,
                                                            phone: phone,
                                                            custom_field_title_1: custom_field_title_1,
                                                            custom_field_title_2: custom_field_title_2,
                                                            custom_field_title_3: custom_field_title_3,
                                                            custom_field_title_4: custom_field_title_4,
                                                            billing_address: billing_address,
                                                            shipping_address: shipping_address,
                                                            special_instruct: special_instruct,
                                                        }
                                                        $.ajax({
                                                            url: '{{ route('paystack.session.store', [$store->slug]) }}',
                                                            method: 'POST',
                                                            data: ajaxData,
                                                            headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                            },
                                                            success: function(data) {
                                                                if (data.status == 'success') {

                                                                    var handler = PaystackPop.setup({
                                                                        key: '{{ $store_payments['paystack_public_key'] }}',
                                                                        email: email,
                                                                        amount: Math.round(total_price),
                                                                        currency: '{{ $store->currency_code }}',
                                                                        ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                                                            1
                                                                        ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                                                                        metadata: {
                                                                            custom_fields: [{
                                                                                display_name: "Mobile Number",
                                                                                variable_name: "mobile_number",
                                                                                value: "phone"
                                                                            }]
                                                                        },

                                                                        callback: function(response) {

                                                                            window.location.href = paystack_callback + '/' + slug + '/' +
                                                                                response.reference + '/' + {{ $order_id }};
                                                                        },
                                                                        onClose: function() {
                                                                            alert('window closed');
                                                                        }
                                                                    });
                                                                    handler.openIframe();

                                                                } else {
                                                                    console.log(data.success);
                                                                    show_toastr("Error", data.success, data["status"]);
                                                                }

                                                            },
                                                            error: function(data) {
                                                                console.log(data);
                                                            }

                                                        });

                                                    }
                                                </script>
                                                <button class="payment-btn" type="submit"
                                                    onclick="payWithPaystack()" id="btnclick">
                                                    {{ __('Pay via Paystack') }}
                                                </button>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_flutterwave_enabled']) && $store_payments['is_flutterwave_enabled'] == 'on')
                                            <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                                            {{-- Flutterwave JAVASCRIPT FUNCTION --}}
                                            <script>
                                                function payWithRave() {
                                                    var API_publicKey = '{{ $store_payments['flutterwave_public_key'] }}';
                                                    var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                                                    var order_id = '{{ $order_id = time() }}';
                                                    var flutter_callback = "{{ url('/flutterwave') }}";
                                                    var slug = '{{ $store->slug }}';
                                                    var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

                                                    // var total_price = $('#Subtotal .total_price').attr('data-value');
                                                    var t_price = $('.final_total_price').html();
                                                    // console.log(t_price);
                                                    var total_price = t_price.replace("{{ $store->currency }}", "");
                                                    // console.log(total_price);
                                                    var coupon_id = $('.hidden_coupon').attr('data_id');
                                                    var dicount_price = $('.dicount_price').html();
                                                    var shipping_price = $('.shipping_price').html();
                                                    var shipping_name = $('.change_location').find(":selected").text();
                                                    var shipping_id = $("input[name='shipping_id']:checked").val();

                                                    var name = $('.detail-form .fname').val();
                                                    var email = $('.detail-form .email').val();
                                                    var phone = $('.detail-form .phone').val();

                                                    var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
                                                    var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
                                                    var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
                                                    var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

                                                    var billing_address = $('.detail-form .billing_address').val();
                                                    var shipping_address = $('.detail-form .shipping_address').val();
                                                    var special_instruct = $('.special_instruct').val();
                                                    var ajaxData = {
                                                        type: 'flutterwave',
                                                        coupon_id: coupon_id,
                                                        dicount_price: dicount_price,
                                                        shipping_price: shipping_price,
                                                        shipping_name: shipping_name,
                                                        shipping_id: shipping_id,
                                                        total_price: total_price,
                                                        order_id: order_id,
                                                        name: name,
                                                        email: email,
                                                        phone: phone,
                                                        custom_field_title_1: custom_field_title_1,
                                                        custom_field_title_2: custom_field_title_2,
                                                        custom_field_title_3: custom_field_title_3,
                                                        custom_field_title_4: custom_field_title_4,
                                                        billing_address: billing_address,
                                                        shipping_address: shipping_address,
                                                        special_instruct: special_instruct,
                                                    }
                                                    $.ajax({
                                                        url: '{{ route('paystack.session.store', [$store->slug]) }}',
                                                        method: 'POST',
                                                        data: ajaxData,
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        },
                                                        success: function(data) {
                                                            if (data.status == 'success') {

                                                                var x = getpaidSetup({
                                                                    PBFPubKey: API_publicKey,
                                                                    customer_email: email,
                                                                    amount: total_price,
                                                                    customer_phone: phone,
                                                                    currency: '{{ $store->currency_code }}',
                                                                    txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) +
                                                                        'fluttpay_online-' +
                                                                        {{ date('Y-m-d') }},
                                                                    meta: [{
                                                                        metaname: "payment_id",
                                                                        metavalue: "id"
                                                                    }],
                                                                    onclose: function() {},
                                                                    callback: function(response) {

                                                                        var txref = response.tx.txRef;

                                                                        if (
                                                                            response.tx.chargeResponseCode == "00" ||
                                                                            response.tx.chargeResponseCode == "0"
                                                                        ) {
                                                                            window.location.href = flutter_callback +
                                                                                '/{{ $store->slug }}/' + txref + '/' +
                                                                                {{ $order_id }};
                                                                        } else {
                                                                            // redirect to a failure page.
                                                                        }
                                                                        x.close(); // use this to close the modal immediately after payment.
                                                                    }
                                                                });

                                                            } else {
                                                                console.log(data.success);
                                                                show_toastr("Error", data.success, data["status"]);
                                                            }

                                                        },
                                                        error: function(data) {
                                                            console.log(data);
                                                        }

                                                    });

                                                }
                                            </script>
                                            {{-- /PAYSTACK JAVASCRIPT FUNCTION --}}
                                            <div class="col-sm-6 col-12">
                                                <button class="payment-btn" type="submit" onclick="payWithRave()">
                                                    {{ __('Pay via Flutterwave') }}
                                                </button>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_razorpay_enabled']) && $store_payments['is_razorpay_enabled'] == 'on')
                                            @php
                                                $logo = asset(Storage::url('uploads/logo/'));

                                                $company_logo = Utility::get_superadmin_logo();
                                            @endphp
                                            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                                            {{-- Flutterwave JAVASCRIPT FUNCTION --}}
                                            <script>
                                                function payRazorPay() {
                                                    var getAmount = $('.total_price').data('value');
                                                    var order_id = '{{ $order_id = time() }}';
                                                    var product_id = '{{ $order_id }}';
                                                    var razorPay_callback = '{{ url('razorpay') }}';
                                                    var product_array = '{{ $encode_product }}';
                                                    var product = JSON.parse(product_array.replace(/&quot;/g, '"'));

                                                    var t_price = $('.final_total_price').html();
                                                    var total_price = t_price.replace("{{ $store->currency }}", "");
                                                    console.log(total_price);
                                                    var coupon_id = $('.hidden_coupon').attr('data_id');
                                                    var dicount_price = $('.dicount_price').html();
                                                    var shipping_price = $('.shipping_price').html();
                                                    var shipping_name = $('.change_location').find(":selected").text();
                                                    var shipping_id = $("input[name='shipping_id']:checked").val();

                                                    var name = $('.detail-form .fname').val();
                                                    var email = $('.detail-form .email').val();
                                                    var phone = $('.detail-form .phone').val();

                                                    var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
                                                    var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
                                                    var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
                                                    var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

                                                    var billing_address = $('.detail-form .billing_address').val();
                                                    var shipping_address = $('.detail-form .shipping_address').val();
                                                    var special_instruct = $('.special_instruct').val();
                                                    var ajaxData = {
                                                        type: 'razorpay',
                                                        coupon_id: coupon_id,
                                                        dicount_price: dicount_price,
                                                        shipping_price: shipping_price,
                                                        shipping_name: shipping_name,
                                                        shipping_id: shipping_id,
                                                        total_price: total_price,
                                                        order_id: order_id,
                                                        name: name,
                                                        email: email,
                                                        phone: phone,
                                                        custom_field_title_1: custom_field_title_1,
                                                        custom_field_title_2: custom_field_title_2,
                                                        custom_field_title_3: custom_field_title_3,
                                                        custom_field_title_4: custom_field_title_4,
                                                        billing_address: billing_address,
                                                        shipping_address: shipping_address,
                                                        special_instruct: special_instruct,
                                                    }
                                                    $.ajax({
                                                        url: '{{ route('paystack.session.store', [$store->slug]) }}',
                                                        method: 'POST',
                                                        data: ajaxData,
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        },
                                                        success: function(data) {
                                                            if (data.status == 'success') {

                                                                total_price = `'${total_price}'`;
                                                                total_price = total_price.replace(/\,/g, '');
                                                                total_price = total_price.replace(/'/g, '');
                                                                total_price = Math.round(total_price) * 100;
                                                                var options = {
                                                                    "key": "{{ $store_payments['razorpay_public_key'] }}", // your Razorpay Key Id
                                                                    "amount": total_price,
                                                                    "name": product,
                                                                    "currency": '{{ $store->currency_code }}',
                                                                    "description": "Order Id : " + order_id,
                                                                    "image": "{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}",
                                                                    "handler": function(response) {
                                                                        window.location.href = razorPay_callback + '/{{ $store->slug }}/' +
                                                                            response.razorpay_payment_id + '/' + order_id;
                                                                    },
                                                                    "theme": {
                                                                        "color": "#528FF0"
                                                                    }
                                                                };

                                                                var rzp1 = new Razorpay(options);
                                                                rzp1.open();

                                                            } else {
                                                                console.log(data.success);
                                                                show_toastr("Error", data.success, data["status"]);
                                                            }

                                                        },
                                                        error: function(data) {
                                                            console.log(data);
                                                        }

                                                    });


                                                }
                                            </script>
                                            <div class="col-sm-6 col-12">
                                                <button class="payment-btn" type="submit" onclick="payRazorPay()">
                                                    {{ __('Pay via Razorpay') }}
                                                </button>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_paytm_enabled']) && $store_payments['is_paytm_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">

                                                <form id="payment-paytm-form" method="POST"
                                                    action="{{ route('paytm.prepare.payments', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="order_id"
                                                        value="{{ str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, '100', STR_PAD_LEFT) }}">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id" class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    @php
                                                        $skrill_data = [
                                                            'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                                            'user_id' => 'user_id',
                                                            'amount' => 'amount',
                                                            'currency' => 'currency',
                                                        ];
                                                        session()->put('skrill_data', $skrill_data);
                                                    @endphp
                                                    <button class="payment-btn" type="submit" id="owner-paytm">
                                                        {{ __('Pay via Paytm') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_mercado_enabled']) && $store_payments['is_mercado_enabled'] == 'on')
                                            <script>
                                                function payMercado() {
                                                    var t_price = $('.final_total_price').html();
                                                    var total_price = t_price.replace("{{ $store->currency }}", "");
                                                    console.log(total_price);
                                                    var coupon_id = $('.hidden_coupon').attr('data_id');
                                                    var dicount_price = $('.dicount_price').html();
                                                    var shipping_price = $('.shipping_price').html();
                                                    var shipping_name = $('.change_location').find(":selected").text();
                                                    var shipping_id = $("input[name='shipping_id']:checked").val();

                                                    var name = $('.detail-form .fname').val();
                                                    var email = $('.detail-form .email').val();
                                                    var phone = $('.detail-form .phone').val();

                                                    var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
                                                    var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
                                                    var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
                                                    var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

                                                    var billing_address = $('.detail-form .billing_address').val();
                                                    var shipping_address = $('.detail-form .shipping_address').val();
                                                    var special_instruct = $('.special_instruct').val();
                                                    var ajaxData = {
                                                        type: 'mercadopago',
                                                        coupon_id: coupon_id,
                                                        dicount_price: dicount_price,
                                                        shipping_price: shipping_price,
                                                        shipping_name: shipping_name,
                                                        shipping_id: shipping_id,
                                                        total_price: total_price,
                                                        name: name,
                                                        email: email,
                                                        phone: phone,
                                                        custom_field_title_1: custom_field_title_1,
                                                        custom_field_title_2: custom_field_title_2,
                                                        custom_field_title_3: custom_field_title_3,
                                                        custom_field_title_4: custom_field_title_4,
                                                        billing_address: billing_address,
                                                        shipping_address: shipping_address,
                                                        special_instruct: special_instruct,
                                                    }
                                                    $.ajax({
                                                        url: '{{ route('mercadopago.prepare', $store->slug) }}',
                                                        method: 'POST',
                                                        data: ajaxData,
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        },
                                                        success: function(data) {
                                                            if (data.status == 'success') {
                                                                window.location.href = data.url;
                                                            } else {
                                                                show_toastr("Error", data.success, data["status"]);
                                                            }
                                                        }
                                                    });
                                                }
                                            </script>
                                            <div class="col-sm-6 col-12">
                                                <button class="payment-btn" type="submit" onclick="payMercado()">
                                                    {{ __('Pay via Mercado Pago') }}
                                                </button>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_mollie_enabled']) && $store_payments['is_mollie_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">

                                                <form id="payment-mollie-form" method="POST"
                                                    action="{{ route('mollie.prepare.payments', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id" class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-mollie">
                                                        {{ __('Pay via Mollie') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_skrill_enabled']) && $store_payments['is_skrill_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-skrill-form" method="POST"
                                                    action="{{ route('skrill.prepare.payments', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="transaction_id"
                                                        value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id' }}">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id" class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <button class="payment-btn" type="submit" id="owner-skrill">
                                                        {{ __('Pay via Skrill') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_coingate_enabled']) && $store_payments['is_coingate_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-coingate-form" method="POST"
                                                    action="{{ route('coingate.prepare', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="transaction_id"
                                                        value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id' }}">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id" class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <button class="payment-btn" type="submit"
                                                        id="owner-coingate">
                                                        {{ __('Pay via CoinGate') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_paymentwall_enabled']) && $store_payments['is_paymentwall_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-paymentwall-form" method="POST"
                                                    action="{{ route('paymentwall.session.store', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="transaction_id"
                                                        value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id' }}">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <button class="payment-btn" type="submit"
                                                        id="owner-paymentwall">
                                                        {{ __('Pay via Paymentwall') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_payfast_enabled']) && $store_payments['is_payfast_enabled'] == 'on')
                                            @php
                                                $pfHost = $store_payments['payfast_mode'] == 'sandbox' ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
                                            @endphp
                                            <div class="col-sm-6 col-12">
                                                <form role="form" class="payfast-form"
                                                    action={{ 'https://' . $pfHost . '/eng/process' }}
                                                    method="post" class="require-validation" id="payfast-form">
                                                    <div class="card-btn">
                                                        <div id="get-payfast-inputs"></div>
                                                        <input type="hidden" name="order_id" id="order_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($order_id) }}">
                                                        <button type="button" class="payment-btn"
                                                            onclick="payPayfast()" id="payfast-get-status">
                                                            {{ __('Pay via Payfast') }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_toyyibpay_enabled']) && $store_payments['is_toyyibpay_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-toyyibpay-form" method="POST"
                                                    action="{{ route('toyyibpay.prepare.payments', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit"
                                                        id="owner-toyyibpay">
                                                        {{ __('Pay via ToyyibPay') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if ($store['enable_bank'] == 'on')
                                            <div class="col-sm-6 col-12 ">
                                                <form style="margin-top: 0"
                                                    action="{{ route('user.bank_transfer', $store->slug) }}"
                                                    method="POST" id="bank_transfer_form"
                                                    class="payment-method-form" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="upload-btn-wrapper">
                                                        <label for="bank_transfer_invoice"
                                                            class="file-upload btn payment-btn bg-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="17"
                                                                height="17" viewBox="0 0 17 17" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M6.67952 7.2448C6.69833 7.59772 6.42748 7.89908 6.07456 7.91789C5.59289 7.94357 5.21139 7.97498 4.91327 8.00642C4.51291 8.04864 4.26965 8.29456 4.22921 8.64831C4.17115 9.15619 4.12069 9.92477 4.12069 11.0589C4.12069 12.193 4.17115 12.9616 4.22921 13.4695C4.26972 13.8238 4.51237 14.0691 4.91213 14.1112C5.61223 14.1851 6.76953 14.2586 8.60022 14.2586C10.4309 14.2586 11.5882 14.1851 12.2883 14.1112C12.6881 14.0691 12.9307 13.8238 12.9712 13.4695C13.0293 12.9616 13.0798 12.193 13.0798 11.0589C13.0798 9.92477 13.0293 9.15619 12.9712 8.64831C12.9308 8.29456 12.6875 8.04864 12.2872 8.00642C11.9891 7.97498 11.6076 7.94357 11.1259 7.91789C10.773 7.89908 10.5021 7.59772 10.5209 7.2448C10.5397 6.89187 10.8411 6.62103 11.194 6.63984C11.695 6.66655 12.0987 6.69958 12.4214 6.73361C13.3713 6.8338 14.1291 7.50771 14.2428 8.50295C14.3077 9.07016 14.3596 9.88879 14.3596 11.0589C14.3596 12.229 14.3077 13.0476 14.2428 13.6148C14.1291 14.6095 13.3732 15.2837 12.4227 15.384C11.6667 15.4638 10.4629 15.5384 8.60022 15.5384C6.73752 15.5384 5.5337 15.4638 4.77779 15.384C3.82728 15.2837 3.07133 14.6095 2.95763 13.6148C2.89279 13.0476 2.84082 12.229 2.84082 11.0589C2.84082 9.88879 2.89279 9.07016 2.95763 8.50295C3.0714 7.50771 3.82911 6.8338 4.77903 6.73361C5.10175 6.69958 5.50546 6.66655 6.00642 6.63984C6.35935 6.62103 6.6607 6.89187 6.67952 7.2448Z"
                                                                    fill="white"></path>
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M6.81509 4.79241C6.56518 5.04232 6.16 5.04232 5.91009 4.79241C5.66018 4.5425 5.66018 4.13732 5.91009 3.88741L8.14986 1.64764C8.39977 1.39773 8.80495 1.39773 9.05486 1.64764L11.2946 3.88741C11.5445 4.13732 11.5445 4.5425 11.2946 4.79241C11.0447 5.04232 10.6395 5.04232 10.3896 4.79241L9.24229 3.64508V9.77934C9.24229 10.1328 8.95578 10.4193 8.60236 10.4193C8.24893 10.4193 7.96242 10.1328 7.96242 9.77934L7.96242 3.64508L6.81509 4.79241Z"
                                                                    fill="white"></path>
                                                            </svg>
                                                            {{ __('Upload ') }}
                                                        </label>
                                                        <input type="file" name="bank_transfer_invoice"
                                                            id="bank_transfer_invoice" class="file-input"
                                                            style="display: none">
                                                        <input type="hidden" name="product_id">
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-sm-6 col-12">

                                                <button type="submit" class="payment-btn"
                                                    id="bank_transfer">{{ __('Bank Transfer ') }}</button>
                                            </div>
                                        @endif


                                        @if (isset($store_payments['is_iyzipay_enabled']) && $store_payments['is_iyzipay_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-iyzipay-form" method="post"
                                                    action="{{ route('iyzipay.prepare.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-iyzipay">
                                                        {{ __('Pay via Iyzipay') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_sspay_enabled']) && $store_payments['is_sspay_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-sspay-form" method="POST"
                                                    action="{{ route('sspay.prepare.payments', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-sspay">
                                                        {{ __('Pay via SS pay') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_paytab_enabled']) && $store_payments['is_paytab_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-paytab-form" method="post"
                                                    action="{{ route('pay.with.paytab', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-paytab">
                                                        {{ __('Pay via Paytab') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_benefit_enabled']) && $store_payments['is_benefit_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-benefit-form" method="post"
                                                    action="{{ route('store.benefit.initiate', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-benefit">
                                                        {{ __('Pay via Benefit') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_cashfree_enabled']) && $store_payments['is_cashfree_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-cashfree-form" method="post"
                                                    action="{{ route('store.cashfree.initiate', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit"
                                                        id="owner-cashfree">
                                                        {{ __('Pay via Cashfree') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_aamarpay_enabled']) && $store_payments['is_aamarpay_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-aamarpay-form" method="post"
                                                    action="{{ route('store.pay.aamarpay.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit"
                                                        id="owner-aamarpay">
                                                        {{ __('Pay via AamarPay') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_paytr_enabled']) && $store_payments['is_paytr_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-paytr-form" method="post"
                                                    action="{{ route('store.pay.paytr.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-paytr">
                                                        {{ __('Pay via PayTR') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_yookassa_enabled']) && $store_payments['is_yookassa_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-yookassa-form" method="post"
                                                    action="{{ route('store.pay.yookassa.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-yookassa">
                                                        {{ __('Pay via Yookassa') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_midtrans_enabled']) && $store_payments['is_midtrans_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-midtrans-form" method="post"
                                                    action="{{ route('store.pay.midtrans.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-midtrans">
                                                        {{ __('Pay via Midtrans') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_xendit_enabled']) && $store_payments['is_xendit_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-xendit-form" method="post"
                                                    action="{{ route('store.pay.xendit.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-xendit">
                                                        {{ __('Pay via Xendit') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_paiment_pro_enabled']) && $store_payments['is_paiment_pro_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-paimentpro-form" method="post"
                                                    action="{{ route('store.pay.paimentpro.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">

                                                    <input type="hidden" name="mobile_number"
                                                        class="set_paimentpro_mobile_number">
                                                    <input type="hidden" name="channel"
                                                        class="set_paimentpro_channel">
                                                    <button class="payment-btn" type="submit" id="owner-paimentpro">
                                                        {{ __('Pay via Paiment Pro') }}
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="mobile_number"
                                                    class="form-control-label text-dark">{{ __('Mobile Number') }}</label>
                                                    <input type="text" id="mobile_number"
                                                        name="mobile_number"
                                                        class="form-control paimentpro_mobile_number"
                                                        data-from="mobile_number"
                                                        placeholder="{{ __('Enter Mobile Number') }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="channel"
                                                        class="form-control-label text-dark">{{ __('Channel') }}</label>
    
                                                    <input type="text" id="channel"
                                                        name="channel" class="form-control paimentpro_channel"
                                                        data-from="channel"
                                                        placeholder="{{ __('Enter Channel') }}" required>
                                                    <small style="color: red">{{ __('Example : OMCIV2,MOMO,CARD,FLOOZ ,PAYPAL') }}</small>
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_fedapay_enabled']) && $store_payments['is_fedapay_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-fedapay-form" method="post"
                                                    action="{{ route('store.pay.fedapay.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-fedapay">
                                                        {{ __('Pay via Fedapay') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_nepalste_enabled']) && $store_payments['is_nepalste_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-nepalste-form" method="post"
                                                    action="{{ route('store.pay.nepalste.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-nepalste">
                                                        {{ __('Pay via Nepalste') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_payhere_enabled']) && $store_payments['is_payhere_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-payhere-form" method="post"
                                                    action="{{ route('store.pay.payhere.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-payhere">
                                                        {{ __('Pay via Payhere') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        @if (isset($store_payments['is_cinetpay_enabled']) && $store_payments['is_cinetpay_enabled'] == 'on')
                                            <div class="col-sm-6 col-12">
                                                <form id="payment-cinetpay-form" method="post"
                                                    action="{{ route('store.pay.cinetpay.payment', $store->slug) }}">
                                                    @csrf
                                                    <input type="hidden" name="id"
                                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                    <input type="hidden" name="type" class="customer_type">
                                                    <input type="hidden" name="coupon_id"
                                                        class="customer_coupon_id">
                                                    <input type="hidden" name="dicount_price"
                                                        class="customer_dicount_price">
                                                    <input type="hidden" name="shipping_price"
                                                        class="customer_shipping_price">
                                                    <input type="hidden" name="shipping_name"
                                                        class="customer_shipping_name">
                                                    <input type="hidden" name="shipping_id"
                                                        class="customer_shipping_id">
                                                    <input type="hidden" name="total_price"
                                                        class="customer_total_price">
                                                    <input type="hidden" name="product" class="customer_product">
                                                    <input type="hidden" name="order_id"
                                                        class="customer_order_id">
                                                    <input type="hidden" name="name" class="customer_name">
                                                    <input type="hidden" name="email" class="customer_email">
                                                    <input type="hidden" name="phone" class="customer_phone">
                                                    <input type="hidden" name="custom_field_title_1"
                                                        class="customer_custom_field_title_1">
                                                    <input type="hidden" name="custom_field_title_2"
                                                        class="customer_custom_field_title_2">
                                                    <input type="hidden" name="custom_field_title_3"
                                                        class="customer_custom_field_title_3">
                                                    <input type="hidden" name="custom_field_title_4"
                                                        class="customer_custom_field_title_4">
                                                    <input type="hidden" name="billing_address"
                                                        class="customer_billing_address">
                                                    <input type="hidden" name="shipping_address"
                                                        class="customer_shipping_address">
                                                    <input type="hidden" name="special_instruct"
                                                        class="customer_special_instruct">
                                                    <input type="hidden" name="wts_number"
                                                        class="customer_wts_number">
                                                    <input type="hidden" name="desc"
                                                        value="{{ time() }}">
                                                    <button class="payment-btn" type="submit" id="owner-cinetpay">
                                                        {{ __('Pay via Cinetpay') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Wrapper end -->
    <!-- Footer start -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="floating-wpp"></div>
                <div class="col-md-6 col-sm-6 col-12 {{ env('SITE_RTL') == 'on' ? 'text-right' : 'text-left' }}">
                    <p> &copy; {{date('Y')}} {{ (App\Models\Utility::getValByName('footer_text')) ? App\Models\Utility::getValByName('footer_text') :config('app.name', 'WhatsStore SaaS') }}</p>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <ul class="nav {{ env('SITE_RTL') == 'on' ? 'm-auto float-left' : 'float-right text-left' }}">
                        <div class="floating-wpp"></div>

                        @if (!empty($store->youtube))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $store->youtube }}" target="{{ $store->youtube != '#' ? '_blank' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1"
                                        x="0px" y="0px" viewBox="0 0 409.592 409.592"
                                        style="enable-background:new 0 0 409.592 409.592;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path
                                                    d="M403.882,107.206c-2.15-17.935-19.052-35.133-36.736-37.437c-107.837-13.399-216.883-13.399-324.685,0    C24.762,72.068,7.86,89.271,5.71,107.206c-7.613,65.731-7.613,129.464,0,195.18c2.15,17.935,19.052,35.149,36.751,37.437    c107.802,13.399,216.852,13.399,324.685,0c17.684-2.284,34.586-19.502,36.736-37.437    C411.496,236.676,411.496,172.937,403.882,107.206z M170.661,273.074V136.539l102.4,68.27L170.661,273.074z" />
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            </li>
                        @endif
                        @if (!empty($store->email))
                            <li class="nav-item">
                                <a class="nav-link" href="mailto:{{ $store->email }}" target="”_blank”">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1"
                                        x="0px" y="0px" viewBox="0 0 433.664 433.664"
                                        style="enable-background:new 0 0 433.664 433.664;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path
                                                    d="M229.376,271.616c-4.096,2.56-8.704,3.584-12.8,3.584s-8.704-1.024-12.8-3.584L0,147.2v165.376c0,35.328,28.672,64,64,64    h305.664c35.328,0,64-28.672,64-64V147.2L229.376,271.616z" />
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M369.664,57.088H64c-30.208,0-55.808,21.504-61.952,50.176l215.04,131.072l214.528-131.072    C425.472,78.592,399.872,57.088,369.664,57.088z" />
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            </li>
                        @endif
                        @if (!empty($store->facebook))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $store->facebook }}" target="{{ $store->facebook != '#' ? '_blank' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" height="512"
                                        viewBox="0 0 512 512" width="512" data-name="Layer 1">
                                        <path
                                            d="m420 36h-328a56 56 0 0 0 -56 56v328a56 56 0 0 0 56 56h160.67v-183.076h-36.615v-73.23h36.312v-33.094c0-29.952 14.268-76.746 77.059-76.746l56.565.227v62.741h-41.078c-6.679 0-16.183 3.326-16.183 17.592v29.285h58.195l-6.68 73.23h-54.345v183.071h94.1a56 56 0 0 0 56-56v-328a56 56 0 0 0 -56-56z" />
                                    </svg>
                                </a>
                            </li>
                        @endif
                        @if (!empty($store->instagram))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $store->instagram }}" target="{{ $store->instagram != '#' ? '_blank' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1"
                                        x="0px" y="0px" viewBox="0 0 512 512"
                                        style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path
                                                    d="M371.643,0H140.357C62.964,0,0,62.964,0,140.358v231.285C0,449.037,62.964,512,140.357,512h231.286    C449.037,512,512,449.037,512,371.643V140.358C512,62.964,449.037,0,371.643,0z M481.764,371.643    c0,60.721-49.399,110.121-110.121,110.121H140.357c-60.721,0-110.121-49.399-110.121-110.121V140.358    c0-60.722,49.4-110.122,110.121-110.122h231.286c60.722,0,110.121,49.4,110.121,110.122V371.643z" />
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M256,115.57c-77.434,0-140.431,62.997-140.431,140.431S178.565,396.432,256,396.432    c77.434,0,140.432-62.998,140.432-140.432S333.434,115.57,256,115.57z M256,366.197c-60.762,0-110.196-49.435-110.196-110.197    c0-60.762,49.434-110.196,110.196-110.196c60.763,0,110.197,49.435,110.197,110.197C366.197,316.763,316.763,366.197,256,366.197z    " />
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M404.831,64.503c-23.526,0-42.666,19.141-42.666,42.667c0,23.526,19.14,42.666,42.666,42.666    c23.526,0,42.666-19.141,42.666-42.667S428.357,64.503,404.831,64.503z M404.831,119.599c-6.853,0-12.43-5.576-12.43-12.43    s5.577-12.43,12.43-12.43c6.854,0,12.43,5.577,12.43,12.43S411.685,119.599,404.831,119.599z" />
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            </li>
                        @endif
                        @if (!empty($store->twitter))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $store->twitter }}" target="{{ $store->twitter != '#' ? '_blank' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1"
                                        x="0px" y="0px" viewBox="0 0 512 512"
                                        style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path
                                                    d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016    c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992    c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056    c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152    c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792    c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44    C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568    C480.224,136.96,497.728,118.496,512,97.248z" />
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            </li>
                        @endif
                        @if (!empty($store->whatsapp))
                            <li class="nav-item">
                                <a class="nav-link" href="https://wa.me/{{ $store->whatsapp }}" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1"
                                        x="0px" y="0px" viewBox="0 0 52 52"
                                        style="enable-background:new 0 0 52 52;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path
                                                    d="M26,0C11.663,0,0,11.663,0,26c0,4.891,1.359,9.639,3.937,13.762C2.91,43.36,1.055,50.166,1.035,50.237    c-0.096,0.352,0.007,0.728,0.27,0.981c0.263,0.253,0.643,0.343,0.989,0.237L12.6,48.285C16.637,50.717,21.26,52,26,52    c14.337,0,26-11.663,26-26S40.337,0,26,0z M26,50c-4.519,0-8.921-1.263-12.731-3.651c-0.161-0.101-0.346-0.152-0.531-0.152    c-0.099,0-0.198,0.015-0.294,0.044l-8.999,2.77c0.661-2.413,1.849-6.729,2.538-9.13c0.08-0.278,0.035-0.578-0.122-0.821    C3.335,35.173,2,30.657,2,26C2,12.767,12.767,2,26,2s24,10.767,24,24S39.233,50,26,50z" />
                                                <path
                                                    d="M42.985,32.126c-1.846-1.025-3.418-2.053-4.565-2.803c-0.876-0.572-1.509-0.985-1.973-1.218    c-1.297-0.647-2.28-0.19-2.654,0.188c-0.047,0.047-0.089,0.098-0.125,0.152c-1.347,2.021-3.106,3.954-3.621,4.058    c-0.595-0.093-3.38-1.676-6.148-3.981c-2.826-2.355-4.604-4.61-4.865-6.146C20.847,20.51,21.5,19.336,21.5,18    c0-1.377-3.212-7.126-3.793-7.707c-0.583-0.582-1.896-0.673-3.903-0.273c-0.193,0.039-0.371,0.134-0.511,0.273    c-0.243,0.243-5.929,6.04-3.227,13.066c2.966,7.711,10.579,16.674,20.285,18.13c1.103,0.165,2.137,0.247,3.105,0.247    c5.71,0,9.08-2.873,10.029-8.572C43.556,32.747,43.355,32.331,42.985,32.126z M30.648,39.511    c-10.264-1.539-16.729-11.708-18.715-16.87c-1.97-5.12,1.663-9.685,2.575-10.717c0.742-0.126,1.523-0.179,1.849-0.128    c0.681,0.947,3.039,5.402,3.143,6.204c0,0.525-0.171,1.256-2.207,3.293C17.105,21.48,17,21.734,17,22c0,5.236,11.044,12.5,13,12.5    c1.701,0,3.919-2.859,5.182-4.722c0.073,0.003,0.196,0.028,0.371,0.116c0.36,0.181,0.984,0.588,1.773,1.104    c1.042,0.681,2.426,1.585,4.06,2.522C40.644,37.09,38.57,40.701,30.648,39.511z" />
                                            </g>
                                        </g>

                                    </svg>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        {{-- <div class="floating-wpp"></div> --}}
    </footer>
    <!-- Footer end -->
    <!--scripts start here-->
    <script src="{{ asset('custom/js/jquery.min.js') }}"></script>
    <script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>

    @if (isset($settings['SITE_RTL']) && $settings['SITE_RTL'] == 'on')
        <script src="{{ asset('custom/js/rtl-custom.js') }}"></script>
    @else
        <script src="{{ asset('custom/js/custom.js') }}"></script>
    @endif

    <script src="{{ asset('custom/js/slick.min.js') }}"></script>

    <!--scripts end here-->
    <div class="overlay"></div>

    <!-- Popup add to cart variant start -->
    <div class="modal" id="commonModal">
        <div class="modal-dialog-centered">
            <div class="modal-content-inner">
                <div class="modal-header">
                    <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                        {{ __('Close Popup') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24"
                            viewBox="0 0 25 24" fill="none">
                            <path opacity="0.4"
                                d="M12.4172 22C17.9401 22 22.4172 17.5228 22.4172 12C22.4172 6.47715 17.9401 2 12.4172 2C6.89439 2 2.41724 6.47715 2.41724 12C2.41724 17.5228 6.89439 22 12.4172 22Z"
                                fill="#25314C" />
                            <path
                                d="M13.4783 12L15.9483 9.53005C16.2413 9.23705 16.2413 8.76202 15.9483 8.46902C15.6553 8.17602 15.1803 8.17602 14.8873 8.46902L12.4173 10.939L9.94726 8.46902C9.65426 8.17602 9.17925 8.17602 8.88625 8.46902C8.59325 8.76202 8.59325 9.23705 8.88625 9.53005L11.3563 12L8.88625 14.47C8.59325 14.763 8.59325 15.238 8.88625 15.531C9.03225 15.677 9.22425 15.751 9.41625 15.751C9.60825 15.751 9.80025 15.678 9.94625 15.531L12.4163 13.061L14.8863 15.531C15.0323 15.677 15.2243 15.751 15.4163 15.751C15.6083 15.751 15.8003 15.678 15.9463 15.531C16.2393 15.238 16.2393 14.763 15.9463 14.47L13.4783 12Z"
                                fill="#25314C" />
                        </svg>
                    </button>
                </div>
                <div class="body">
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="checkoutModal">
        <div class="modal-dialog-centered">
            <div class="modal-content-inner">
                <div class="modal-header">
                    <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                        {{ __('Close Popup') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24"
                            viewBox="0 0 25 24" fill="none">
                            <path opacity="0.4"
                                d="M12.4172 22C17.9401 22 22.4172 17.5228 22.4172 12C22.4172 6.47715 17.9401 2 12.4172 2C6.89439 2 2.41724 6.47715 2.41724 12C2.41724 17.5228 6.89439 22 12.4172 22Z"
                                fill="#25314C" />
                            <path
                                d="M13.4783 12L15.9483 9.53005C16.2413 9.23705 16.2413 8.76202 15.9483 8.46902C15.6553 8.17602 15.1803 8.17602 14.8873 8.46902L12.4173 10.939L9.94726 8.46902C9.65426 8.17602 9.17925 8.17602 8.88625 8.46902C8.59325 8.76202 8.59325 9.23705 8.88625 9.53005L11.3563 12L8.88625 14.47C8.59325 14.763 8.59325 15.238 8.88625 15.531C9.03225 15.677 9.22425 15.751 9.41625 15.751C9.60825 15.751 9.80025 15.678 9.94625 15.531L12.4163 13.061L14.8863 15.531C15.0323 15.677 15.2243 15.751 15.4163 15.751C15.6083 15.751 15.8003 15.678 15.9463 15.531C16.2393 15.238 16.2393 14.763 15.9463 14.47L13.4783 12Z"
                                fill="#25314C" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-6 d-flex justify-content-center col-form-label mb-0">
                        {{-- <a href="{{route('customer.login',$store->slug)}}" class="btn btn-secondary btn-light rounded-pill">{{__('Countinue to sign in')}}</a> --}}
                        <a data-url="{{ route('customer.login', $store->slug) }}" data-ajax-popup="true"
                            data-title="{{ __('Login') }}" data-toggle="modal" data-size="md"
                            class="btn btn-secondary btn-light rounded-pill guest-login" id="loginBtn">
                            {{ __('Countinue to sign in') }}
                        </a>
                    </div>
                    <div class="col-6 d-flex justify-content-center col-form-label mb-0 asGuest">
                        <a href="#footer"
                            class="btn btn-secondary btn-light rounded-pill guest-login">{{ __('Continue as guest') }}</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal" id="Orderview">
        <div class="modal-dialog-centered">
            <div class="modal-content-inner modal-xl">
                <div class="modal-header">
                    <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                        Close Popup
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24"
                            viewBox="0 0 25 24" fill="none">
                            <path opacity="0.4"
                                d="M12.4172 22C17.9401 22 22.4172 17.5228 22.4172 12C22.4172 6.47715 17.9401 2 12.4172 2C6.89439 2 2.41724 6.47715 2.41724 12C2.41724 17.5228 6.89439 22 12.4172 22Z"
                                fill="#25314C" />
                            <path
                                d="M13.4783 12L15.9483 9.53005C16.2413 9.23705 16.2413 8.76202 15.9483 8.46902C15.6553 8.17602 15.1803 8.17602 14.8873 8.46902L12.4173 10.939L9.94726 8.46902C9.65426 8.17602 9.17925 8.17602 8.88625 8.46902C8.59325 8.76202 8.59325 9.23705 8.88625 9.53005L11.3563 12L8.88625 14.47C8.59325 14.763 8.59325 15.238 8.88625 15.531C9.03225 15.677 9.22425 15.751 9.41625 15.751C9.60825 15.751 9.80025 15.678 9.94625 15.531L12.4163 13.061L14.8863 15.531C15.0323 15.677 15.2243 15.751 15.4163 15.751C15.6083 15.751 15.8003 15.678 15.9463 15.531C16.2393 15.238 16.2393 14.763 15.9463 14.47L13.4783 12Z"
                                fill="#25314C" />
                        </svg>
                    </button>
                </div>
                <div class="body">
                </div>
            </div>
        </div>
    </div>
    <!-- Popup add to cart variant end -->

    <script src="{{ asset('custom/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/floating-wpp.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            $('.floating-wpp').floatingWhatsApp({
                phone: '{{ $store->whatsapp_contact_number }}',
                popupMessage: 'how may i help you?',
                showPopup: true,
                message: 'Message To Send',
                headerTitle: 'Ask Questions'
            });
        });
    </script>

    <script src="{{ asset('custom/js/swiper-bundle.min.js') }}"></script>

    <script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
    {{-- pwa customer app --}}

    @if ($store->enable_pwa_store == 'on')
        <script type="text/javascript">
            const container = document.querySelector("body")

            const coffees = [];

            if ("serviceWorker" in navigator) {

                window.addEventListener("load", function() {
                    navigator.serviceWorker
                        .register("{{ asset('serviceWorker.js') }}")
                        .then(res => console.log("service worker registered"))
                        .catch(err => console.log("service worker not registered", err))

                })
            }
        </script>
    @endif

    <script src="{{ asset('assets/js/cookieconsent.js') }}"></script>
    @php

        $cookie_settings = Utility::AdminSettings();
    @endphp


    @if ($cookie_settings['enable_cookie'] == 'on' && $cookie_settings['cookie_logging'] == 'on')
        @include('layouts.cookie_consent')
    @endif


    @if (isset($settings['SITE_RTL']) && $settings['SITE_RTL'] == 'on')
        <script src="{{ asset('custom/js/rtl-custom.js') }}"></script>
    @else
        <script src="{{ asset('custom/js/custom.js') }}"></script>
    @endif
    <script src="{{ asset('custom/js/slick.min.js') }}"></script>
    <script>
        $(document).on('click', '#loginBtn', function() {
            $(".close").trigger("click");
        });

        $(document).on('click', '.asGuest', function() {
            $(".close").trigger("click");
            @php
                $allPaymentsDisabled =
                    (isset($store['enable_whatsapp']) && $store['enable_whatsapp'] == 'on') ||
                    (isset($store['enable_telegram']) && $store['enable_telegram'] == 'on') ||
                    (isset($store['enable_cod']) && $store['enable_cod'] == 'on') ||
                    (isset($store_payments['is_stripe_enabled']) && $store_payments['is_stripe_enabled'] == 'on') ||
                    (isset($store_payments['is_paypal_enabled']) && $store_payments['is_paypal_enabled'] == 'on') ||
                    (isset($store_payments['is_paystack_enabled']) &&
                        $store_payments['is_paystack_enabled'] == 'on') ||
                    (isset($store_payments['is_flutterwave_enabled']) &&
                        $store_payments['is_flutterwave_enabled'] == 'on') ||
                    (isset($store_payments['is_razorpay_enabled']) &&
                        $store_payments['is_razorpay_enabled'] == 'on') ||
                    (isset($store_payments['is_mercado_enabled']) &&
                        $store_payments['is_mercado_enabled'] == 'on') ||
                    (isset($store_payments['is_paytm_enabled']) && $store_payments['is_paytm_enabled'] == 'on') ||
                    (isset($store_payments['is_mollie_enabled']) && $store_payments['is_mollie_enabled'] == 'on') ||
                    (isset($store_payments['is_skrill_enabled']) && $store_payments['is_skrill_enabled'] == 'on') ||
                    (isset($store_payments['is_coingate_enabled']) &&
                        $store_payments['is_coingate_enabled'] == 'on') ||
                    (isset($store_payments['is_paymentwall_enabled']) &&
                        $store_payments['is_paymentwall_enabled'] == 'on') ||
                    (isset($store_payments['is_payfast_enabled']) &&
                        $store_payments['is_payfast_enabled'] == 'on') ||
                    (isset($store_payments['is_toyyibpay_enabled']) &&
                        $store_payments['is_toyyibpay_enabled'] == 'on') ||
                    (isset($store_payments['is_manuallypay_enabled']) &&
                        $store_payments['is_manuallypay_enabled'] == 'on') ||
                    (isset($store_payments['is_bank_enabled']) && $store_payments['is_bank_enabled'] == 'on') ||
                    (isset($store_payments['is_iyzipay_enabled']) &&
                        $store_payments['is_iyzipay_enabled'] == 'on') ||
                    (isset($store_payments['is_sspay_enabled']) && $store_payments['is_sspay_enabled'] == 'on') ||
                    (isset($store_payments['is_paytab_enabled']) && $store_payments['is_paytab_enabled'] == 'on') ||
                    (isset($store_payments['is_benefit_enabled']) &&
                        $store_payments['is_benefit_enabled'] == 'on') ||
                    (isset($store_payments['is_cashfree_enabled']) &&
                        $store_payments['is_cashfree_enabled'] == 'on') ||
                    (isset($store_payments['is_aamarpay_enabled']) &&
                        $store_payments['is_aamarpay_enabled'] == 'on') ||
                    (isset($store_payments['is_paytr_enabled']) && $store_payments['is_paytr_enabled'] == 'on');

                $allPaymentsDisabled = $allPaymentsDisabled ? true : false;
            @endphp

            @if (!$allPaymentsDisabled)
                show_toastr('Error', 'Payment method not found.<br>Please contect store owner.', 'error');
            @else
                $("#asGuest, #paymentsBtn").show();
            @endif

        });

         $(document).on('click', '.authUser', function() {
            @php
                $allPaymentsDisabled =
                    (isset($store['enable_whatsapp']) && $store['enable_whatsapp'] == 'on') ||
                    (isset($store['enable_telegram']) && $store['enable_telegram'] == 'on') ||
                    (isset($store['enable_cod']) && $store['enable_cod'] == 'on') ||
                    (isset($store_payments['is_stripe_enabled']) && $store_payments['is_stripe_enabled'] == 'on') ||
                    (isset($store_payments['is_paypal_enabled']) && $store_payments['is_paypal_enabled'] == 'on') ||
                    (isset($store_payments['is_paystack_enabled']) &&
                        $store_payments['is_paystack_enabled'] == 'on') ||
                    (isset($store_payments['is_flutterwave_enabled']) &&
                        $store_payments['is_flutterwave_enabled'] == 'on') ||
                    (isset($store_payments['is_razorpay_enabled']) &&
                        $store_payments['is_razorpay_enabled'] == 'on') ||
                    (isset($store_payments['is_mercado_enabled']) &&
                        $store_payments['is_mercado_enabled'] == 'on') ||
                    (isset($store_payments['is_paytm_enabled']) && $store_payments['is_paytm_enabled'] == 'on') ||
                    (isset($store_payments['is_mollie_enabled']) && $store_payments['is_mollie_enabled'] == 'on') ||
                    (isset($store_payments['is_skrill_enabled']) && $store_payments['is_skrill_enabled'] == 'on') ||
                    (isset($store_payments['is_coingate_enabled']) &&
                        $store_payments['is_coingate_enabled'] == 'on') ||
                    (isset($store_payments['is_paymentwall_enabled']) &&
                        $store_payments['is_paymentwall_enabled'] == 'on') ||
                    (isset($store_payments['is_payfast_enabled']) &&
                        $store_payments['is_payfast_enabled'] == 'on') ||
                    (isset($store_payments['is_toyyibpay_enabled']) &&
                        $store_payments['is_toyyibpay_enabled'] == 'on') ||
                    (isset($store_payments['is_manuallypay_enabled']) &&
                        $store_payments['is_manuallypay_enabled'] == 'on') ||
                    (isset($store_payments['is_bank_enabled']) && $store_payments['is_bank_enabled'] == 'on') ||
                    (isset($store_payments['is_iyzipay_enabled']) &&
                        $store_payments['is_iyzipay_enabled'] == 'on') ||
                    (isset($store_payments['is_sspay_enabled']) && $store_payments['is_sspay_enabled'] == 'on') ||
                    (isset($store_payments['is_paytab_enabled']) && $store_payments['is_paytab_enabled'] == 'on') ||
                    (isset($store_payments['is_benefit_enabled']) &&
                        $store_payments['is_benefit_enabled'] == 'on') ||
                    (isset($store_payments['is_cashfree_enabled']) &&
                        $store_payments['is_cashfree_enabled'] == 'on') ||
                    (isset($store_payments['is_aamarpay_enabled']) &&
                        $store_payments['is_aamarpay_enabled'] == 'on') ||
                    (isset($store_payments['is_paytr_enabled']) && $store_payments['is_paytr_enabled'] == 'on');

                $allPaymentsDisabled = $allPaymentsDisabled ? true : false;
            @endphp

            @if (!$allPaymentsDisabled)
                    show_toastr('Error', 'Payment method not found.<br>Please contect store owner.', 'error');
            @else
                    $("#asGuest,#paymentsBtn").show();
                    $(".checkoutBtn").hide();
            @endif

        });

        $(document).on('click', '#guestBtn,#loginBtn', function() {
            $(".checkoutBtn").hide();
        });

        @if (!empty($pro_cart) && count($pro_cart['products']) > 0)
            $(".checkoutBtn").show();
            $("#asGuest,#paymentsBtn").hide();
            // $("#paymentsBtn").hide();
        @else
            $(".checkoutBtn,#asGuest,#paymentsBtn ").hide();
            // $("#asGuest").hide();
            // $("#paymentsBtn").hide();
        @endif
    </script>

    <script>
        $(document).on('click', 'a[data-ajax-popup="true"]', function() {
            var title = $(this).data('title');
            var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
            var url = $(this).data('url');
            $("#commonModal .modal-title").html(title);
            $("#commonModal .modal-dialog-centered .modal-content-inner").addClass('modal-' + size);
            if ($(this).data('name') == 'custom-addcart' || title == 'Login') {
                $("#commonModal modal-dialog-centered .modal-content-inner").removeClass('modal-lg')
            }
            $.ajax({
                url: url,
                success: function(data) {

                    $("#commonModal").addClass('active');
                    $('#commonModal .body').html(data);
                    taskCheckbox();
                    commonLoader();
                    common_bind("#commonModal");
                    common_bind_select("#commonModal");
                    $('#enable_subscriber').trigger('change');
                    $('#enable_flat').trigger('change');
                    $('#enable_domain').trigger('change');
                    $('#enable_header_img').trigger('change');
                    $('#enable_product_variant').trigger('change');
                    $('#enable_social_button').trigger('change');
                },
                error: function(data) {
                    data = data.responseJSON;
                }
            });
        });

        $(document).on('click', 'a[class="ac-viewbtn"]', function() {
            var title = $(this).data('title');
            var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
            var url = $(this).data('url');
            $("#Orderview .modal-title").html(title);
            $("#Orderview .modal-dialog-centered .modal-content-inner").addClass('modal-' + size);
            if ($(this).data('name') == 'custom-addcart' || title == 'Login') {
                $("#Orderview modal-dialog-centered .modal-content-inner").removeClass('modal-lg')
            }
            $.ajax({
                url: url,
                success: function(data) {

                    $("#Orderview").addClass('active');
                    $('#Orderview .body').html(data);
                    taskCheckbox();
                    commonLoader();
                    common_bind("#Orderview");
                    common_bind_select("#Orderview");
                    $('#enable_subscriber').trigger('change');
                    $('#enable_flat').trigger('change');
                    $('#enable_domain').trigger('change');
                    $('#enable_header_img').trigger('change');
                    $('#enable_product_variant').trigger('change');
                    $('#enable_social_button').trigger('change');
                },
                error: function(data) {
                    data = data.responseJSON;
                }
            });
        });

        $(document).on('click', 'a[id="checkoutBtn"]', function() {
            var title = $(this).data('title');
            var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
            var url = $(this).data('url');
            $("#checkoutModal .modal-title").html(title);
            $("#checkoutModal .modal-dialog-centered .modal-content-inner").addClass('modal-' + size);
            $.ajax({
                url: url,
                success: function(data) {
                    $("#checkoutModal").addClass('active');
                    $('#checkoutModal .body').html(data);
                    taskCheckbox();
                    commonLoader();
                    common_bind("#checkoutModal");
                    common_bind_select("#checkoutModal");
                    $('#enable_subscriber').trigger('change');
                    $('#enable_flat').trigger('change');
                    $('#enable_domain').trigger('change');
                    $('#enable_header_img').trigger('change');
                    $('#enable_product_variant').trigger('change');
                    $('#enable_social_button').trigger('change');
                },
                error: function(data) {
                    data = data.responseJSON;
                }
            });
        });

        function taskCheckbox() {
            var checked = 0;
            var count = 0;
            var percentage = 0;

            count = $("#check-list input[type=checkbox]").length;
            checked = $("#check-list input[type=checkbox]:checked").length;
            percentage = parseInt(((checked / count) * 100), 10);
            if (isNaN(percentage)) {
                percentage = 0;
            }
            $(".custom-label").text(percentage + "%");
            $('#taskProgress').css('width', percentage + '%');


            $('#taskProgress').removeClass('bg-warning');
            $('#taskProgress').removeClass('bg-primary');
            $('#taskProgress').removeClass('bg-success');
            $('#taskProgress').removeClass('bg-danger');

            if (percentage <= 15) {
                $('#taskProgress').addClass('bg-danger');
            } else if (percentage > 15 && percentage <= 33) {
                $('#taskProgress').addClass('bg-warning');
            } else if (percentage > 33 && percentage <= 70) {
                $('#taskProgress').addClass('bg-primary');
            } else {
                $('#taskProgress').addClass('bg-success');
            }
        }


        $(function() {
            commonLoader();
            $(document).on("click", ".show_confirm", function() {
                var form = $(this).closest("form");
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "This action can not be undone. Do you want to continue?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });


        function commonLoader() {



            if ($(".multi-select").length > 0) {
                $($(".multi-select")).each(function(index, element) {
                    var id = $(element).attr('id');
                    var multipleCancelButton = new Choices(
                        '#' + id, {
                            removeItemButton: true,
                        }
                    );
                });

            }

            if ($(".pc-dt-simple").length) {
                const dataTable = new simpleDatatables.DataTable(".pc-dt-simple");
            }


            // if ($(".pc-tinymce-2").length) {

            //     tinymce.init({
            //         selector: '.pc-tinymce-2',
            //         height: "400",
            //         content_style: 'body { font-family: "Inter", sans-serif; }'
            //     });
            // }


            if ($(".d_week").length) {

                $(".d_week").each(function(index) {
                    (function() {
                        const d_week = new Datepicker(document.querySelector('.d_week'), {
                            buttonClass: 'btn',
                            format: 'yyyy-mm-dd',
                        });
                    })();
                });
            }

            if ($(".pc-timepicker-2").length) {
                document.querySelector(".pc-timepicker-2").flatpickr({
                    enableTime: true,
                    noCalendar: true,
                });
            }

            if ($("#data_picker1").length) {

                $("#data_picker1").each(function(index) {
                    (function() {
                        const d_week = new Datepicker(document.querySelector('#data_picker1'), {
                            buttonClass: 'btn',
                            format: 'yyyy-mm-dd',
                        });
                    })();
                });
            }


            if ($("#data_picker2").length) {
                $("#data_picker2").each(function(index) {
                    (function() {
                        const d_week = new Datepicker(document.querySelector('#data_picker2'), {
                            buttonClass: 'btn',
                            format: 'yyyy-mm-dd',
                        });
                    })();
                });
            }
            if ($("#pc-daterangepicker-2").length) {

                document.querySelector("#pc-daterangepicker-2").flatpickr({
                    mode: "range",

                });
            }


            if ($(".jscolor").length) {
                jscolor.installByClassName("jscolor");
            }

            // for Choose file
            $(document).on('change', 'input[type=file]', function() {
                var fileclass = $(this).attr('data-filename');
                var finalname = $(this).val().split('\\').pop();
                $('.' + fileclass).html(finalname);
            });
        }

        $(document).on('click', '.close-button', function() {
            $(this).parents("#commonModal").removeClass("active");
        });


        $(document).on('click', '.close-button', function() {
            $(this).parents("#checkoutModal").removeClass("active");
        });

        function common_bind(selector = "body") {
            var $datepicker = $(selector + ' .datepicker');
            if ($(".datepicker").length) {
                $('.datepicker').daterangepicker({
                    singleDatePicker: true,
                    format: 'yyyy-mm-dd',
                    locale: date_picker_locale,
                });

            }
            if ($(".custom-datepicker").length) {
                $('.custom-datepicker').daterangepicker({
                    singleDatePicker: true,
                    format: 'Y-MM',
                    locale: {
                        format: 'Y-MM'
                    }
                });
            }

            if ($(".summernote").length) {
                $('.summernote').summernote({
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                        ['list', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'unlink']],
                    ],
                    height: 250,
                });
                // $('.summernote-simple').summernote({
                //     dialogsInBody: !0,
                //     minHeight: 200,
                //     toolbar: [
                //         ['style', ['style']],
                //         ["font", ["bold", "italic", "underline", "clear", "strikethrough"]],
                //         ['fontname', ['fontname']],
                //         ['color', ['color']],
                //         ["para", ["ul", "ol", "paragraph"]],
                //     ]
                // });
            }
        }


        function common_bind_select(selector = "body") {
            if (jQuery().selectric) {
                $(".selectric").selectric({
                    disableOnMobile: false,
                    nativeOnMobile: false
                });
            }
            if ($(".jscolor").length) {
                jscolor.installByClassName("jscolor");
            }

            var Select = function() {
                var e = $('[data-toggle="select"]');
                e.length && e.each(function() {
                    $(this).select({
                        minimumResultsForSearch: -1
                    })
                })
            }()
        }
    </script>

    <script>
        $('.search-drp-btn').on('click', function(e) {
            e.preventDefault();
            setTimeout(function() {
                $(".header-search form").toggleClass("active");
                $('.overlay').addClass('menu-overlay menu-search');
            }, 50);
        });

        $(document).ready(function() {
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".product_tableese .product_item").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        $('#search').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });

        $(document).ready(function() {
            $("#btn-search").on("click", function() {
                var value = $(this).val().toLowerCase();
                $(".product_tableese .product_item").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>


    <script src="https://js.paystack.co/v1/inline.js"></script>

    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        @if (
            !empty($store_payments['is_stripe_enabled']) &&
            isset($store_payments['is_stripe_enabled']) &&
            $store_payments['is_stripe_enabled'] == 'on' &&
            !empty($store_payments['stripe_key']) &&
            !empty($store_payments['stripe_secret'])
            )
            @php
                $stripe_session = Session::get('stripe_session');
            @endphp
            @if(isset($stripe_session) && $stripe_session)
                <script>
                    var stripe = Stripe('{{ $store_payments['stripe_key'] }}');
                    stripe.redirectToCheckout({
                        sessionId: '{{ $stripe_session->id }}',
                    }).then((result) => {
                        console.log(result);
                    });
                </script>
            @endif
        @endif
    </script>

    @php
        $store_settings = \App\Models\Store::where('slug', $store->slug)->first();
    @endphp
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $store_settings->google_analytic }}"></script>

    {!! $store_settings->storejs !!}

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{ $store_settings->google_analytic }}');
    </script>


    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            // s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ !empty($store_settings->facebook_pixel) }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ $store_settings->facebook_pixel }}&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->

    <!--scripts start here-->

    <script>
        function show_toastr(title, message, type) {
            var o, i;
            var icon = '';
            var cls = '';
            if (type == 'success') {
                icon = 'fas fa-check-circle';
                // cls = 'success';
                cls = 'success';
            } else {
                icon = 'fas fa-times-circle';
                cls = 'danger';
            }
            // console.log(type, cls);
            $.notify({
                icon: icon,
                title: " " + title,
                message: message,
                url: ""
            }, {
                element: "body",
                type: cls,
                allow_dismiss: !0,
                placement: {
                    from: 'top',
                    align: 'right'
                },
                offset: {
                    x: 15,
                    y: 15
                },
                spacing: 10,
                z_index: 1080,
                delay: 2500,
                timer: 6000,
                url_target: "_blank",
                mouse_over: !1,
                animate: {
                    enter: o,
                    exit: i
                },
                template: '<div class = "toast bg-' + cls + ' fade show animated fadeInDown"' +
                    'role = "alert"' +
                    'aria-live = "assertive"' +
                    'aria-atomic = "true"' +
                    'data-notify - position = "top-right"' +
                    'style ="display: inline-block; margin: 0px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 1080; top: 15px; animation-iteration-count: 1;">' +
                    '<div class = "d-flex"> <div class = "toast-body"> ' + message +
                    '</div><button type="button" class="btn-close btn-close-white me-2 m-auto"  style="padding-top: 15px;" aria-hidden="true" data-dismiss="modal" data-notify="dismiss" data-bs-dismiss="toast" aria-label="Close"></button>' +
                    '</div></div>'
            });
        }

        $(document).ready(function() {
            $('.change_location').trigger('change');

            setTimeout(function() {
                var shipping_id = $("input[name='shipping_id']:checked").val();
                getTotal(shipping_id);
            }, 200);
        });

        $(document).on('change', '.shipping_mode', function() {
            var shipping_id = this.value;
            getTotal(shipping_id);
        });

        function getTotal(shipping_id) {
            // var pro_total_price = $('.pro_total_price').attr('data-original');
            var sub_total_price = $('.sub_total_price').attr('data-value');

            var coupon = $('.coupon').val();

            if (shipping_id == undefined) {
                $('.shipping_price_add').hide();
                return false
            } else {
                $('.shipping_price_add').show();
            }

            $.ajax({
                url: '{{ route('user.shipping', [$store->slug, '_shipping']) }}'.replace('_shipping',
                    shipping_id),
                data: {
                    "pro_total_price": sub_total_price,
                    "coupon": coupon,
                    "_token": "{{ csrf_token() }}",
                },
                method: 'POST',
                context: this,
                dataType: 'json',

                success: function(data) {
                    // var price = data.price + sub_total_price;
                    $('.shipping_price').html(data.price);
                    $('.pro_total_price').html(data.total_price);
                    $('.pro_total_price').attr('data-original', data.total_price);
                }
            });
        }

        $(document).on('change', '.change_location', function() {
            var location_id = $('.change_location').val();

            if (location_id == 0) {
                $('#location_hide').hide();

            } else {
                $('#location_hide').show();

            }

            $.ajax({
                url: '{{ route('user.location', [$store->slug, '_location_id']) }}'.replace(
                    '_location_id',
                    location_id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                method: 'POST',
                context: this,
                dataType: 'json',

                success: function(data) {
                    var html = '';
                    var shipping_id =
                        '{{ isset($cust_details['shipping_id']) ? $cust_details['shipping_id'] : '' }}';
                    $.each(data.shipping, function(key, value) {
                        var checked = '';
                        if (shipping_id != '' && shipping_id == value.id) {
                            checked = 'checked';
                        }
                        html +=
                            '<div class = "radio-group" >' +
                            '<input type = "radio" name="shipping_id"' +
                            'data-id="' + value.price + '" value="' + value.id + '"' +
                            '" id="shipping_price' + key + '" class="shipping_mode"' + checked +
                            '>' +
                            ' <label name="shipping_label" for="shipping_price' + key +
                            '" class="shipping_label"> ' + value.name +
                            '</label></div>';


                        // '<div class="radio-group shipping_location"><input type="radio" name="shipping_id" data-id="' +
                        // value.price + '" value="' + value.id +
                        //     '" id="shipping_price' + key + '" class="shipping_mode" ' +
                        //     checked + '>' +
                        //     ' <label name="shipping_label" for="shipping_price' + key +
                        //     '" class="shipping_label"> ' + value.name +
                        //     '</label></div>';
                    });
                    $('#shipping_location_content').html(html);
                }
            });
        });


        $(document).on('click', '.apply-coupon', function(e) {
            e.preventDefault();
            var ele = $(this);
            // var coupon = ele.closest('.row').find('.coupon').val();
            var coupon = $('#stripe_coupon').val();
            var hidden_field = $('.hidden_coupon').val();
            var price = $('#card-summary .product_total').val();
            var shipping_price = $('.shipping_price').html();
            /* if(coupon == ""){
                    show_toastr('Error', 'Please apply coupon code.', 'error');
                }*/
            if (coupon == hidden_field && coupon != "") {
                show_toastr('Error', 'Coupon Already Used', 'error');
            } else {
                if (coupon != '') {
                    $.ajax({
                        url: '{{ route('apply.productcoupon') }}',
                        datType: 'json',
                        data: {
                            price: price,
                            shipping_price: shipping_price,
                            store_id: {{ $store->id }},
                            coupon: coupon
                        },
                        success: function(data) {
                            $('#stripe_coupon, #paypal_coupon').val(coupon);
                            if (data.is_success) {
                                $('.hidden_coupon').val(coupon);
                                $('.hidden_coupon').attr(data);

                                $('.dicount_price').html(data.discount_price);
                                $('.pro_total_price').attr('data-original', data.final_price);
                                var html = '';
                                // html += '<span data-value="' + data.final_price + '">' + data.final_price + '</span>'
                                //html += '<span data-value="' + data.final_price + '">' + data.final_price + '</span>'
                                html += data.final_price;
                                $('.final_total_price').html(html);

                                // $('.coupon-tr').show().find('.coupon-price').text(data.discount_price);
                                // $('.final-price').text(data.final_price);
                                show_toastr('Success', data.message, 'success');
                            } else {
                                // $('.coupon-tr').hide().find('.coupon-price').text('');
                                $('.final-price').text(data.final_price);

                                show_toastr('Error', data.message, 'error');
                            }
                        }
                    })
                } else {
                    var hidd_cou = $('.hidd_val').val();
                    console.log(hidd_cou);
                    if (hidd_cou == "") {
                        var total_pa_val = $(".total_pay_price").val();
                        $(".final_total_price").html(total_pa_val);
                        $(".dicount_price").html(0.00);
                        // console.log(total_pa_val);
                    }
                    show_toastr('Error', '{{ __('Invalid Coupon Code.') }}', 'error');
                }
            }
        });

        $(document).on('click', '#owner-whatsapp', function() {
            var product_array = '{{ $encode_product }}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            // console.log(product);
            var order_id = '{{ $order_id = '#' . time() }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').attr('data-original');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();


            var ajaxData = {
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                shipping_price: shipping_price,
                shipping_name: shipping_name,
                shipping_id: shipping_id,
                total_price: total_price,
                product: product,
                order_id: order_id,
                name: name,
                email: email,
                phone: phone,
                custom_field_title_1: custom_field_title_1,
                custom_field_title_2: custom_field_title_2,
                custom_field_title_3: custom_field_title_3,
                custom_field_title_4: custom_field_title_4,
                billing_address: billing_address,
                shipping_address: shipping_address,
                special_instruct: special_instruct,

                wts_number: $('#wts_number').val()
            }

            getWhatsappUrl(ajaxData);

            var submitAjax = null;

            submitAjax = $.ajax({
                url: '{{ route('user.whatsapp', $store->slug) }}',
                method: 'POST',
                data: ajaxData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    if (submitAjax != null) {
                        submitAjax.abort();
                    }
                },
                success: function(data) {
                    if (data.status == 'success') {

                        removesession();

                        show_toastr(data["success"], '{!! session('+data["status"]+') !!}', data["status"]);

                        setTimeout(function() {
                            var url =
                                '{{ route('store-complete.complete', [$store->slug, ':id']) }}';
                            url = url.replace(':id', data.order_id);

                            window.location.href = url;
                        }, 1000);

                        setTimeout(function() {
                            var get_url_msg_url = $('#return_url').val();
                            var append_href = get_url_msg_url + '  ' +
                                '{{ route('user.order', [$store->slug, Crypt::encrypt(!empty($order->id) ? $order->id + 1 : 0 + 1)]) }}';
                            window.open(append_href, '_blank');
                        }, 20);

                    } else {
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        });

        $(document).on('click', '#owner-telegram', function() {
            var product_array = '{{ $encode_product }}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').attr('data-original');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;

            var ajaxData = {
                type: 'telegram',
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                shipping_price: shipping_price,
                shipping_name: shipping_name,
                shipping_id: shipping_id,
                total_price: total_price,
                product: product,
                order_id: order_id,
                name: name,
                email: email,
                phone: phone,
                custom_field_title_1: custom_field_title_1,
                custom_field_title_2: custom_field_title_2,
                custom_field_title_3: custom_field_title_3,
                custom_field_title_4: custom_field_title_4,
                billing_address: billing_address,
                shipping_address: shipping_address,
                special_instruct: special_instruct,

                wts_number: $('#wts_number').val()
            }

            getWhatsappUrl(ajaxData);

            var submitAjaxtelegram = null;

            submitAjaxtelegram = $.ajax({
                url: '{{ route('user.telegram', $store->slug) }}',
                method: 'POST',
                data: ajaxData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    if (submitAjaxtelegram != null) {
                        submitAjaxtelegram.abort();
                    }
                },
                success: function(data) {
                    // console.log(data);
                    if (data.status == 'success') {

                        show_toastr('success', data.success, data.status);
                        // show_toastr(data["success"], '{!! session('+data["status"]+') !!}', data["status"]);

                        setTimeout(function() {

                            removesession();

                            var url =
                                '{{ route('store-complete.complete', [$store->slug, ':id']) }}';
                            url = url.replace(':id', data.order_id);

                            window.location.href = url;
                        }, 1000);

                    } else {
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        });

        $(document).on('click', '#cash_on_delivery', function() {
            var product_array = '{{ $encode_product }}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').attr('data-original');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;

            var ajaxData = {
                type: 'COD',
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                shipping_price: shipping_price,
                shipping_name: shipping_name,
                shipping_id: shipping_id,
                total_price: total_price,
                product: product,
                order_id: order_id,
                name: name,
                email: email,
                phone: phone,
                custom_field_title_1: custom_field_title_1,
                custom_field_title_2: custom_field_title_2,
                custom_field_title_3: custom_field_title_3,
                custom_field_title_4: custom_field_title_4,
                billing_address: billing_address,
                shipping_address: shipping_address,
                special_instruct: special_instruct,
            }

            $.ajax({
                url: '{{ route('user.cod', $store->slug) }}',
                method: 'POST',
                data: ajaxData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        // show_toastr(data["success"], '{!! session('+data["status"]+') !!}', data["status"]);
                        show_toastr(data["status"], data["success"], data["status"]);

                        setTimeout(function() {
                            var url =
                                '{{ route('store-complete.complete', [$store->slug, ':id']) }}';
                            url = url.replace(':id', data.order_id);
                            window.location.href = url;
                        }, 1000);
                        removesession();
                    } else {
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        });


        $(document).on('click', '#bank_transfer', function() {
            var product_array = '{!! $encode_product !!}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';
            var total_price = $('.final_total_price').attr('data-original');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();
            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();
            var files = $('#bank_transfer_invoice')[0].files;


            var formData = new FormData($("#bank_transfer_form")[0]);
            formData.append('product', product_array);
            formData.append('order_id', order_id);
            formData.append('total_price', total_price);
            if (coupon_id != undefined) {
                formData.append('coupon_id', coupon_id);
            }
            formData.append('dicount_price', dicount_price);
            formData.append('shipping_price', shipping_price);
            formData.append('shipping_name', shipping_name);
            if (shipping_id != undefined) {
                formData.append('shipping_id', shipping_id);
            }
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            if (custom_field_title_1 != undefined) {
                formData.append('custom_field_title_1', custom_field_title_1);
            }
            if (custom_field_title_2 != undefined) {
                formData.append('custom_field_title_2', custom_field_title_2);
            }
            if (custom_field_title_3 != undefined) {
                formData.append('custom_field_title_3', custom_field_title_3);
            }
            if (custom_field_title_4 != undefined) {
                formData.append('custom_field_title_4', custom_field_title_4);
            }
            if (billing_address != undefined) {
                formData.append('billing_address', billing_address);
            }
            if (shipping_address != undefined) {
                formData.append('shipping_address', shipping_address);
            }
            if (special_instruct != undefined) {
                formData.append('special_instruct', special_instruct);
            }

            formData.append('files', files);

            $.ajax({
                url: '{{ route('user.bank_transfer', $store->slug) }}',
                method: 'POST',
                // data: data,
                data: formData,
                contentType: false,
                // cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        removesession();

                        // show_toastr(data["success"], '{!! session('+data["status"]+') !!}', data["status"]);
                        show_toastr(data["status"], data["success"], data["status"]);
                        setTimeout(function() {
                            var url =
                                '{{ route('store-complete.complete', [$store->slug, ':id']) }}';
                            url = url.replace(':id', data.order_id);
                            window.location.href = url;
                        }, 2500);
                    } else {
                        // console.log(data);

                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        });


        $(document).on('click', '#owner-paypal', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;





            $(".customer_type").val('paypal');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-paypal-form');
            formcc.submit();
        });
        $(document).on('click', '#owner-stripe', function(event) {

            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;





            $(".customer_type").val('stripe');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);
            console.log($(".customer_detailes").val());

            var formcc = document.getElementById('payment-form');
            formcc.submit();
        });
        $(document).on('click', '#owner-paystack', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;




            $(".customer_type").val('paystack');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);
            console.log($(".customer_detailes").val());

            var formcc = document.getElementById('payment-paystack-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-paytm', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;

            $(".customer_type").val('paytm');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);
            console.log($(".customer_detailes").val());

            var formcc = document.getElementById('payment-paytm-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-mollie', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('mollie');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-mollie-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-skrill', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;

            $(".customer_type").val('skrill');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formccxsc = document.getElementById('payment-skrill-form');
            formccxsc.submit();
        });

        $(document).on('click', '#owner-coingate', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;

            $(".customer_type").val('coingate');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formccxsc = document.getElementById('payment-coingate-form');
            formccxsc.submit();
        });
        $(document).on('click', '#owner-paymentwall', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;

            $(".customer_type").val('paymentwall');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formccxsc = document.getElementById('payment-paymentwall-form');
            formccxsc.submit();
        });

        $(document).on('click', '#owner-toyyibpay', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('toyyibpay');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-toyyibpay-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-iyzipay', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('iyzipay');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-iyzipay-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-sspay', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('sspay');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-sspay-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-paytab', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('paytab');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-paytab-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-benefit', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('benefit');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-benefit-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-cashfree', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('cashfree');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-cashfree-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-aamarpay', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('AamarPay');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-aamarpay-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-paytr', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('PayTR');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-paytr-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-yookassa', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('Yookassa');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-yookassa-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-midtrans', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('Midtrans');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-midtrans-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-xendit', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('Xendit');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-xendit-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-paimentpro', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var paimentpro_mobile_number = $('.paimentpro_mobile_number').val();
            var paimentpro_channel = $('.paimentpro_channel').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('Paiment Pro');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);
            $(".set_paimentpro_mobile_number").val(paimentpro_mobile_number);
            $(".set_paimentpro_channel").val(paimentpro_channel);

            if (paimentpro_mobile_number.length > 0 && paimentpro_channel.length > 0) {
                var formcc = document.getElementById('payment-paimentpro-form');
                formcc.submit();
            } else {
                show_toastr("Error", 'Mobile number and Channel field is required.', 'error');
            }
        });

        $(document).on('click', '#owner-fedapay', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('Fedapay');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-fedapay-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-nepalste', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('Nepalste');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-nepalste-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-payhere', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('Payhere');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-payhere-form');
            formcc.submit();
        });

        $(document).on('click', '#owner-cinetpay', function(event) {
            event.preventDefault();
            var order_id = '{{ $order_id = !empty($order->id) ? $order->id + 1 : 0 + 1 }}';

            // var total_price = $('#Subtotal .total_price').attr('data-value');
            var total_price = $('.final_total_price').html();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();

            var submitAjaxtelegram = null;


            $(".customer_type").val('Cinetpay');
            $(".customer_coupon_id").val(coupon_id);
            $(".customer_dicount_price").val(dicount_price);
            $(".customer_shipping_price").val(shipping_price);
            $(".customer_shipping_name").val(shipping_name);
            $(".customer_shipping_id").val(shipping_id);
            $(".customer_total_price").val(total_price);
            $(".customer_order_id").val(order_id);
            $(".customer_name").val(name);
            $(".customer_email").val(email);
            $(".customer_phone").val(phone);
            $(".customer_custom_field_title_1").val(custom_field_title_1);
            $(".customer_custom_field_title_2").val(custom_field_title_2);
            $(".customer_custom_field_title_3").val(custom_field_title_3);
            $(".customer_custom_field_title_4").val(custom_field_title_4);
            $(".customer_billing_address").val(billing_address);
            $(".customer_shipping_address").val(shipping_address);
            $(".customer_special_instruct").val(special_instruct);

            var formcc = document.getElementById('payment-cinetpay-form');
            formcc.submit();
        });

        //for create/get Whatsapp Url
        function getWhatsappUrl(ajaxData = '') {
            $.ajax({
                url: '{{ route('get.whatsappurl', $store->slug) }}',
                method: 'post',
                data: ajaxData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#return_url').val(data.url);
                        $('#return_order_id').val(data.order_id);

                    } else {
                        $('#return_url').val('')
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        }

        //for create/get Telegram Url
        function getTelegramUrl(ajaxData = '') {
            $.ajax({
                url: '{{ route('get.whatsappurl', $store->slug) }}',
                method: 'post',
                data: ajaxData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#return_url').val(data.url);
                        $('#return_order_id').val(data.order_id);

                    } else {
                        $('#return_url').val('')
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        }

        function removesession() {
            $.ajax({
                url: '{{ route('remove.session', $store->slug) }}',
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {

                }
            });
        }

        $(document).on('change', '.variant-selection', function() {
            var variants = [];
            $(".variant-selection").each(function(index, element) {
                if (element.value != '' && element.value != undefined) {
                    var el_val = element.value;
                    variants.push(el_val);
                }
            });
            if (variants.length > 0) {

                $.ajax({
                    url: '{{ route('get.products.variant.quantity') }}',
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        variants: variants.join(' : '),
                        product_id: $('#product_id').val()
                    },

                    success: function(data) {
                        if (data.variant_id == 0) {
                            $('.variant_stock1').addClass('d-none');
                            $('.variation_price1').html('Please Select Variants');
                            $('.variant_qty').html('0');
                        } else {
                            $('.variation_price1').html(data.price);
                            $('#variant_id').val(data.variant_id);
                            $('.variant_qty').html(data.quantity);
                            $('.variant_stock1').removeClass('d-none');
                            if (data.quantity != 0) {
                                $('.variant_stock1').html('In Stock');
                                $(".variant_stock1").css({
                                    "backgroundColor": "#C2FFA5",
                                    "color": "#58A336"
                                });
                            } else {
                                $(".variant_stock1").css({
                                    "backgroundColor": "#FFA5A5",
                                    "color": "#A33636"
                                });
                                $('.variant_stock1').html('Out Of Stock');
                            }
                        }
                    }
                });
            }
        });

        $(document).on('click', '.add_to_cart', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: '{{ route('user.addToCart', ['__product_id', $store->slug]) }}'.replace(
                    '__product_id', id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.success, 'success');
                        $("#shoping_count").attr("value", response.item_count);
                        location.reload();
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function(result) {
                    // console.log('error');
                }
            });
        });

        $(document).on('click', '.add_to_cart_variant', function(e) {
            // e.preventDefault();
            var id = $(this).attr('data-id');
            var variants = [];
            $(".variant-selection").each(function(index, element) {
                variants.push(element.value);
            });

            if (jQuery.inArray('0', variants) != -1) {
                show_toastr('Error', "{{ __('Please select all option.') }}", 'error');
                return false;
            }

            var variation_ids = $('#variant_id').val();

            $.ajax({
                url: '{{ route('user.addToCart', ['__product_id', $store->slug, 'variation_id']) }}'
                    .replace('__product_id', id).replace('variation_id', variation_ids ?? 0),
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    variants: variants.join(' : '),
                },
                success: function(response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.success, 'success');
                        $("#shoping_count").attr("value", response.item_count);
                        location.reload();
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function(result) {
                    // console.log('error');
                }
            });
        })


        $(document).ready(function() {

            var url = window.location.href;
            var n = url.split("/");
            if (n[n.length - 1] == 'login') {
                $("#login-BTN").trigger('click');
            }


            var type = 'hightolow';
            // when change sorting order
            $('#product_sort').on('click', 'li', function() {
                type = $(this).attr('data-val');
                ajaxFilterProjectView(type);
                $('#product_sort option').removeClass('active');
                $(this).addClass('active');
            });

            $('#myproducts').on('click', function() {
                type = $(this).attr('data-val');
                ajaxFilterProjectView(type);
            });


            ajaxFilterProjectView(type);

            function ajaxFilterProjectView(type) {
                var mainEle = $('#product_view');
                var view = '{{ $view }}';
                var slug = '{{ $store->slug }}';

                $.ajax({
                    url: '{{ route('filter.product.view') }}',
                    type: 'POST',
                    data: {
                        view: view,
                        types: type,
                        slug: slug,
                    },
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        mainEle.html(data.html);
                        var data_id = $('.pro_category').find('.custom-list-group-item.active').attr(
                            'data-href');
                        $('#product_view .collection-items').addClass('0All');
                        $('#product_view .collection-items.' + data_id).removeClass('0All');
                        $('#sort_by .' + view).addClass('active');
                    }
                });

            }
        });

        $(document).on('click', '.custom-list-group-item', function() {
            var dataHref = $(this).attr('data-href');
            $('#product_view .collection-items').removeClass('active');
            $('#product_view .collection-items').addClass('d-none');
            $('.' + dataHref).addClass('active');
            $('.' + dataHref).removeClass('d-none');
            $('div').removeClass('nav-open');
        });

        $(".productTab").click(function(e) {
            $('.side-menu-wrapper ').removeClass('active-menu');
            $('.overlay ').removeClass('menu-overlay');
            $('body').removeClass('no-scroll active-menu');
        });

        $(".product_qty_input").on('blur', function(e) {
            e.preventDefault();
            var product_id = $(this).attr('data-id');
            var arrkey = $(this).parents('div').attr('data-id');
            var quantity = $(this).closest('div').find('input[name="quantity"]').val();
            var sum = 0;
            var subtax = 0;
            var total = 0;

            setTimeout(function() {
                if (quantity == 0 || quantity == '' || quantity < 0) {
                    location.reload();
                    return false;
                }

                $.ajax({
                    url: '{{ route('user-product_qty.product_qty', ['__product_id', $store->slug, 'arrkeys']) }}'
                        .replace('__product_id', product_id).replace('arrkeys', arrkey),
                    type: "post",
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "product_qty": quantity,
                    },
                    success: function(response) {
                        if (response.status == "Success") {
                            var taxsss = [];

                            if ('carttotal' in response) {
                                // var data = data['items'];
                                $.each(response.product, function(key, value) {
                                    total += value.total;
                                    if (value.variant_id != 0) {
                                        sum += value.variant_subtotal;

                                        if (value.tax.length > 0) {
                                            $.each(value.tax, function(key, tv) {
                                                var t_name = tv.tax_name;
                                                var subtax = value.variant_price * value
                                                    .quantity * tv.tax / 100;

                                                if (taxsss[key] != undefined) {
                                                    taxsss[key] = taxsss[key] + subtax;
                                                } else {
                                                    taxsss[key] = subtax;
                                                }
                                                $('.total_tax_' + key).text(addCommas(
                                                    taxsss[key]));


                                                $('#product-variant-id-' + value
                                                    .variant_id + ' .variant_tax_' +
                                                    key
                                                ).text(tv.tax_name + ' ' + tv.tax +
                                                    '% ' + '(' + subtax + ')');
                                            });
                                        } else {
                                            $('#product-variant-id-' + value.variant_id +
                                                ' .variant_tax').text('-');
                                        }
                                        // $('#product-variant-id-' + value.variant_id +'.sub_total_price').text(addCommas(total));
                                        $('#product-variant-id-' + value.variant_id +
                                                '.subtotal')
                                            .text(addCommas(value.total));

                                    } else {
                                        sum += value.subtotal;

                                        if (value.tax.length > 0) {
                                            $.each(value.tax, function(key, tv) {
                                                var t_name = tv.tax_name;

                                                // console.log("Value for key '" + keyToFind + "': " + value);

                                                var subtax = value.price * value
                                                    .quantity *
                                                    tv.tax / 100;

                                                if (taxsss[key] != undefined) {
                                                    taxsss[key] = taxsss[key] + subtax;
                                                } else {
                                                    taxsss[key] = subtax;
                                                }
                                                $('.total_tax_' + key).text(addCommas(
                                                    taxsss[key]));

                                                $('#product-id-' + value.id + ' .tax_' +
                                                    key).text(tv.tax_name + ' ' + tv
                                                    .tax + '% ' + '(' + subtax + ')'
                                                );
                                            });

                                        } else {

                                            $('#product-id-' + value.product_id + ' .tax').text('-');
                                        }
                                        // $('#product-id-' + value.id +' .sub_total_price').text(addCommas(total));

                                        $('#product-id-' + value.product_id + '.subtotal').text(
                                            addCommas(value.total));

                                    }
                                });
                                $('#displaytotal').text(addCommas(sum));
                                $('.sub_total_price').text(addCommas(total));
                            }

                        } else {
                            show_toastr('Error', response.error, 'error');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);

                            // location.reload(); // then reload the page.(3)
                        }
                    },
                    error: function(result) {
                        // console.log('error12');
                    }
                });
            }, 500);
        });

        var site_currency_symbol_position = '{{ $store_settings['currency_symbol_position'] }}';
        var site_currency_symbol = '{{ $store_settings['currency'] }}';

        function addCommas(num) {
            var number = parseFloat(num).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            return ((site_currency_symbol_position == "pre") ? site_currency_symbol : '') + number + ((
                site_currency_symbol_position == "post") ? site_currency_symbol : '');
        }

        $(".product_qty").on('click', function(e) {
            val = $('#product_qty_input').val();
            e.preventDefault();

            var product_id = $(this).attr('data-id');
            var arrkey = $(this).parents('div').attr('data-id');
            // var qty_id = $(this).val();
            var sum = 0;
            var subtax = 0;
            var total = 0;
            var quantity = $(this).closest('div').find('input[name="quantity"]').val();

            if ($(this).attr('data-option') == 'decrease') {
                qty_id = parseInt(quantity) - parseInt(1);
            } else {
                qty_id = parseInt(quantity) + parseInt(1);
            }

            setTimeout(function() {
                if (qty_id != 0 || qty_id == '' || qty_id < 0) {
                    // location.reload();
                    return false;
                }
            });

            if (qty_id != 0) {

                $.ajax({
                    url: '{{ route('user-product_qty.product_qty', ['__product_id', $store->slug, 'arrkeys']) }}'
                        .replace('__product_id', product_id).replace('arrkeys', arrkey),
                    type: "post",
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "product_qty": qty_id,
                    },
                    success: function(response) {
                        if (response.status == "Success") {
                            var taxsss = [];

                            if ('carttotal' in response) {
                                // var data = data['items'];
                                $.each(response.product, function(key, value) {
                                    total += value.total;
                                    if (value.variant_id != 0) {
                                        sum += value.variant_subtotal;

                                        if (value.tax.length > 0) {
                                            $.each(value.tax, function(key, tv) {
                                                var t_name = tv.tax_name;
                                                var subtax = value.variant_price * value
                                                    .quantity * tv.tax / 100;

                                                if (taxsss[key] != undefined) {
                                                    taxsss[key] = taxsss[key] + subtax;
                                                } else {
                                                    taxsss[key] = subtax;
                                                }
                                                $('.total_tax_' + key).text(addCommas(
                                                    taxsss[key]));


                                                $('#product-variant-id-' + value
                                                    .variant_id + ' .variant_tax_' +
                                                    key
                                                ).text(tv.tax_name + ' ' + tv.tax +
                                                    '% ' + '(' + subtax + ')');
                                            });
                                        } else {
                                            $('#product-variant-id-' + value.variant_id +
                                                ' .variant_tax').text('-');
                                        }
                                        // $('#product-variant-id-' + value.variant_id +'.sub_total_price').text(addCommas(total));
                                        $('#product-variant-id-' + value.variant_id +
                                                '.subtotal')
                                            .text(addCommas(value.total));

                                    } else {
                                        sum += value.subtotal;

                                        if (value.tax.length > 0) {
                                            $.each(value.tax, function(key, tv) {
                                                var t_name = tv.tax_name;

                                                var subtax = value.price * value
                                                    .quantity *
                                                    tv.tax / 100;
                                                if (taxsss[key] != undefined) {
                                                    taxsss[key] = taxsss[key] + subtax;
                                                } else {
                                                    taxsss[key] = subtax;
                                                }
                                                $('.total_tax_' + key).text(addCommas(
                                                    taxsss[key]));

                                                $('#product-id-' + value.id + ' .tax_' +
                                                    key).text(tv.tax_name + ' ' + tv
                                                    .tax + '% ' + '(' + subtax + ')'
                                                );
                                            });

                                        } else {
                                            $('#product-id-' + value.product_id + ' .tax').text('-');
                                        }
                                        // $('#product-id-' + value.id +'.sub_total_price').text(addCommas(total));
                                        $('#product-id-' + value.product_id + '.subtotal').text(
                                            addCommas(value.total));

                                    }
                                });
                                $('#displaytotal').text(addCommas(sum));
                                $('.sub_total_price').text(addCommas(total));
                            }

                        } else {
                            show_toastr('Error', response.error, 'error');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);

                            // location.reload(); // then reload the page.(3)
                        }
                    },
                    error: function(result) {
                        console.log('error12');
                    }
                });
            };
        });

        var quantity = 0;
        $('.quantity-increment').click(function() {
            var t = $(this).siblings('.quantity');
            var quantity = parseInt($(t).val());
            $(t).val(quantity + 1);
        });
        $('.quantity-decrement').click(function() {
            var t = $(this).siblings('.quantity');
            var quantity = parseInt($(t).val());
            if (quantity > 1) {
                $(t).val(quantity - 1);
            }
        });
    </script>



    @if (Session::has('success'))
        <script>
            show_toastr('{{ __('Success') }}', '{!! session('success') !!}', 'success');
        </script>
        {{ Session::forget('success') }}
    @endif
    @if (Session::has('error'))
        <script>
            show_toastr('{{ __('Error') }}', '{!! session('error') !!}', 'error');
        </script>
        {{ Session::forget('error') }}
    @endif


    <script type="text/javascript"></script>
    <script>
        $('#guest').click(function() {
            $('#signIn').removeClass('active').addClass('inactive');
            $(this).removeClass('inactive').addClass('active').css("background-color", "silver");
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#card-summary").offset().top
            }, 1000);
        });
    </script>

    <script>
        $('.authUser').on('click', function() {
            var name = $('.fname').val();
            var email = $('.email').val();
            var phone = $('.phone').val();
            var billing_address = $('.billing_address').val();
            var shipping_address = $('.shipping_address').val();
            var custom_field_title_1 = $('.custom_field_title_1').val();
            var custom_field_title_2 = $('.custom_field_title_2').val();
            var custom_field_title_3 = $('.custom_field_title_3').val();
            var custom_field_title_4 = $('.custom_field_title_4').val();
            var special_instruct = $('.special_instruct').val();

            var ajaxData = {
                name: name,
                email: email,
                phone: phone,
                custom_field_title_1: custom_field_title_1,
                custom_field_title_2: custom_field_title_2,
                custom_field_title_3: custom_field_title_3,
                custom_field_title_4: custom_field_title_4,
                billing_address: billing_address,
                shipping_address: shipping_address,
                special_instruct: special_instruct,
            }
        });


        function payPayfast() {
            var t_price = $('.final_total_price').html();
            var order_id = '{{ $order_id = time() }}';
            console.log(order_id);

            var total_price = t_price.replace("{{ $store->currency }}", "");
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var shipping_price = $('.shipping_price').html();
            var shipping_name = $('.change_location').find(":selected").text();
            var shipping_id = $("input[name='shipping_id']:checked").val();

            var name = $('.detail-form .fname').val();
            var email = $('.detail-form .email').val();
            var phone = $('.detail-form .phone').val();

            var custom_field_title_1 = $('.detail-form .custom_field_title_1').val();
            var custom_field_title_2 = $('.detail-form .custom_field_title_2').val();
            var custom_field_title_3 = $('.detail-form .custom_field_title_3').val();
            var custom_field_title_4 = $('.detail-form .custom_field_title_4').val();

            var billing_address = $('.detail-form .billing_address').val();
            var shipping_address = $('.detail-form .shipping_address').val();
            var special_instruct = $('.special_instruct').val();
            var ajaxData = {
                type: 'payfast',
                order_id: order_id,
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                shipping_price: shipping_price,
                shipping_name: shipping_name,
                shipping_id: shipping_id,
                total_price: total_price,
                name: name,
                email: email,
                phone: phone,
                custom_field_title_1: custom_field_title_1,
                custom_field_title_2: custom_field_title_2,
                custom_field_title_3: custom_field_title_3,
                custom_field_title_4: custom_field_title_4,
                billing_address: billing_address,
                shipping_address: shipping_address,
                special_instruct: special_instruct,
            }

            $.ajax({
                url: '{{ route('payfast', $store->slug) }}',
                method: 'POST',
                data: ajaxData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success == true) {
                        $('#get-payfast-inputs').append(data.inputs);
                        $('.payfast-form').submit();
                    } else {
                        show_toastr('Error', data.success, 'error')
                    }
                }
            });
        }
    </script>

    <script>
        $('.menu-toggle-btn').on('click', function(e) {
            e.preventDefault();
            setTimeout(function() {
                $('body').addClass('no-scroll active-menu');
                $(".side-menu-wrapper").toggleClass("active-menu");
                $('.overlay').addClass('menu-overlay');
            }, 50);
        });
        $('body').on('click', '.overlay.menu-overlay, .menu-close-icon', function(e) {
            e.preventDefault();
            $('body').removeClass('no-scroll active-menu');
            $(".side-menu-wrapper").removeClass("active-menu");
            $('.overlay').removeClass('menu-overlay');
        });
    </script>



    <!--scripts end here-->

</body>

</html>
