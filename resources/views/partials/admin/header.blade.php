@php
    $currantLang = $users->currentLanguages();
    $profile = \App\Models\Utility::get_file('uploads/profile/');
    if ($currantLang == null) {
        $currantLang = 'en';
    }

    $current_store = \Auth::user()->current_store;
    $store = \Auth::user()->currentStore;
    if (\Auth::user()->type != 'super admin') {
        $currentPlan = \Auth::user()->currentPlan;
        $allStore = \App\Models\Store::where('created_by', \Auth::user()->id)->get();
        foreach ($allStore as $key => $oneStore) {
            $key = $key+1;
            if ($currentPlan->max_stores != -1 && $key > $currentPlan->max_stores) {
                $oneStore['is_store_enabled'] = 0;
                $oneStore->save();
            } else {
                $oneStore['is_store_enabled'] = 1;
                $oneStore->save();
            }
        }
    }
    // $setting = App\Models\Utility::settings();
@endphp
<!-- [ Header ] start -->
@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <header class="dash-header transprent-bg">
    @else
        <header class="dash-header">
@endif
<div class="header-wrapper">
    <div class="me-auto dash-mob-drp">
        <ul class="list-unstyled">
            <li class="dash-h-item mob-hamburger">
                <a href="#!" class="dash-head-link" id="mobile-collapse">
                    <div class="hamburger hamburger--arrowturn">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </a>
            </li>
            <li class="dropdown dash-h-item drp-company">
                <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="theme-avtar"> <img alt="#" width="35"
                            src="{{ !empty($users->avatar) ? $profile . '/' . $users->avatar : $profile . '/avatar.png' }}"
                            class="img-fluid rounded-circle"></span>
                    <span class="hide-mob ms-2">{{ __('Hi,') }}{{ Auth::user()->name }}!</span>
                    <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                </a>
                <div class="dropdown-menu dash-h-dropdown">
                    <a href="{{ route('profile') }}" class="dropdown-item">
                        <i class="ti ti-user"></i>
                        <span>{{ __('My Profile') }}</span>
                    </a>
                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault();document.getElementById('frm-logout').submit();">
                        <i class="ti ti-power"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                    <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf</form>
                </div>
            </li>
        </ul>
    </div>

    <div class="ms-auto">
        <ul class="list-unstyled">
            @impersonating($guard = null)
                    <li class="dropdown dash-h-item drp-company">
                        <a class="btn btn-danger btn-sm me-3"
                            href="{{ route('exit.owner') }}"><i class="ti ti-ban"></i>
                            {{ __('Exit Owner Login') }}
                        </a>
                    </li>
            @endImpersonating
            @auth('web')
                @if (Auth::user()->type != 'super admin')
                    @can('Create Store')
                        <li class="dropdown dash-h-item drp-language">
                            <a href="#!" class="dash-head-link dropdown-toggle arrow-none me-0 cust-btn" data-size="lg"
                                data-url="{{ route('store-resource.create') }}" data-ajax-popup="true"
                                data-title="Create New Store">
                                <i class="ti ti-circle-plus"></i>
                                <span class="drp-text hide-mob">{{ __('Create New Store') }}</span>
                            </a>
                        </li>
                    @endcan
                @endif
            @endauth
            @if (Auth::user()->type != 'super admin')
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0 cust-btn" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false"
                        data-bs-placement="bottom" data-bs-original-title="Select your bussiness">
                        <i class="ti ti-building-store"></i>
                        <span class="drp-text hide-mob">{{ __(ucfirst($store->name)) }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        @php
                            $user = App\Models\User::find(\Auth::user()->creatorId());
                        @endphp
                        @foreach ($user->stores as $store)
                            @if ($store->is_store_enabled == 1)
                                <a href="@if ($current_store == $store->id) # @else {{ route('change_store', $store->id) }} @endif"
                                    class="dropdown-item">
                                    @if ($current_store == $store->id)
                                        <i class="ti ti-checks text-primary"></i>
                                    @endif
                                    <span>{{ $store->name }}</span>
                                    @if ($current_store == $store->id)
                                        <span class="badge bg-dark">{{ 'Owner' }}</span>
                                    @endif
                                </a>
                            @else
                                <a href="#!" class="dropdown-item">
                                    <i class="ti ti-lock"></i>
                                    <span>{{ $store->name }}</span>
                                    @if (isset($store->pivot->permission))
                                        @if ($store->pivot->permission == 'Owner')
                                            <span class="badge bg-dark">{{ __($store->pivot->permission) }}</span>
                                        @else
                                            <span class="badge bg-dark">{{ __('Shared') }}</span>
                                        @endif
                                    @endif
                                </a>
                            @endif
                        @endforeach
                        <div class="dropdown-divider m-0"></div>
                    </div>
                </li>
            @endif
            @if (Auth::user()->type != 'super admin')
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text">{{ Str::ucfirst($currantLang->fullname) }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        @foreach (App\Models\Utility::languages() as $code => $lang)
                            <a href="{{ route('change.language', $code) }}"
                                class="dropdown-item {{ $currantLang->code == $code ? 'text-primary' : '' }}">
                                <span>{{ Str::ucfirst($lang) }}</span>
                            </a>
                        @endforeach
                    </div>
                </li>
            @else
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text">{{ Str::ucfirst($currantLang->fullname) }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        @foreach (App\Models\Utility::languages() as $code => $lang)
                            <a href="{{ route('change.language', $code) }}"
                                class="dropdown-item {{ $currantLang->code == $code ? 'text-primary' : '' }}">
                                <span>{{ Str::ucfirst($lang) }}</span>
                            </a>
                        @endforeach
                     @if (Auth::user()->type == 'super admin')
                            @can('Create Language')
                                <a href="#" data-url="{{ route('create.language') }}" data-size="md"
                                    data-ajax-popup="true" data-title="{{ __('Create New Language') }}"
                                    class="dropdown-item border-top py-1 text-primary">{{ __('Create Language') }}</a>
                            @endcan
                            @can('Manage Language')
                                <a href="{{ route('manage.language', [$currantLang->code]) }}"
                                    class="dropdown-item border-top py-1 text-primary">{{ __('Manage Languages') }}
                                </a>
                            @endcan
                        @endif
                    </div>
                </li>
            @endif
        </ul>
    </div>
</div>
</header>
<!-- [ Header ] end -->
