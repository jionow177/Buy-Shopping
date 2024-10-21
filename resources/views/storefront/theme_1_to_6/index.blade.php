@php
    $settings = Utility::settings();
    // store RTL
    $s_logo = \App\Models\Utility::get_file('uploads/store_logo/');
    $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
    $data = DB::table('settings');
    $logo1 = \App\Models\Utility::get_file('uploads/logo/');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');

    $meta_image = \App\Models\Utility::get_file('uploads/meta_image');
@endphp
<style>
    /* New custom added */
    .wp-btn-wrapper {
        row-gap: 15px;
    }
    .wp-btn-wrapper .btn {
        width: 100%;
    }
</style>
<!DOCTYPE html>
<html lang="en" dir="{{ $settings['SITE_RTL'] == 'on' ? 'rtl' : '' }}">

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
    <meta property="og:image" content="{{ $meta_image . '/' . $store->meta_image }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ $store->meta_keyword }}">
    <meta property="twitter:description" content="{{ ucfirst($store->meta_description) }}">
    <meta property="twitter:image" content="{{ $meta_image . '/' . $store->meta_image }}">



    <link rel="icon"
        href="{{ $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
        type="image" sizes="16x16">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css" integrity="sha384-QYIZto+st3yW+o8+5OHfT6S482Zsvz2WfOzpFSXMF9zqeLcFV0/wlZpMtyFcZALm" crossorigin="anonymous">

    @if (isset($settings['SITE_RTL']) && $settings['SITE_RTL'] == 'on')
        @if (!empty($store->store_theme))
            <link rel="stylesheet" href="{{ asset('custom/css/rtl/rtl-' . $store->store_theme . '.css') }}" id="stylesheet">
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

     {{-- floating whatsapp --}}
     <link rel="stylesheet" href="{{ asset('assets/css/floating-wpp.min.css') }}">

    {{-- pwa customer app --}}
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="mobile-wep-app-capable" content="yes">
    <meta name="apple-mobile-wep-app-capable" content="yes">
    <meta name="msapplication-starturl" content="/">

    <link rel="apple-touch-icon" href="{{ $logo1 . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}" />
    @if ($store->enable_pwa_store == 'on')
    <link rel="manifest" href="{{ asset('storage/uploads/customer_app/store_' . $store_settings->id . '/manifest.json') }}" />
    @endif
    @if (!empty($store->pwa_store($store->slug)->theme_color))
        <meta name="theme-color" content="{{ $store->pwa_store($store->slug)->theme_color }}" />
    @endif
    @if (!empty($store->pwa_store($store->slug)->background_color))
        <meta name="apple-mobile-web-app-status-bar" content="{{ $store->pwa_store($store->slug)->background_color }}" />
    @endif
    @foreach ($pixelScript as $script)
        <?= $script ?> @endforeach
</head>

<body class="themes-color">
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
    <header class="site-header header-style-one style-two">
        <div class="main-navigationbar">
            <div class="full-container">
                <div class="navigationbar-row row navbar nav-color">
                    <div class="col-lg-2 col-12 d-none style-dark-body">
                        <div class="logo-col">
                            <h1>
                                <a href="">
                                    <img src="{{ $s_logo . (isset($store_settings['logo']) && !empty($store_settings['logo']) ? $store_settings['logo'] : 'logo.png') . '?timestamp=' . time() }}"
                                        class="nav_tab_img">
                                </a>
                            </h1>
                        </div>
                    </div>
                    <div class="col-lg-10 col-12 floating-nav">
                        <div class="navbar-container d-flex align-items-center justify-content-between">
                            <div class="logo-col">
                                <h1>
                                    <a href="">
                                        <img src="{{ $s_logo . (isset($store_settings['logo']) && !empty($store_settings['logo']) ? $store_settings['logo'] : 'logo.png') . '?timestamp=' . time() }}"
                                            class="nav_tab_img">
                                    </a>
                                </h1>
                            </div>
                            <a href="#" class="nav-brand nav-logo">
                                <img src="{{ $s_logo . (isset($store_settings['logo']) && !empty($store_settings['logo']) ? $store_settings['logo'] : 'logo.png') . '?timestamp=' . time() }}"
                                    class="header_img grey-bg" width="200px"
                                    style="margin:15px 40px;padding: 15px 20px;border-radius: 30px;">
                            </a>
                            <a href="#" class="nav-brand nav-tagline d-none">
                                <h2 class="nav-title">{{ $store->name }}</h2>

                                <span class="sub-text">{{ $store->storeAddress($store->address) }}
                                    {{ $store->storeAddress($store->city) }} {{ $store->storeAddress($store->state) }}
                                    {{ $store->storeAddress($store->country, 'country') }}</span>
                            </a>
                            <div class="search-bar">

                                <form class="search-input" action="/action_page.php">
                                    <a href="#" class="search-icon">
                                        <img src="{{ asset('custom/images/search-icon-black-body.svg') }}"
                                            alt="#">
                                    </a>
                                    <input type="search" id="search" placeholder="Search" name="search">
                                </form>
                                @if (Utility::CustomerAuthCheck($store->slug) == true)
                                    <div class="profile-header has-item">
                                        <a href="javascript:void(0)" class="btn btn-secondary">
                                            <span class="login-text m-lbl"
                                                style="display: block;">{{ ucFirst(Auth::guard('customers')->user()->name) }}</span>
                                        </a>
                                        <div class="menu-dropdown">
                                            <ul>
                                                <li>
                                                    <a
                                                        href="{{ route('store.slug', $store->slug) }}">{{ __('My Dashboard') }}</a>
                                                </li>
                                                <li>
                                                    <a href="#" data-size="lg"
                                                        data-url="{{ route('customer.profile', [$store->slug, \Illuminate\Support\Facades\Crypt::encrypt(Auth::guard('customers')->user()->id)]) }}"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Profile') }}"
                                                        data-toggle="modal">{{ __('My Profile') }}</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" id="myproducts"
                                                        data-val="myproducts">{{ __('My Orders') }}</a>
                                                </li>
                                                <li>
                                                    @if (Utility::CustomerAuthCheck($store->slug) == false)
                                                        <a data-url="{{ route('customer.login', $store->slug) }}"
                                                            data-title="{{ __('Login') }}">{{ __('LogIn') }}</a>
                                                    @else
                                                        <a class="dropdown-item" href="#"
                                                            onclick="event.preventDefault(); document.getElementById('customer-frm-logout').submit();">{{ __('Logout') }}</a>
                                                        <form id="customer-frm-logout"
                                                            action="{{ route('customer.logout', $store->slug) }}"
                                                            method="POST" class="d-none">
                                                            {{ csrf_field() }}
                                                        </form>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @else
                                    <a data-url="{{ route('customer.login', $store->slug) }}" data-ajax-popup="true"
                                        data-title="{{ __('Login') }}" data-toggle="modal"
                                        class="btn btn-secondary  float-right ml-2 bg--gray hover-translate-y-n3 icon-font"
                                        id="login-BTN">
                                        {{ __('Log in') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--header end here-->
    <!--wrapper start here-->
    <div class="wrapper">
        <section class="home-bnner-section tabs-wrapper">
            <div class="full-container">
                <div class="d-flex home-banner-tab-row row">
                    <div class="banner-col-left col-lg-2 col-12">
                        <div class="banner-tab-left-inner">
                            <div class="mobile-only close-filter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                                    viewBox="0 0 50 50" fill="none">
                                    <path
                                        d="M27.7618 25.0008L49.4275 3.33503C50.1903 2.57224 50.1903 1.33552 49.4275 0.572826C48.6647 -0.189868 47.428 -0.189965 46.6653 0.572826L24.9995 22.2386L3.33381 0.572826C2.57102 -0.189965 1.3343 -0.189965 0.571605 0.572826C-0.191089 1.33562 -0.191186 2.57233 0.571605 3.33503L22.2373 25.0007L0.571605 46.6665C-0.191186 47.4293 -0.191186 48.666 0.571605 49.4287C0.952952 49.81 1.45285 50.0007 1.95275 50.0007C2.45266 50.0007 2.95246 49.81 3.3339 49.4287L24.9995 27.763L46.6652 49.4287C47.0465 49.81 47.5464 50.0007 48.0463 50.0007C48.5462 50.0007 49.046 49.81 49.4275 49.4287C50.1903 48.6659 50.1903 47.4292 49.4275 46.6665L27.7618 25.0008Z"
                                        fill="white"></path>
                                </svg>
                            </div>
                            <h2 class="title-category grey-text">{{ __('Categories') }}</h2>
                            <ul class="cat-tab tabs  pro_category">

                                @php
                                    $key = 0;
                                @endphp
                                @foreach ($products as $item => $product)
                                    @php
                                        $total_product = count($product);
                                    @endphp
                                    @if($total_product != 0)
                                        <li data-href="{{ $loop->iteration }}{!! str_replace(' ', '_', $item) !!}"
                                            class=" tab-link custom-list-group-item productTab {{ $key == 0 ? 'active' : '' }}"
                                            data-tab="tab-{{ $key }}">
                                            <a>
                                                {{ __($item) }}
                                            </a>
                                        </li>
                                    @endif
                                    @php
                                        $key++;
                                    @endphp
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="banner-col-right
        col-lg-10 col-12">
                        <div class="content-wrapper">
                            <div class="content-body">
                                <div class="row">
                                    <div class="col-xl-8 col-lg-12 col-12">
                                        <div class="card-header card-header-main">
                                            <div class="filter-title">
                                                <div class="mobile-only">
                                                    <svg class="icon icon-filter" aria-hidden="true"
                                                        focusable="false" role="presentation"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="none">
                                                        <path fill-rule="evenodd"
                                                            d="M4.833 6.5a1.667 1.667 0 1 1 3.334 0 1.667 1.667 0 0 1-3.334 0ZM4.05 7H2.5a.5.5 0 0 1 0-1h1.55a2.5 2.5 0 0 1 4.9 0h8.55a.5.5 0 0 1 0 1H8.95a2.5 2.5 0 0 1-4.9 0Zm11.117 6.5a1.667 1.667 0 1 0-3.334 0 1.667 1.667 0 0 0 3.334 0ZM13.5 11a2.5 2.5 0 0 1 2.45 2h1.55a.5.5 0 0 1 0 1h-1.55a2.5 2.5 0 0 1-4.9 0H2.5a.5.5 0 0 1 0-1h8.55a2.5 2.5 0 0 1 2.45-2Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <h4 class="card-title main-title">{{ __('Products') }}</h4>
                                            <div class="right-area">
                                                <div class="sort-area" id="sort_by">
                                                    <p class="card-text">{{ __('Sort by') }}: </p>
                                                    <a href="{{ route('store.slug', [$store->slug, 'grid']) }}"
                                                        class="sort-icon grid" data-val="grid" id="grid">
                                                        <svg width="12" height="12" viewBox="0 0 12 12"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M3.9 0H1.5C0.671573 0 0 0.671573 0 1.5V3.9C0 4.72843 0.671573 5.4 1.5 5.4H3.9C4.72843 5.4 5.4 4.72843 5.4 3.9V1.5C5.4 0.671573 4.72843 0 3.9 0ZM1.2 1.5C1.2 1.33431 1.33431 1.2 1.5 1.2H3.9C4.06569 1.2 4.2 1.33431 4.2 1.5V3.9C4.2 4.06569 4.06569 4.2 3.9 4.2H1.5C1.33431 4.2 1.2 4.06569 1.2 3.9V1.5ZM3.9 6.6H1.5C0.671573 6.6 0 7.27157 0 8.1V10.5C0 11.3284 0.671573 12 1.5 12H3.9C4.72843 12 5.4 11.3284 5.4 10.5V8.1C5.4 7.27157 4.72843 6.6 3.9 6.6ZM1.2 8.1C1.2 7.93432 1.33431 7.8 1.5 7.8H3.9C4.06569 7.8 4.2 7.93432 4.2 8.1V10.5C4.2 10.6657 4.06569 10.8 3.9 10.8H1.5C1.33431 10.8 1.2 10.6657 1.2 10.5V8.1ZM8.1 6.6H10.5C11.3284 6.6 12 7.27157 12 8.1V10.5C12 11.3284 11.3284 12 10.5 12H8.1C7.27157 12 6.6 11.3284 6.6 10.5V8.1C6.6 7.27157 7.27157 6.6 8.1 6.6ZM8.1 7.8C7.93432 7.8 7.8 7.93432 7.8 8.1V10.5C7.8 10.6657 7.93432 10.8 8.1 10.8H10.5C10.6657 10.8 10.8 10.6657 10.8 10.5V8.1C10.8 7.93432 10.6657 7.8 10.5 7.8H8.1ZM10.5 0H8.1C7.27157 0 6.6 0.671573 6.6 1.5V3.9C6.6 4.72843 7.27157 5.4 8.1 5.4H10.5C11.3284 5.4 12 4.72843 12 3.9V1.5C12 0.671573 11.3284 0 10.5 0ZM7.8 1.5C7.8 1.33431 7.93432 1.2 8.1 1.2H10.5C10.6657 1.2 10.8 1.33431 10.8 1.5V3.9C10.8 4.06569 10.6657 4.2 10.5 4.2H8.1C7.93432 4.2 7.8 4.06569 7.8 3.9V1.5Z"
                                                                fill="#fff"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('store.slug', [$store->slug, 'list']) }}"
                                                        class="sort-icon list" data-val="list" id="h-grid">
                                                        <svg width="12" height="12" viewBox="0 0 12 12"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M-5.24537e-08 10.8C-2.34843e-08 11.4627 0.537258 12 1.2 12L10.8 12C11.4627 12 12 11.4627 12 10.8L12 7.8C12 7.13726 11.4627 6.6 10.8 6.6L1.2 6.6C0.537259 6.6 -2.12557e-07 7.13726 -1.83588e-07 7.8L-5.24537e-08 10.8ZM1.2 10.8L1.2 7.8L10.8 7.8L10.8 10.8L1.2 10.8ZM-3.40949e-07 4.2C-3.11979e-07 4.86274 0.537258 5.4 1.2 5.4L10.8 5.4C11.4627 5.4 12 4.86274 12 4.2L12 1.2C12 0.53726 11.4627 4.52622e-07 10.8 4.81591e-07L1.2 9.01221e-07C0.537258 9.3019e-07 -5.01052e-07 0.537258 -4.72083e-07 1.2L-3.40949e-07 4.2ZM1.2 4.2L1.2 1.2L10.8 1.2L10.8 4.2L1.2 4.2Z"
                                                                fill="#838383"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="filter-by">
                                                    <label class="filter-icon">
                                                        <svg width="14" height="14" viewBox="0 0 11 11"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M7.2778 9.77778C7.2778 10.4528 6.73059 11 6.05558 11L4.94447 11C4.62031 11 4.30944 10.8712 4.08022 10.642C3.85101 10.4128 3.72224 10.1019 3.72224 9.77778L3.72224 5.40054L0.364524 2.09294C0.011161 1.74485 -0.0967854 1.21772 0.0913067 0.758747C0.2794 0.299778 0.726229 -5.55075e-08 1.22224 8.54804e-07L9.7778 1.06853e-07C10.2738 6.34903e-08 10.7206 0.299777 10.9087 0.758746C11.0968 1.21772 10.9889 1.74485 10.6355 2.09294L7.2778 5.40054L7.2778 9.77778ZM4.8598 4.83438C4.91475 4.9276 4.94447 5.03461 4.94447 5.14472L4.94447 9.77778L6.05558 9.77778L6.05558 5.14472C6.05558 5.03461 6.08529 4.9276 6.14024 4.83438C6.16697 4.78903 6.19967 4.74695 6.23783 4.70936L9.7778 1.22222L1.22224 1.22222L4.76222 4.70936C4.80037 4.74695 4.83307 4.78903 4.8598 4.83438Z"
                                                                fill="#838383"></path>
                                                        </svg>
                                                        <p>{{ __('Filter by') }}:</p>
                                                    </label>
                                                    <div class="nice-select" tabindex="0">
                                                        <span class="current">{{ __('Price') }} </span>
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
                                        <div class="tabs-container">
                                            <div id="product_view">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 col-12 right-card">

                                        <div class="card">
                                            <h4 class="card-header card-title coupon-title">{{ __('Coupon') }}</h4>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input type="text" id="stripe_coupon" name="coupon"
                                                        class="form-control coupon hidd_val"
                                                        placeholder="{{ __('Enter Coupon Code') }}" value="">
                                                    <input type="hidden" name="coupon"
                                                        class="form-control hidden_coupon" value="">
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group product-detail apply-stripe-btn-coupon">
                                                    <a href="#"
                                                        class="btn btn-secondary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="card cart-card cart-dispay" id="card-summary">
                                            <div class="card-header">
                                                <h4 class="card-title coupon-title coupon-title"
                                                    style="color: white;">
                                                    {{ __('Cart') }}</h4>
                                            </div>
                                            @if (!empty($pro_cart) && count($pro_cart['products']) > 0)
                                                @php
                                                    $sub_tax = 0;
                                                    $total = 0;
                                                    $sub_total = 0;
                                                @endphp

                                                <div class="order-historycontent">
                                                    <table class="cart-tble">
                                                        <tbody>
                                                            @foreach ($pro_cart['products'] as $key => $product)
                                                                @if ($product['variant_id'] != 0)
                                                                    <tr
                                                                        id="product-variant-id-{{ $product['variant_id'] }}">
                                                                        <td data-label="Product">
                                                                            <a href="#" class="pro-img-cart">
                                                                                <img
                                                                                    src="{{ asset($product['image']) }}">
                                                                            </a>
                                                                        </td>
                                                                        <td data-label="Name">
                                                                            <a
                                                                                href="#">{{ $product['product_name'] . ' - ' . $product['variant_name'] }}</a>
                                                                        </td>
                                                                        @php
                                                                            $total_tax = 0;
                                                                        @endphp
                                                                        <div class="title_name">

                                                                            <td data-label="quantity"
                                                                                id="product-variant-id-{{ $product['variant_id'] }}">
                                                                                <div class="qty-spinner"
                                                                                    data-id="{{ $key }}">
                                                                                    <button type="button"
                                                                                        class="quantity-decrement product_qty"
                                                                                        data-id="{{ $product['id'] }}"
                                                                                        type="submit"
                                                                                        value="{{ $product['quantity'] }}"
                                                                                        data-option="decrease">


                                                                                        <svg width="12"
                                                                                            height="2"
                                                                                            viewBox="0 0 12 2"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                d="M0 0.251343V1.74871H12V0.251343H0Z"
                                                                                                fill="#61AFB3"></path>
                                                                                        </svg>

                                                                                    </button>
                                                                                    <input type="text"
                                                                                        class="quantity pro_variant_id product_qty_input"
                                                                                        data-id="{{ $product['variant_id'] }}"
                                                                                        data-cke-saved-name="quantity"
                                                                                        name="quantity"
                                                                                        id="product_qty"
                                                                                        id="product_qty_input"
                                                                                        value="{{ $product['quantity'] }}">
                                                                                    <button type="button"
                                                                                        class="quantity-increment product_qty product_qty_input"
                                                                                        data-id="{{ $product['id'] }}"
                                                                                        type="submit"
                                                                                        value="{{ $product['quantity'] }}"
                                                                                        data-option="increase">
                                                                                        <svg width="12"
                                                                                            height="12"
                                                                                            viewBox="0 0 12 12"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                d="M6.74868 5.25132V0H5.25132V5.25132H0V6.74868H5.25132V12H6.74868V6.74868H12V5.25132H6.74868Z"
                                                                                                fill="#61AFB3"></path>
                                                                                        </svg>
                                                                                    </button>
                                                                                </div>
                                                                                @if ($product['tax'] > 0)
                                                                                    @foreach ($product['tax'] as $k => $tax)
                                                                                        @php
                                                                                            $sub_tax = ($product['variant_price'] * $product['quantity'] * $tax['tax']) / 100;
                                                                                            $total_tax += $sub_tax;
                                                                                        @endphp
                                                                                        <small
                                                                                            class="title_name ml-0 variant_tax_{{ $k }}">
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
                                                                            </td>
                                                                            <td class="price-spin">
                                                                                <b class="subtotal"
                                                                                    id="product-variant-id-{{ $product['variant_id'] }}">
                                                                                    {{ \App\Models\Utility::priceFormat($product['variant_price'] * $product['quantity']) }}</b>
                                                                                {!! Form::open([
                                                                                    'method' => 'DELETE',
                                                                                    'route' => ['delete.cart_item', $store->slug, $product['id'], $product['variant_id']],
                                                                                ]) !!}
                                                                                <a href="#!"
                                                                                    class="remove-btn show_confirm"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="{{ __('Delete') }}">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        viewBox="0 0 16 16"
                                                                                        aria-hidden="true"
                                                                                        focusable="false"
                                                                                        role="presentation"
                                                                                        class="icon icon-remove">
                                                                                        <path
                                                                                            d="M14 3h-3.53a3.07 3.07 0 00-.6-1.65C9.44.82 8.8.5 8 .5s-1.44.32-1.87.85A3.06 3.06 0 005.53 3H2a.5.5 0 000 1h1.25v10c0 .28.22.5.5.5h8.5a.5.5 0 00.5-.5V4H14a.5.5 0 000-1zM6.91 1.98c.23-.29.58-.48 1.09-.48s.85.19 1.09.48c.2.24.3.6.36 1.02h-2.9c.05-.42.17-.78.36-1.02zm4.84 11.52h-7.5V4h7.5v9.5z"
                                                                                            fill="currentColor"></path>
                                                                                        <path
                                                                                            d="M6.55 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5zM9.45 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5z"
                                                                                            fill="currentColor"></path>
                                                                                    </svg>
                                                                                </a>
                                                                                {!! Form::close() !!}
                                                                            </td>
                                                                        </div>
                                                                    </tr>
                                                                @else
                                                                    <tr id="product-id-{{ $product['product_id'] }}">
                                                                        <td data-label="Product">
                                                                            <a href="#" class="pro-img-cart">
                                                                                <img
                                                                                    src="{{ asset($product['image']) }}">
                                                                            </a>
                                                                        </td>
                                                                        <td data-label="Name">
                                                                            <a
                                                                                href="#">{{ $product['product_name'] }}</a>
                                                                        </td>
                                                                        @php
                                                                            $total_tax = 0;
                                                                        @endphp
                                                                        <td data-label="quantity"
                                                                            id="product-id-{{ $product['product_id'] }}">
                                                                            <div class="qty-spinner"
                                                                                data-id="{{ $key }}">
                                                                                <button type="submit"
                                                                                    class="quantity-decrement product_qty"
                                                                                    data-id="{{ $product['id'] }}"
                                                                                    value="{{ $product['quantity'] }}"
                                                                                    data-option="decrease">
                                                                                    <svg width="12" height="2"
                                                                                        viewBox="0 0 12 2"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M0 0.251343V1.74871H12V0.251343H0Z"
                                                                                            fill="#61AFB3"></path>
                                                                                    </svg>
                                                                                </button>

                                                                                <input type="text"
                                                                                    class="quantity pro_variant_id product_qty_input"
                                                                                    add_to_cart_variant="pro_variant_id"
                                                                                    data-id="{{ $product['variant_id'] }}"
                                                                                    data-cke-saved-name="quantity"
                                                                                    name="quantity" id="product_qty"
                                                                                    id="product_qty_input"
                                                                                    value="{{ $product['quantity'] }}"
                                                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">

                                                                                <button type="submit"
                                                                                    class="quantity-increment product_qty"
                                                                                    data-id="{{ $product['id'] }}"
                                                                                    value="{{ $product['quantity'] }}"
                                                                                    data-option="increase">
                                                                                    <svg width="12" height="12"
                                                                                        viewBox="0 0 12 12"
                                                                                        fill="none"
                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                        <path
                                                                                            d="M6.74868 5.25132V0H5.25132V5.25132H0V6.74868H5.25132V12H6.74868V6.74868H12V5.25132H6.74868Z"
                                                                                            fill="#61AFB3"></path>
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
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
                                                                        </td>
                                                                        <td data-label="Total">
                                                                            <b class="subtotal"
                                                                                id="product-id-{{ $product['product_id'] }}">
                                                                                {{ \App\Models\Utility::priceFormat($product['price'] * $product['quantity']) }}</b>
                                                                            {!! Form::open([
                                                                                'method' => 'DELETE',
                                                                                'route' => ['delete.cart_item', $store->slug, $product['id'], $product['variant_id']],
                                                                            ]) !!}
                                                                            <a href="#!"
                                                                                class="remove-btn show_confirm"
                                                                                data-bs-toggle="tooltip"
                                                                                data-bs-placement="top"
                                                                                title="{{ __('Delete') }}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    viewBox="0 0 16 16"
                                                                                    aria-hidden="true"
                                                                                    focusable="false"
                                                                                    role="presentation"
                                                                                    class="icon icon-remove">
                                                                                    <path
                                                                                        d="M14 3h-3.53a3.07 3.07 0 00-.6-1.65C9.44.82 8.8.5 8 .5s-1.44.32-1.87.85A3.06 3.06 0 005.53 3H2a.5.5 0 000 1h1.25v10c0 .28.22.5.5.5h8.5a.5.5 0 00.5-.5V4H14a.5.5 0 000-1zM6.91 1.98c.23-.29.58-.48 1.09-.48s.85.19 1.09.48c.2.24.3.6.36 1.02h-2.9c.05-.42.17-.78.36-1.02zm4.84 11.52h-7.5V4h7.5v9.5z"
                                                                                        fill="currentColor"></path>
                                                                                    <path
                                                                                        d="M6.55 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5zM9.45 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5z"
                                                                                        fill="currentColor"></path>
                                                                                </svg>
                                                                            </a>
                                                                            {!! Form::close() !!}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="invoice-details">
                                                        <ul class="invoice-list">
                                                            <li class="invoice-detail">
                                                                <div class="invoice-title">
                                                                    {{ __('Subtotal') }}
                                                                </div>
                                                                <div class="invoice-amt font-weight-600 sub_total_price"
                                                                    data-value="{{ $total }}">
                                                                    {{ App\Models\Utility::priceFormat($sub_total) }}
                                                                </div>
                                                            </li>
                                                            <li class="invoice-detail">
                                                                <div class="invoice-title">
                                                                    {{ __('Coupon') }}
                                                                </div>
                                                                <div class="invoice-amt dicount_price">
                                                                    {{ __('0.00') }}
                                                                </div>
                                                            </li>
                                                            @if (!empty($pro_cart) && count($pro_cart['products']) > 0)
                                                                <li class="invoice-detail">
                                                                    <div class="invoice-title total-title">
                                                                        {{ __('Shipping') }}
                                                                    </div>
                                                                    <div class="invoice-amt">
                                                                        <span
                                                                            class="invoice-amt shipping_price">{{ __('0.00') }}</span>
                                                                    </div>
                                                                </li>
                                                            @endif
                                                            @if (!empty($taxArr))
                                                                @foreach ($taxArr['tax'] as $k => $tax)
                                                                    @if ($product['variant_id'] != 0)
                                                                        <li class="invoice-detail"
                                                                            id="product-variant-id-{{ $product['variant_id'] }}">
                                                                        @else
                                                                        <li class="invoice-detail"
                                                                            id="product-id-{{ $product['product_id'] }}">
                                                                    @endif
                                                                    {{-- <li class="invoice-detail"> --}}
                                                                    <div class="invoice-title total-title ">
                                                                        {{ $tax }}
                                                                    </div>
                                                                    <div
                                                                        class="invoice-amt total_tax_{{ $k }}">
                                                                        {{ \App\Models\Utility::priceFormat($taxArr['rate'][$k]) }}
                                                                    </div>
                                                                    </li>
                                                                @endforeach
                                                            @endif


                                                            <li class="invoice-detail">
                                                                <div class="invoice-title total-title ">
                                                                    <h4>{{ __('Total (Incl Tax)') }}</h4>
                                                                </div>
                                                                <div class="invoice-amt total-amount"
                                                                    data-original="$0.00">
                                                                    <input type="hidden" class="product_total"
                                                                        value="{{ $total }}">
                                                                    <input type="hidden" class="total_pay_price"
                                                                        value="{{ App\Models\Utility::priceFormat($total) }}">
                                                                    <div class="final_total_price pro_total_price"
                                                                        id="displaytotal"
                                                                        data-original="{{ \App\Models\Utility::priceFormat(!empty($total) ? $total : 0) }}">
                                                                        {{ App\Models\Utility::priceFormat($total) }}
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="card-footer">
                                                    <div class="invoice-details">
                                                        <ul class="invoice-list">
                                                            <li class="invoice-detail">
                                                                <div class="invoice-title">
                                                                    {{ __('Subtotal') }}
                                                                </div>
                                                                <div class="invoice-amt font-weight-600">
                                                                    0.00
                                                                </div>
                                                            </li>
                                                            <li class="invoice-detail">
                                                                <div class="invoice-title total-title ">
                                                                    {{ __('Coupon') }}
                                                                </div>
                                                                <div class="invoice-amt">
                                                                    0.00
                                                                </div>
                                                            </li>
                                                            @if (!empty($pro_cart) && count($pro_cart['products']) > 0)
                                                                <li class="invoice-detail">
                                                                    <div class="invoice-title total-title">
                                                                        {{ __('Shipping') }}
                                                                    </div>
                                                                    <div class="invoice-amt">
                                                                        <span
                                                                            class="invoice-amt shipping_price">0.00</span>
                                                                    </div>
                                                                </li>
                                                            @endif
                                                            @if (!empty($taxArr))
                                                                @foreach ($taxArr['tax'] as $k => $tax)
                                                                    <li class="invoice-detail">
                                                                        <div class="invoice-title total-title ">
                                                                            {{ $tax }}
                                                                        </div>
                                                                        <div class="invoice-amt">
                                                                            {{ \App\Models\Utility::priceFormat($taxArr['rate'][$k]) }}
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            @endif


                                                            <li class="invoice-detail">
                                                                <div class="invoice-title total-title ">
                                                                    <h4>{{ __('Total (Incl Tax)') }}</h4>
                                                                </div>
                                                                <div class="invoice-amt total-amount final_total_price pro_total_price"
                                                                    id="displaytotal"
                                                                    data-original="{{ \App\Models\Utility::priceFormat(!empty($total) ? $total : 0) }}">
                                                                    0.00
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="card delivery-card">
                                            <div class="card-header">
                                                <h4 class="card-title coupon-title">{{ __('Delivery Details') }}</h4>
                                            </div>
                                            <div class="card-body detail-form">
                                                <div class="form-group">
                                                    {{ Form::label('name', __('Name'), ['class' => 'form-control-label']) }}
                                                    {{ Form::text('name', old('name'), ['class' => 'active fname', 'required' => 'required']) }}
                                                </div>
                                                <div class="form-group">
                                                    {{ Form::label('email', __('Email'), ['class' => 'form-control-label']) }}
                                                    {{ Form::email('email', old('email'), ['class' => 'active email', 'required' => 'required']) }}
                                                </div>
                                                <div class="form-group">
                                                    {{ Form::label('phone', __('Phone'), ['class' => 'form-control-label']) }}
                                                    {{ Form::text('phone', old('phone'), ['class' => 'active phone', 'required' => 'required']) }}
                                                </div>
                                                @if (!empty($store->custom_field_title_1))
                                                    <div class="form-group">
                                                        {{ Form::label('custom_field_title_1', $store->custom_field_title_1, ['class' => 'form-control-label']) }}
                                                        {{ Form::text('custom_field_title_1', old('custom_field_title_1'), ['class' => 'active custom_field_title_1']) }}
                                                    </div>
                                                @endif
                                                @if (!empty($store->custom_field_title_2))
                                                    <div class="form-group">
                                                        {{ Form::label('custom_field_title_2', $store->custom_field_title_2, ['class' => 'form-control-label']) }}
                                                        {{ Form::text('custom_field_title_2', old('custom_field_title_2'), ['class' => 'active custom_field_title_2']) }}
                                                    </div>
                                                @endif
                                                @if (!empty($store->custom_field_title_3))
                                                    <div class="form-group">
                                                        {{ Form::label('custom_field_title_3', $store->custom_field_title_3, ['class' => 'form-control-label']) }}
                                                        {{ Form::text('custom_field_title_3', old('custom_field_title_3'), ['class' => 'active custom_field_title_3']) }}
                                                    </div>
                                                @endif
                                                @if (!empty($store->custom_field_title_4))
                                                    <div class="form-group">
                                                        {{ Form::label('custom_field_title_4', $store->custom_field_title_4, ['class' => 'form-control-label']) }}
                                                        {{ Form::text('custom_field_title_4', old('custom_field_title_4'), ['class' => 'active custom_field_title_4']) }}
                                                    </div>
                                                @endif

                                                <div class="form-group">
                                                    {{ Form::label('billingaddress', __('Address line 1'), ['class' => 'form-control-label']) }}
                                                    {{ Form::text('billing_address', old('billing_address'), ['class' => 'active billing_address', 'required' => 'required']) }}
                                                </div>
                                                <div class="form-group">
                                                    {{ Form::label('shipping_address', __('Address line 2'), ['class' => 'form-control-label']) }}
                                                    {{ Form::text('shipping_address', old('shipping_address'), ['class' => 'active shipping_address']) }}
                                                </div>
                                            </div>
                                        </div>
                                        @if (!empty($pro_cart) && count($pro_cart['products']) > 0)
                                            {{-- @dd($pro_cart['products']) --}}
                                            @if ($store->enable_shipping == 'on')
                                                @if (count($locations) != 1)
                                                    @if (count($shippings) != 0)
                                                        <div class="card">
                                                            <div class="form-group">
                                                                <h4 class="card-header card-title coupon-title">
                                                                    {{ __('Shipping Location') }}</h4>
                                                            </div>
                                                            <div class="card-body">
                                                                {{ Form::select('location_id', $locations, null, ['class' => 'active acticard-titleve form-control change_location', 'required' => 'required']) }}
                                                            </div>
                                                            <div class="card-body" id="location_hide"
                                                                style="display: none">
                                                                <h4 class="card-title coupon-title coupon-title">
                                                                    <h6>{{ __('Select Shipping') }}</h6>
                                                                </h4>

                                                                <div class="p-2" id="shipping_location_content">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                        <div class="card instruction-card">
                                            <div class="card-header">
                                                <h4 class="card-title coupon-title">{{ __('Order Notes') }}</h4>
                                            </div>
                                            <div class="card-body">
                                                {{ Form::textarea('special_instruct', null, ['class' => 'special_instruct form-control', 'rows' => 3]) }}
                                                {{-- <textarea class="special_instruct form-control" rows="3" name="special_instruct" cols="50"></textarea> --}}
                                            </div>
                                            <div class="card-footer">

                                            </div>
                                        </div>

                                        <div class="order-md-1 text-center" style="border: 0; padding:0;">
                                            @if (
                                                $store_settings['is_checkout_login_required'] == null ||
                                                    ($store_settings['is_checkout_login_required'] == 'off' && !Auth::guard('customers')->user()))
                                                <a href="#" class="btn checkoutBtn" data-toggle="modal" id="checkoutBtn"
                                                    data-target="#checkoutModal" data-title="CheckOut Model">
                                                    <span class="text-primary">{{ __('Proceed to checkout') }}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                                                        data-name="Layer 1" viewBox="0 0 24 24" width="512"
                                                        height="512">
                                                        <path
                                                            d="M23.297,9.034c-.57-.657-1.396-1.034-2.267-1.034h-.086C20.445,3.506,16.625,0,12,0S3.555,3.506,3.056,8h-.056c-.87,0-1.695,.377-2.266,1.034S-.093,10.562,.03,11.425l1.061,7.424c.42,2.937,2.974,5.151,5.94,5.151h9.969c2.966,0,5.52-2.215,5.94-5.151l1.061-7.424c.123-.862-.134-1.733-.704-2.391ZM12,2c3.52,0,6.441,2.613,6.928,6H5.072c.487-3.387,3.408-6,6.928-6Zm10.021,9.142l-1.061,7.424c-.28,1.958-1.982,3.435-3.96,3.435H7.031c-1.979,0-3.681-1.477-3.96-3.435l-1.061-7.424c-.042-.291,.042-.574,.234-.797,.193-.223,.461-.345,.755-.345H21.03c.294,0,.562,.122,.756,.345,.192,.223,.276,.506,.234,.797Zm-9.021,1.858v6c0,.553-.447,1-1,1s-1-.447-1-1v-6c0-.553,.447-1,1-1s1,.447,1,1Zm5,0v6c0,.553-.447,1-1,1s-1-.447-1-1v-6c0-.553,.447-1,1-1s1,.447,1,1Zm-10,0v6c0,.553-.447,1-1,1s-1-.447-1-1v-6c0-.553,.447-1,1-1s1,.447,1,1Z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="#footer" class="btn checkoutBtn authUser">
                                                    <span class="text-primary">{{ __('Proceed to checkout') }}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                                                        data-name="Layer 1" viewBox="0 0 24 24" width="512"
                                                        height="512">
                                                        <path
                                                            d="M23.297,9.034c-.57-.657-1.396-1.034-2.267-1.034h-.086C20.445,3.506,16.625,0,12,0S3.555,3.506,3.056,8h-.056c-.87,0-1.695,.377-2.266,1.034S-.093,10.562,.03,11.425l1.061,7.424c.42,2.937,2.974,5.151,5.94,5.151h9.969c2.966,0,5.52-2.215,5.94-5.151l1.061-7.424c.123-.862-.134-1.733-.704-2.391ZM12,2c3.52,0,6.441,2.613,6.928,6H5.072c.487-3.387,3.408-6,6.928-6Zm10.021,9.142l-1.061,7.424c-.28,1.958-1.982,3.435-3.96,3.435H7.031c-1.979,0-3.681-1.477-3.96-3.435l-1.061-7.424c-.042-.291,.042-.574,.234-.797,.193-.223,.461-.345,.755-.345H21.03c.294,0,.562,.122,.756,.345,.192,.223,.276,.506,.234,.797Zm-9.021,1.858v6c0,.553-.447,1-1,1s-1-.447-1-1v-6c0-.553,.447-1,1-1s1,.447,1,1Zm5,0v6c0,.553-.447,1-1,1s-1-.447-1-1v-6c0-.553,.447-1,1-1s1,.447,1,1Zm-10,0v6c0,.553-.447,1-1,1s-1-.447-1-1v-6c0-.553,.447-1,1-1s1,.447,1,1Z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>

                                        <div class="order-md-2 card" id="asGuest">
                                            <div
                                                class="form-group btn-leb d-flex align-items-center justify-content-between">
                                                @if ($store->enable_whatsapp == 'on')
                                                    <button type="submit" class="btn whatsap-btn payment"
                                                        id="owner-whatsapp" data-toggle="modal"
                                                        data-target="#checkoutModal">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                                                            id="Capa_1" x="0px" y="0px"
                                                            viewBox="0 0 52 52"
                                                            style="enable-background:new 0 0 52 52;"
                                                            xml:space="preserve">
                                                            <g>
                                                                <g>
                                                                    <path
                                                                        d="M26,0C11.663,0,0,11.663,0,26c0,4.891,1.359,9.639,3.937,13.762C2.91,43.36,1.055,50.166,1.035,50.237    c-0.096,0.352,0.007,0.728,0.27,0.981c0.263,0.253,0.643,0.343,0.989,0.237L12.6,48.285C16.637,50.717,21.26,52,26,52    c14.337,0,26-11.663,26-26S40.337,0,26,0z M26,50c-4.519,0-8.921-1.263-12.731-3.651c-0.161-0.101-0.346-0.152-0.531-0.152    c-0.099,0-0.198,0.015-0.294,0.044l-8.999,2.77c0.661-2.413,1.849-6.729,2.538-9.13c0.08-0.278,0.035-0.578-0.122-0.821    C3.335,35.173,2,30.657,2,26C2,12.767,12.767,2,26,2s24,10.767,24,24S39.233,50,26,50z">
                                                                    </path>
                                                                    <path
                                                                        d="M42.985,32.126c-1.846-1.025-3.418-2.053-4.565-2.803c-0.876-0.572-1.509-0.985-1.973-1.218    c-1.297-0.647-2.28-0.19-2.654,0.188c-0.047,0.047-0.089,0.098-0.125,0.152c-1.347,2.021-3.106,3.954-3.621,4.058    c-0.595-0.093-3.38-1.676-6.148-3.981c-2.826-2.355-4.604-4.61-4.865-6.146C20.847,20.51,21.5,19.336,21.5,18    c0-1.377-3.212-7.126-3.793-7.707c-0.583-0.582-1.896-0.673-3.903-0.273c-0.193,0.039-0.371,0.134-0.511,0.273    c-0.243,0.243-5.929,6.04-3.227,13.066c2.966,7.711,10.579,16.674,20.285,18.13c1.103,0.165,2.137,0.247,3.105,0.247    c5.71,0,9.08-2.873,10.029-8.572C43.556,32.747,43.355,32.331,42.985,32.126z M30.648,39.511    c-10.264-1.539-16.729-11.708-18.715-16.87c-1.97-5.12,1.663-9.685,2.575-10.717c0.742-0.126,1.523-0.179,1.849-0.128    c0.681,0.947,3.039,5.402,3.143,6.204c0,0.525-0.171,1.256-2.207,3.293C17.105,21.48,17,21.734,17,22c0,5.236,11.044,12.5,13,12.5    c1.701,0,3.919-2.859,5.182-4.722c0.073,0.003,0.196,0.028,0.371,0.116c0.36,0.181,0.984,0.588,1.773,1.104    c1.042,0.681,2.426,1.585,4.06,2.522C40.644,37.09,38.57,40.701,30.648,39.511z">
                                                                    </path>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                        <span>{{ __('Order on WhatsApp') }}</span>
                                                    </button>
                                                @endif

                                                @if ($store->enable_telegram == 'on')
                                                    <button type="submit" class="btn telegram-btn"
                                                        id="owner-telegram">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xodm="http://www.corel.com/coreldraw/odm/2003"
                                                            clip-rule="evenodd" fill-rule="evenodd" height="512"
                                                            image-rendering="optimizeQuality"
                                                            shape-rendering="geometricPrecision"
                                                            text-rendering="geometricPrecision" viewBox="0 0 512 512"
                                                            width="512">
                                                            <g id="Layer_x0020_1">
                                                                <path
                                                                    d="m256 .09c141.33 0 255.91 114.58 255.91 255.91s-114.58 255.91-255.91 255.91-255.91-114.58-255.91-255.91 114.58-255.91 255.91-255.91zm85.72 351.11c-.02.05-7.42 18.52-27.27 9.86-10.79-4.72-77.95-59.44-92.97-70.94 29.26-26.3 58.52-52.6 87.76-78.93l-109.82 69.74c-17.97-6.05-35.95-12.06-53.9-18.15-16.01-5.14-17.84-22.95.06-29.73l208.53-81.82c2-.85 25.22-9.68 25.22 10.96zm-11.08-4.15 36.84-185.76c-.62-2.75-8.87.83-8.89.84l-209.03 81.98c-6.53 2.17-6.69 5.23-.18 7.55l48.44 16.31 130.36-82.76c4.43-2.7 11.11-4.61 14.98.04 3.91 4.71.63 10.5-3.17 14.05-3.76 3.51-77.52 69.83-100.07 90.1l79.85 61.12c7.01 2.71 10.1-1.99 10.87-3.47zm-74.64-335.14c-134.81 0-244.1 109.28-244.1 244.09s109.29 244.09 244.1 244.09 244.1-109.28 244.1-244.09-109.29-244.09-244.1-244.09z"
                                                                    fill-rule="nonzero" />
                                                            </g>
                                                        </svg>
                                                        <span>{{ __('Order on Telegram') }}</span>
                                                    </button>
                                                @endif
                                            </div>


                                            <div class="btn-leb">
                                                <div class="row wp-btn-wrapper">
                                                    
                                                    @if ($store['enable_cod'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <button type="submit" class="btn " id="cash_on_delivery">
                                                                <span>{{ __('Order on COD') }}</span>
                                                            </button>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_stripe_enabled']) && $store_payments['is_stripe_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <div class="card-btn" id="paymentsBtn">
                                                                <form role="form"
                                                                    action="{{ route('stripe.post', $store->slug) }}"
                                                                    method="post" class="require-validation"
                                                                    id="payment-form">
                                                                    @csrf
                                                                    <input type="hidden" name="type"
                                                                        class="customer_type">
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
                                                                    <input type="hidden" name="product"
                                                                        class="customer_product">
                                                                    <input type="hidden" name="order_id"
                                                                        class="customer_order_id">
                                                                    <input type="hidden" name="name"
                                                                        class="customer_name">
                                                                    <input type="hidden" name="email"
                                                                        class="customer_email">
                                                                    <input type="hidden" name="phone"
                                                                        class="customer_phone">
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
                                                                    <button type="submit" class="btn" id="owner-stripe">
                                                                        {{ __('Pay via Stripe') }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_paypal_enabled']) && $store_payments['is_paypal_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <div class="card-btn pay_online_btn">
                                                                <form method="POST"
                                                                    action="{{ route('pay.with.paypal', $store->slug) }}"
                                                                    id="payment-paypal-form">
                                                                    @csrf
                                                                    <input type="hidden" name="type"
                                                                        class="customer_type">
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
                                                                    <input type="hidden" name="product"
                                                                        class="customer_product">
                                                                    <input type="hidden" name="order_id"
                                                                        class="customer_order_id">
                                                                    <input type="hidden" name="name"
                                                                        class="customer_name">
                                                                    <input type="hidden" name="email"
                                                                        class="customer_email">
                                                                    <input type="hidden" name="phone"
                                                                        class="customer_phone">
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
                                                                    <button type="submit" class="btn" id="owner-paypal">
                                                                        {{ __('Pay via PayPal') }}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @php
                                                        $total_price_1 = \App\Models\Utility::priceFormat(!empty($total) ? $total : 0);
                                                        $toal_price_1 = str_replace(' ', '', str_replace(',', '', str_replace($store->currency, '', $total_price_1)));
                                                    @endphp

                                                    @if (isset($store_payments['is_paystack_enabled']) && $store_payments['is_paystack_enabled'] == 'on')
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
                                                        <div class="col-sm-6 col-12">
                                                            <div class="card-btn">
                                                                <button type="submit" onclick="payWithPaystack()"
                                                                    id="btnclick" class="btn">
                                                                    {{ __('Pay via Paystack') }}
                                                                </button>
                                                            </div>
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
                                                            <div class="card-btn">
                                                                <button type="submit" onclick="payWithRave()"
                                                                    class="btn">
                                                                    {{ __('Pay via Flutterwave') }}
                                                                </button>
                                                            </div>
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
                                                            <div class="card-btn">
                                                                <button type="submit" onclick="payRazorPay()"
                                                                    class="btn">
                                                                    {{ __('Pay via Razorpay') }}
                                                                </button>
                                                            </div>
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
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
                                                                @php
                                                                    $skrill_data = [
                                                                        'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                                                        'user_id' => 'user_id',
                                                                        'amount' => 'amount',
                                                                        'currency' => 'currency',
                                                                    ];
                                                                    session()->put('skrill_data', $skrill_data);
                                                                @endphp
                                                                <button type="submit" id="owner-paytm" class="btn">
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
                                                            <div class="card-btn">
                                                                <button type="submit" onclick="payMercado()" class="btn">
                                                                    {{ __('Pay via Mercado Pago') }}
                                                                </button>
                                                            </div>
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
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
                                                                <button type="submit" id="owner-mollie" class="btn">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
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
                                                                <button type="submit" id="owner-skrill" class="btn">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
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
                                                                <button type="submit" id="owner-coingate" class="btn">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
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
                                                                <button type="submit" id="owner-paymentwall"
                                                                    class="btn">
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
                                                                method="post" class="require-validation"
                                                                id="payfast-form">
                                                                <div class="card-btn">
                                                                    <div id="get-payfast-inputs"></div>
                                                                    <input type="hidden" name="order_id" id="order_id"
                                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($order_id) }}">
                                                                    <button type="button" class="btn"
                                                                        onclick="payPayfast()"
                                                                        id="payfast-get-status">{{ __('Pay via Payfast') }}</button>
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
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-toyyibpay">
                                                                    {{ __('Pay via ToyyibPay') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
    
                                                    @if ($store['enable_bank'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <div class="payment-method card-btn">
                                                                <div class="upload-btn-wrapper">
                                                                    <form method="POST"
                                                                        action="{{ route('user.bank_transfer', $store->slug) }}"
                                                                        class="payment-method-form" id="bank_transfer_form"
                                                                        enctype="multipart/form-data">
                                                                        @csrf
                                                                        <label for="bank_transfer_invoice"
                                                                            class="file-upload btn">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="17" height="17"
                                                                                viewBox="0 0 17 17" fill="none">
                                                                                <path fill-rule="evenodd"
                                                                                    clip-rule="evenodd"
                                                                                    d="M6.67952 7.2448C6.69833 7.59772 6.42748 7.89908 6.07456 7.91789C5.59289 7.94357 5.21139 7.97498 4.91327 8.00642C4.51291 8.04864 4.26965 8.29456 4.22921 8.64831C4.17115 9.15619 4.12069 9.92477 4.12069 11.0589C4.12069 12.193 4.17115 12.9616 4.22921 13.4695C4.26972 13.8238 4.51237 14.0691 4.91213 14.1112C5.61223 14.1851 6.76953 14.2586 8.60022 14.2586C10.4309 14.2586 11.5882 14.1851 12.2883 14.1112C12.6881 14.0691 12.9307 13.8238 12.9712 13.4695C13.0293 12.9616 13.0798 12.193 13.0798 11.0589C13.0798 9.92477 13.0293 9.15619 12.9712 8.64831C12.9308 8.29456 12.6875 8.04864 12.2872 8.00642C11.9891 7.97498 11.6076 7.94357 11.1259 7.91789C10.773 7.89908 10.5021 7.59772 10.5209 7.2448C10.5397 6.89187 10.8411 6.62103 11.194 6.63984C11.695 6.66655 12.0987 6.69958 12.4214 6.73361C13.3713 6.8338 14.1291 7.50771 14.2428 8.50295C14.3077 9.07016 14.3596 9.88879 14.3596 11.0589C14.3596 12.229 14.3077 13.0476 14.2428 13.6148C14.1291 14.6095 13.3732 15.2837 12.4227 15.384C11.6667 15.4638 10.4629 15.5384 8.60022 15.5384C6.73752 15.5384 5.5337 15.4638 4.77779 15.384C3.82728 15.2837 3.07133 14.6095 2.95763 13.6148C2.89279 13.0476 2.84082 12.229 2.84082 11.0589C2.84082 9.88879 2.89279 9.07016 2.95763 8.50295C3.0714 7.50771 3.82911 6.8338 4.77903 6.73361C5.10175 6.69958 5.50546 6.66655 6.00642 6.63984C6.35935 6.62103 6.6607 6.89187 6.67952 7.2448Z"
                                                                                    fill="white"></path>
                                                                                <path fill-rule="evenodd"
                                                                                    clip-rule="evenodd"
                                                                                    d="M6.81509 4.79241C6.56518 5.04232 6.16 5.04232 5.91009 4.79241C5.66018 4.5425 5.66018 4.13732 5.91009 3.88741L8.14986 1.64764C8.39977 1.39773 8.80495 1.39773 9.05486 1.64764L11.2946 3.88741C11.5445 4.13732 11.5445 4.5425 11.2946 4.79241C11.0447 5.04232 10.6395 5.04232 10.3896 4.79241L9.24229 3.64508V9.77934C9.24229 10.1328 8.95578 10.4193 8.60236 10.4193C8.24893 10.4193 7.96242 10.1328 7.96242 9.77934L7.96242 3.64508L6.81509 4.79241Z"
                                                                                    fill="white"></path>
                                                                            </svg>
                                                                            {{ __('Choose file here') }}
                                                                        </label>
                                                                        <input type="file" name="bank_transfer_invoice"
                                                                            id="bank_transfer_invoice" class="file-input">
                                                                        <input type="hidden" name="transaction_id"
                                                                            value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') }}">
                                                                        <input type="hidden" name="desc"
                                                                            value="{{ time() }}">
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-12">
                                                            <div class="payment-method card-btn">
                                                                <div class="text-right">
                                                                    <button type="submit" class="btn"
                                                                        id="bank_transfer">{{ __('Bank Transfer ') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_iyzipay_enabled']) && $store_payments['is_iyzipay_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <form id="payment-iyzipay-form" method="POST"
                                                                action="{{ route('iyzipay.prepare.payment', $store->slug) }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-iyzipay">
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
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-sspay">
                                                                    {{ __('Pay via SS pay') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_paytab_enabled']) && $store_payments['is_paytab_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <form id="payment-paytab-form" method="POST"
                                                                action="{{ route('pay.with.paytab', $store->slug) }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-paytab">
                                                                    {{ __('Pay via Paytab') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_benefit_enabled']) && $store_payments['is_benefit_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <form id="payment-benefit-form" method="POST"
                                                                action="{{ route('store.benefit.initiate', $store->slug) }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-benefit">
                                                                    {{ __('Pay via Benefit') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_cashfree_enabled']) && $store_payments['is_cashfree_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <form id="payment-cashfree-form" method="POST"
                                                                action="{{ route('store.cashfree.initiate', $store->slug) }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-cashfree">
                                                                    {{ __('Pay via Cashfree') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_aamarpay_enabled']) && $store_payments['is_aamarpay_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <form id="payment-aamarpay-form" method="POST"
                                                                action="{{ route('store.pay.aamarpay.payment', $store->slug) }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-aamarpay">
                                                                    {{ __('Pay via AamarPay') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_paytr_enabled']) && $store_payments['is_paytr_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <form id="payment-paytr-form" method="POST"
                                                                action="{{ route('store.pay.paytr.payment', $store->slug) }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-paytr">
                                                                    {{ __('Pay via PayTR') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_yookassa_enabled']) && $store_payments['is_yookassa_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <form id="payment-yookassa-form" method="POST"
                                                                action="{{ route('store.pay.yookassa.payment', $store->slug) }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-yookassa">
                                                                    {{ __('Pay via Yookassa') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_midtrans_enabled']) && $store_payments['is_midtrans_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <form id="payment-midtrans-form" method="POST"
                                                                action="{{ route('store.pay.midtrans.payment', $store->slug) }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-midtrans">
                                                                    {{ __('Pay via Midtrans') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
    
                                                    @if (isset($store_payments['is_xendit_enabled']) && $store_payments['is_xendit_enabled'] == 'on')
                                                        <div class="col-sm-6 col-12">
                                                            <form id="payment-xendit-form" method="POST"
                                                                action="{{ route('store.pay.xendit.payment', $store->slug) }}">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                                                <input type="hidden" name="type"
                                                                    class="customer_type">
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
                                                                <input type="hidden" name="product"
                                                                    class="customer_product">
                                                                <input type="hidden" name="order_id"
                                                                    class="customer_order_id">
                                                                <input type="hidden" name="name"
                                                                    class="customer_name">
                                                                <input type="hidden" name="email"
                                                                    class="customer_email">
                                                                <input type="hidden" name="phone"
                                                                    class="customer_phone">
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
                                                                <button class="button btn" type="submit"
                                                                    id="owner-xendit">
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
                                                                <button class="button btn" type="submit" id="owner-paimentpro">
                                                                    {{ __('Pay via Paiment Pro') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class="col-md-12 detail-form">
                                                            <div class="form-group">
                                                                <label for="mobile_number"
                                                                class="form-control-label">{{ __('Mobile Number') }}</label>
                                                                <input type="text" id="mobile_number"
                                                                    name="mobile_number"
                                                                    class="active paimentpro_mobile_number"
                                                                    data-from="mobile_number"
                                                                    placeholder="{{ __('Enter Mobile Number') }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="channel"
                                                                    class="form-control-label">{{ __('Channel') }}</label>
                
                                                                <input type="text" id="channel"
                                                                    name="channel" class="active paimentpro_channel"
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
                                                                <button class="button btn" type="submit" id="owner-fedapay">
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
                                                                <button class="button btn" type="submit" id="owner-nepalste">
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
                                                                <button class="button btn" type="submit" id="owner-payhere">
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
                                                                <button class="button btn" type="submit" id="owner-cinetpay">
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
                            <div class="conteiner">
                                <div class="floating-wpp"></div>
                                <div class="copyright d-flex align-items-center justify-content-between"
                                    id="footer">
                                    <div class="text-store">
                                        <p class="{{ env('SITE_RTL') == 'on' ? 'text-center' : 'text-left' }}">
                                            {{ $store->footer_note }}</p>
                                    </div>
                                    <div class="icone-store">
                                        <ul
                                            class="nav {{ env('SITE_RTL') == 'on' ? 'm-auto float-left' : 'float-right text-left' }}">
                                            @if (!empty($store->youtube))
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ $store->youtube }}"
                                                        target="{{ $store->youtube != '#' ? '_blank' : '' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            version="1.1" id="Capa_1" x="0px"
                                                            y="0px" viewBox="0 0 409.592 409.592"
                                                            style="enable-background:new 0 0 409.592 409.592;"
                                                            xml:space="preserve">
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
                                                    <a class="nav-link" href="mailto:{{ $store->email }}"
                                                        target="”_blank”">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            version="1.1" id="Capa_1" x="0px"
                                                            y="0px" viewBox="0 0 433.664 433.664"
                                                            style="enable-background:new 0 0 433.664 433.664;"
                                                            xml:space="preserve">
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
                                                    <a class="nav-link" href="{{ $store->facebook }}"
                                                        target="{{ $store->facebook != '#' ? '_blank' : '' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                                                            height="512" viewBox="0 0 512 512" width="512"
                                                            data-name="Layer 1">
                                                            <path
                                                                d="m420 36h-328a56 56 0 0 0 -56 56v328a56 56 0 0 0 56 56h160.67v-183.076h-36.615v-73.23h36.312v-33.094c0-29.952 14.268-76.746 77.059-76.746l56.565.227v62.741h-41.078c-6.679 0-16.183 3.326-16.183 17.592v29.285h58.195l-6.68 73.23h-54.345v183.071h94.1a56 56 0 0 0 56-56v-328a56 56 0 0 0 -56-56z" />
                                                        </svg>
                                                    </a>
                                                </li>
                                            @endif
                                            @if (!empty($store->instagram))
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ $store->instagram }}"
                                                        target="{{ $store->instagram != '#' ? '_blank' : '' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            version="1.1" id="Capa_1" x="0px"
                                                            y="0px" viewBox="0 0 512 512"
                                                            style="enable-background:new 0 0 512 512;"
                                                            xml:space="preserve">
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
                                                    <a class="nav-link" href="{{ $store->twitter }}"
                                                        target="{{ $store->twitter != '#' ? '_blank' : '' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            version="1.1" id="Capa_1" x="0px"
                                                            y="0px" viewBox="0 0 512 512"
                                                            style="enable-background:new 0 0 512 512;"
                                                            xml:space="preserve">
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
                                                    <a class="nav-link"
                                                        href="https://wa.me/{{ $store->whatsapp }}"
                                                        target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            version="1.1" id="Capa_1" x="0px"
                                                            y="0px" viewBox="0 0 52 52"
                                                            style="enable-background:new 0 0 52 52;"
                                                            xml:space="preserve">
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!---wrapper end here-->

    <div class="overlay"></div>

    <!-- popup -->

    <div class="modal modal-content modal-popup d-flex align-items-center" id="commonModal">
        <div class="modal-content-inner">
            <div class="modal-header align-items-center">
                <div class="modal-title">
                    <h6 class="mb-0" id="modelCommanModelLabel"></h6>
                </div>
                <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="body"></div>
        </div>
    </div>


    <div class="modal modal-content modal-popup d-flex align-items-center" id="checkoutModal">
        <div class="modal-content-inner">
            <div class="modal-header align-items-center">
                <div class="modal-title">
                    <h6 class="mb-0" id="modelcheckoutModalLabel">{{ __('Proceed To Checkout') }}</h6>
                </div>
                <button type="button" class="close close-button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="form-group col-6 d-flex justify-content-center col-form-label mb-0">
                    {{-- <a href="{{route('customer.login',$store->slug)}}" class="btn btn-secondary btn-light rounded-pill">{{__('Countinue to sign in')}}</a> --}}
                    <a data-url="{{ route('customer.login', $store->slug) }}" data-ajax-popup="true"
                        data-title="{{ __('Login') }}" data-toggle="modal" data-size="md"
                        class="btn btn-secondary btn-light rounded-pill" id="loginBtn">
                        {{ __('Countinue to sign in') }}
                    </a>
                </div>
                <div class="form-group col-6 d-flex justify-content-center col-form-label mb-0 asGuest">
                    <a href="#footer"
                        class="btn btn-secondary btn-light rounded-pill">{{ __('Continue as guest') }}</a>
                </div>
            </div>
        </div>
    </div>



    <!-- !--search-popup--! -->
    <div class="search-popup">
        <div class="close-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50"
                fill="none">
                <path
                    d="M27.7618 25.0008L49.4275 3.33503C50.1903 2.57224 50.1903 1.33552 49.4275 0.572826C48.6647 -0.189868 47.428 -0.189965 46.6653 0.572826L24.9995 22.2386L3.33381 0.572826C2.57102 -0.189965 1.3343 -0.189965 0.571605 0.572826C-0.191089 1.33562 -0.191186 2.57233 0.571605 3.33503L22.2373 25.0007L0.571605 46.6665C-0.191186 47.4293 -0.191186 48.666 0.571605 49.4287C0.952952 49.81 1.45285 50.0007 1.95275 50.0007C2.45266 50.0007 2.95246 49.81 3.3339 49.4287L24.9995 27.763L46.6652 49.4287C47.0465 49.81 47.5464 50.0007 48.0463 50.0007C48.5462 50.0007 49.046 49.81 49.4275 49.4287C50.1903 48.6659 50.1903 47.4292 49.4275 46.6665L27.7618 25.0008Z"
                    fill="white"></path>
            </svg>
        </div>
        <div class="search-form-wrapper">
            <form>
                <div class="form-inputs d-flex">
                    <input type="search" placeholder="Search Product..." class="form-control">
                    <button type="submit" class="btn btn-serch">
                        <svg>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.000169754 6.99457C0.000169754 10.8576 3.13174 13.9891 6.99473 13.9891C8.60967 13.9891 10.0968 13.4418 11.2807 12.5226C11.3253 12.6169 11.3866 12.7053 11.4646 12.7834L17.0603 18.379C17.4245 18.7432 18.015 18.7432 18.3792 18.379C18.7434 18.0148 18.7434 17.4243 18.3792 17.0601L12.7835 11.4645C12.7055 11.3864 12.6171 11.3251 12.5228 11.2805C13.442 10.0966 13.9893 8.60951 13.9893 6.99457C13.9893 3.13157 10.8577 0 6.99473 0C3.13174 0 0.000169754 3.13157 0.000169754 6.99457ZM1.86539 6.99457C1.86539 4.1617 4.16187 1.86522 6.99473 1.86522C9.8276 1.86522 12.1241 4.1617 12.1241 6.99457C12.1241 9.82743 9.8276 12.1239 6.99473 12.1239C4.16187 12.1239 1.86539 9.82743 1.86539 6.99457Z">
                            </path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- !--search-popup end--! -->

    <script src="{{ asset('custom/js/jquery.min.js') }}"></script>
    <script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>

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

        $(document).on('click', '.checkoutBtn', function() {
            $("#asGuest,#paymentsBtn").show();
            $(".checkoutBtn").hide();

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
            $("#commonModal .modal-content-inner").addClass('modal-' + size);
            if ($(this).data('name') == 'custom-addcart' || title == 'Login') {
                $("#commonModal .modal-content-inner").removeClass('modal-lg')
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


        $(document).on('click', 'a[class="btn checkoutBtn"]', function() {
            var title = $(this).data('title');
            var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
            var url = $(this).data('url');
            $("#checkoutModal .modal-title").html(title);
            $("#checkoutModal .modal-content-inner").addClass('modal-' + size);
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

            if ($(".summernote-simple").length) {
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

    <script src="https://js.paystack.co/v1/inline.js"></script>

    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        @if (
            !empty($store_payments['is_stripe_enabled']) &&
                isset($store_payments['is_stripe_enabled']) &&
                $store_payments['is_stripe_enabled'] == 'on' &&
                !empty($store_payments['stripe_key']) &&
                !empty($store_payments['stripe_secret']))

            <?php $stripe_session = Session::get('stripe_session'); ?>
            <?php if(isset($stripe_session) && $stripe_session): ?>
                <
                script >
                var stripe = Stripe('{{ $store_payments['stripe_key'] }}');
            stripe.redirectToCheckout({
                sessionId: '{{ $stripe_session->id }}',
            }).then((result) => {
                console.log(result);
            });
    </script>
    <?php endif ?>
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
                timer: 2000,
                url_target: "_blank",
                mouse_over: !1,
                animate: {
                    enter: o,
                    exit: i
                },
                // danger


                template: '<div class = "toast text-white bg-' + cls + ' fade show animated fadeInDown"' +
                    'role = "alert"' +
                    'aria-live = "assertive"' +
                    'aria-atomic = "true"' +
                    'data-notify - position = "top-right"' +
                    'style ="display: inline-block; margin: 0px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 1080; top: 15px; right: 15px; animation-iteration-count: 1;">' +
                    '<div class = "d-flex"> <div class = "toast-body"> ' + message +
                    '</div><button type="button" class="close close-button-white me-2 m-auto"><span aria-hidden="true" data-dismiss="modal" data-notify="dismiss" data-bs-dismiss="toast" aria-label="Close">×</span></button>' +
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

        // $('.nice-select').on('click',function(){
        //     console.log($(this));
        //     $(this).find('.nice-select').toggleClass('open')
        // })


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
                            '<div class="radio-group shipping_location"><input type="radio" name="shipping_id" data-id="' +
                            value.price + '" value="' + value.id +
                            '" id="shipping_price' + key + '" class="shipping_mode" ' +
                            checked + '>' +
                            ' <label name="shipping_label" for="shipping_price' + key +
                            '" class="shipping_label"> ' + value.name +
                            '</label></div>';
                    });
                    $('#shipping_location_content').html(html);
                }
            });
        });


        $(document).on('click', '.apply-coupon', function(e) {
            e.preventDefault();
            var ele = $(this);
            var coupon = ele.closest('.row').find('.coupon').val();
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
                        }, 1000);
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
                            $('.variation_price1').html('Please Select Variants');
                            $('.variant_qty').html('0');
                        } else {
                            $('.variation_price1').html(data.price);
                            $('#variant_id').val(data.variant_id);
                            $('.variant_qty').html(data.quantity);
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
                $('#product_sort li').removeClass('active');
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
            $('.collection-items').removeClass('active');
            $('.collection-items').addClass('d-none');
            $('.' + dataHref).addClass('active');
            $('.' + dataHref).removeClass('d-none');
            $('div').removeClass('nav-open');
        });

        $(".productTab").click(function(e) {
            $('.banner-col-left ').removeClass('active')
            $('body').removeClass('no-scroll')
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
                        console.log('error12');
                    }
                });
            }
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

        // payPayfast(amount = 0, coupon = null,ajaxData);
        // })

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

    <!--scripts end here-->
    </body>

</html>
