@php
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = \App\Models\Utility::GetLogo();
    // $setting = Utility::settings();
    // dd($company_logo);
    //    if(\Auth::user()->type=="Super Admin")
    //     {
    //         $company_logo=Utility::get_superadmin_logo();
    //     }
    //     else
    //     {
    //         $company_logo=Utility::get_company_logo();
    //     }
@endphp
@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <nav class="dash-sidebar light-sidebar transprent-bg">
    @else
        <nav class="dash-sidebar light-sidebar">
@endif

<div class="navbar-wrapper">
    <div class="m-header justify-content-center">
        <a href="{{ route('dashboard') }}" class="b-brand">
            @if ($setting['cust_darklayout'] == 'on')
                <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-light.png') . '?timestamp='. time() }}"
                    alt="" width="201px" height="41px" />
            @else
                <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?timestamp='. time() }}"
                    alt="" width="201px" height="41px" />
            @endif
        </a>
    </div>
    <div class="navbar-content">
        <ul class="dash-navbar">
            @if (Auth::user()->type == 'super admin')
                @can('Manage Dashboard')
                    <li class="dash-item {{ Request::segment(1) == 'dashboard' ? ' active' : 'collapsed' }}">
                        <a href="{{ route('dashboard') }}"
                            class="dash-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-home"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                @endcan
                @can('Manage Store')
                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'store-resource' || Request::route()->getName() == 'store.grid' ? ' active dash-trigger' : 'collapsed' }}">
                        <a href="{{ route('store-resource.index') }}"
                            class="dash-link {{ request()->is('store-resource') ? 'active' : '' }}">
                            <span class="dash-micon">
                                <i data-feather="user"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Stores') }}</span>
                        </a>
                    </li>
                @endcan
                @can('Manage Coupans')
                    <li class="dash-item dash-hasmenu {{ Request::segment(1) == 'coupons' ? ' active' : 'collapsed' }}">
                        <a href="{{ route('coupons.index') }}"
                            class="dash-link {{ request()->is('coupons') ? 'active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-tag"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Coupons') }}</span>
                        </a>
                    </li>
                @endcan

                @can('Manage Plans')
                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'plans' || Request::route()->getName() == 'stripe' ? ' active dash-trigger' : 'collapsed' }}">
                        <a href="{{ route('plans.index') }}"
                            class="dash-link {{ request()->is('plans') ? 'active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-trophy"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Plans') }}</span>
                        </a>
                    </li>
                @endcan

                @can('Manage Plan Request')
                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'plan_request' ? ' active' : 'collapsed' }}">
                        <a href="{{ route('plan_request.index') }}"
                            class="dash-link {{ request()->is('plan_request') ? 'active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-brand-telegram"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Plan Requests') }}</span>
                        </a>
                    </li>
                @endcan

                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'custom_domain_request' ? ' active' : 'collapsed' }}">
                    <a href="{{ route('custom_domain_request.index') }}"
                        class="dash-link {{ request()->is('custom_domain_request') ? 'active' : '' }}">
                        <span class="dash-micon">
                            <i class="ti ti-browser"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Domain Requests') }}</span>
                    </a>
                </li>

                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'referral-program' ? ' active' : 'collapsed' }}">
                    <a href="{{ route('referral-program.index') }}"
                        class="dash-link {{ request()->is('referral-program') ? 'active' : '' }}">
                        <span class="dash-micon">
                            <i class="ti ti-discount-2"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Referral Program') }}</span>
                    </a>
                </li>

                @can('Manage Email Template')
                    <li
                        class="dash-item dash-hasmenu {{ Request::route()->getName() == 'manage.email.language' || Request::route()->getName() == 'manage.email.language' ? ' active dash-trigger' : 'collapsed' }}">
                        <a href="{{ route('manage.email.language', \Auth::user()->lang) }}"
                            class="dash-link {{ request()->is('email_template') ? 'active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-mail"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Email Templates') }}</span>
                        </a>
                    </li>
                @endcan

                @if (Auth::user()->type == 'super admin')
                    @include('landingpage::menu.landingpage')
                @endif
                @can('Manage Settings')
                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'settings' || Request::route()->getName() == 'store.editproducts' ? ' active dash-trigger' : 'collapsed' }}">
                        <a href="{{ route('settings') }}"
                            class="dash-link {{ request()->is('settings') ? 'active' : '' }}">
                            <span class="dash-micon"> <i data-feather="settings"></i>
                            </span>
                            <span class="dash-mtext">
                                @if (Auth::user()->type == 'super admin')
                                    {{ __('Settings') }}
                                @endif
                            </span>
                        </a>
                    </li>
                @endcan
            @else
                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'dashboard' || Request::segment(1) == 'storeanalytic' || Request::route()->getName() == 'orders.show' ? ' active dash-trigger' : 'collapsed' }}">
                    <a href="#!" class="dash-link ">
                        <span class="dash-micon">
                            <i class="ti ti-home"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Dashboard') }}</span>
                        <span class="dash-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>
                    <ul
                        class="dash-submenu {{ Request::segment(1) == 'dashboard' || Request::segment(1) == 'storeanalytic' ? ' show' : '' }}">
                        @can('Manage Dashboard')
                            <li class="dash-item {{ Request::route()->getName() == 'dashboard' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>
                        @endcan
                        @can('Manage Store Analytics')
                            <li class="dash-item {{ Request::route()->getName() == 'storeanalytic' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('storeanalytic') }}">{{ __('Store Analytics') }}</a>
                            </li>
                        @endcan
                        @can('Manage Orders')
                            <li
                                class="dash-item {{ Request::segment(1) == 'orders.index' || Request::route()->getName() == 'orders.show' ? ' active dash-trigger' : 'collapsed' }}">
                                <a class="dash-link" href="{{ route('orders.index') }}">{{ __('Orders') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @can('Manage Themes')
                    <li class="dash-item {{ Request::segment(1) == 'themes' ? ' active' : 'collapsed' }}">
                        <a href="{{ route('themes.theme') }}"
                            class="dash-link {{ request()->is('themes') ? 'active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-layout-2"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Themes') }}</span>
                        </a>
                    </li>
                @endcan
                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'roles' ? ' active dash-trigger' : 'collapsed' }}">
                    <a href="#!" class="dash-link ">
                        <span class="dash-micon">
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Staff') }}</span>
                        <span class="dash-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>
                    <ul
                        class="dash-submenu {{ Request::segment(1) == 'roles' || Request::segment(1) == 'roles' ? ' show' : '' }}">
                        @can('Manage Role')
                            <li class="dash-item {{ Request::route()->getName() == 'roles' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Roles') }}</a>
                            </li>
                        @endcan
                        @can('Manage User')
                            <li
                                class="dash-item {{ Request::segment(1) == 'users.index' || Request::route()->getName() == 'users.show' ? ' active dash-trigger' : 'collapsed' }}">
                                <a class="dash-link" href="{{ route('users.index') }}">{{ __('User') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li
                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'product' || Request::segment(1) == 'product_categorie' || Request::segment(1) == 'product_tax' || Request::segment(1) == 'product-coupon' || Request::segment(1) == 'shipping' || Request::segment(1) == 'subscriptions' || Request::segment(1) == 'custom-page' || Request::segment(1) == 'blog' ? ' active dash-trigger' : 'collapsed' }}">
                    <a href="#!" class="dash-link">
                        <span class="dash-micon">
                            <i class="ti ti-layout-2"></i>
                        </span>
                        <span class="dash-mtext">{{ __('Shop') }}</span>
                        <span class="dash-arrow">
                            <i data-feather="chevron-right"></i>
                        </span>
                    </a>
                    <ul class="dash-submenu {{ Request::segment(1) == 'product.index' ? ' show' : '' }}">
                        @can('Manage Products')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'product.index' || Request::route()->getName() == 'product.create' || Request::route()->getName() == 'product.edit' || Request::route()->getName() == 'product.show' || Request::route()->getName() == 'product.grid' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('product.index') }}">{{ __('Products') }}</a>
                            </li>
                        @endcan
                        @can('Manage Product category')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'product_categorie.index' ? ' active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('product_categorie.index') }}">{{ __('Product Category') }}</a>
                            </li>
                        @endcan
                        @can('Manage Product Tax')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'product_tax.index' ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('product_tax.index') }}">{{ __('Product Tax') }}</a>
                            </li>
                        @endcan
                        @can('Manage Product Coupan')
                            <li
                                class="dash-item {{ Request::route()->getName() == 'product-coupon.index' || Request::route()->getName() == 'product-coupon.show' ? ' active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('product-coupon.index') }}">{{ __('Product Coupon') }}</a>
                            </li>
                        @endcan
                        @if (isset($plan->shipping_method) && $plan->shipping_method == 'on')
                            @can('Manage Shipping')
                                <li
                                    class="dash-item {{ Request::route()->getName() == 'shipping.index' ? ' active' : '' }}">
                                    <a class="dash-link" href="{{ route('shipping.index') }}">{{ __('Shipping') }}</a>
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>
                @can('Manage Customers')
                    <li
                        class="dash-item {{ Request::segment(1) == 'customer.index' || Request::route()->getName() == 'customer.show' ? ' active dash-trigger ' : 'collapsed' }}">
                        <a href="{{ route('customer.index') }}"
                            class="dash-link {{ request()->is('customer.index') ? 'active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-user"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Customers') }}</span>
                        </a>
                    </li>
                @endcan
                @can('Manage Plans')
                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'plans' || Request::route()->getName() == 'stripe' ? ' active dash-trigger' : 'collapsed' }}">
                        <a href="{{ route('plans.index') }}"
                            class="dash-link {{ request()->is('plans') ? 'active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-trophy"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Plans') }}</span>
                        </a>
                    </li>
                @endcan
                
                @if (Auth::user()->type == 'Owner')
                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'referral-program' ? ' active' : 'collapsed' }}">
                        <a href="{{ route('referral-program.owner') }}"
                            class="dash-link {{ request()->is('referral-program') ? 'active' : '' }}">
                            <span class="dash-micon">
                                <i class="ti ti-discount-2"></i>
                            </span>
                            <span class="dash-mtext">{{ __('Referral Program') }}</span>
                        </a>
                    </li>
                @endif

                @can('Manage Settings')
                    <li
                        class="dash-item dash-hasmenu {{ Request::segment(1) == 'settings' || Request::route()->getName() == 'store.editproducts' ? ' active dash-trigger' : 'collapsed' }}">
                        <a href="{{ route('settings') }}"
                            class="dash-link {{ request()->is('settings') ? 'active' : '' }}">
                            <span class="dash-micon"> <i data-feather="settings"></i>
                            </span>
                            <span class="dash-mtext">
                                {{ __('Store Settings') }}
                            </span>
                        </a>
                    </li>
                @endcan
            @endif
        </ul>
        @if ($setting['cust_darklayout'] == 'on')
            <div class="navbar-footer border-top bg-dark">
            @else
                <div class="navbar-footer border-top bg-white">
        @endif

        <div class="d-flex align-items-center py-3 px-3 border-bottom">
            <div class="me-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="29" height="30" viewBox="0 0 29 30"
                    fill="none">
                    <circle cx="14.5" cy="15.1846" r="14.5" fill="#6FD943" />
                    <path opacity="0.4"
                        d="M22.08 8.66459C21.75 8.28459 21.4 7.92459 21.02 7.60459C19.28 6.09459 17 5.18461 14.5 5.18461C12.01 5.18461 9.73999 6.09459 7.98999 7.60459C7.60999 7.92459 7.24999 8.28459 6.92999 8.66459C5.40999 10.4146 4.5 12.6946 4.5 15.1846C4.5 17.6746 5.40999 19.9546 6.92999 21.7046C7.24999 22.0846 7.60999 22.4446 7.98999 22.7646C9.73999 24.2746 12.01 25.1846 14.5 25.1846C17 25.1846 19.28 24.2746 21.02 22.7646C21.4 22.4446 21.75 22.0846 22.08 21.7046C23.59 19.9546 24.5 17.6746 24.5 15.1846C24.5 12.6946 23.59 10.4146 22.08 8.66459ZM14.5 19.6246C13.54 19.6246 12.65 19.3146 11.93 18.7946C11.52 18.5146 11.17 18.1646 10.88 17.7546C10.37 17.0346 10.06 16.1346 10.06 15.1846C10.06 14.2346 10.37 13.3346 10.88 12.6146C11.17 12.2046 11.52 11.8546 11.93 11.5746C12.65 11.0546 13.54 10.7446 14.5 10.7446C15.46 10.7446 16.35 11.0546 17.08 11.5646C17.49 11.8546 17.84 12.2046 18.13 12.6146C18.64 13.3346 18.95 14.2346 18.95 15.1846C18.95 16.1346 18.64 17.0346 18.13 17.7546C17.84 18.1646 17.49 18.5146 17.08 18.8046C16.35 19.3146 15.46 19.6246 14.5 19.6246Z"
                        fill="#162C4E" />
                    <path
                        d="M22.08 8.66459L18.18 12.5746C18.16 12.5846 18.15 12.6046 18.13 12.6146C17.84 12.2046 17.49 11.8546 17.08 11.5646C17.09 11.5446 17.1 11.5346 17.12 11.5146L21.02 7.60459C21.4 7.92459 21.75 8.28459 22.08 8.66459Z"
                        fill="#162C4E" />
                    <path
                        d="M11.9297 18.7947C11.9197 18.8147 11.9097 18.8347 11.8897 18.8547L7.98969 22.7647C7.60969 22.4447 7.24969 22.0847 6.92969 21.7047L10.8297 17.7947C10.8397 17.7747 10.8597 17.7647 10.8797 17.7547C11.1697 18.1647 11.5197 18.5147 11.9297 18.7947Z"
                        fill="#162C4E" />
                    <path
                        d="M11.9297 11.5746C11.5197 11.8546 11.1697 12.2045 10.8797 12.6145C10.8597 12.6045 10.8497 12.5846 10.8297 12.5746L6.92969 8.66453C7.24969 8.28453 7.60969 7.92453 7.98969 7.60453L11.8897 11.5146C11.9097 11.5346 11.9197 11.5546 11.9297 11.5746Z"
                        fill="#162C4E" />
                    <path
                        d="M22.08 21.7046C21.75 22.0846 21.4 22.4446 21.02 22.7646L17.12 18.8546C17.1 18.8346 17.09 18.8246 17.08 18.8046C17.49 18.5146 17.84 18.1646 18.13 17.7546C18.15 17.7646 18.16 17.7746 18.18 17.7946L22.08 21.7046Z"
                        fill="#162C4E" />
                </svg>
            </div>
            <div>
                <b class="d-block f-w-700">{{ __('You need help?') }}</b>
                <span>{{ __('Check out our repository') }} </span>
            </div>
        </div>
    </div>
</div>
</div>
</nav>
