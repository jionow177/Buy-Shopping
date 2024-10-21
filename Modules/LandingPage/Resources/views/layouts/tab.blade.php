<li class="nav-item"><a href="{{ route('landingpage.index') }}"
        class="nav-link {{ Request::route()->getName() == 'landingpage.index' ? ' active' : '' }}">{{ __('Top Bar') }}
        <div class="float-end"></div></a></li>

<li class="nav-item"><a href="{{ route('custom_page.index') }}"
        class="nav-link {{ Request::route()->getName() == 'custom_page.index' ? ' active' : '' }}">{{ __('Custom Page') }}
        <div class="float-end"></div>
    </a></li>

<li class="nav-item"><a href="{{ route('homesection.index') }}"
        class="nav-link {{ Request::route()->getName() == 'homesection.index' ? ' active' : '' }}">{{ __('Home') }}
        <div class="float-end"></div></a></li>

<li class="nav-item"><a href="{{ route('features.index') }}"
        class="nav-link {{ Request::route()->getName() == 'features.index' ? ' active' : '' }}">{{ __('Features') }}
        <div class="float-end"></div>
    </a></li>

<li class="nav-item"><a href="{{ route('discover.index') }}"
        class="nav-link {{ Request::route()->getName() == 'discover.index' ? ' active' : '' }}">{{ __('Discover') }}
        <div class="float-end"></div>
    </a></li>

<li class="nav-item"><a href="{{ route('screenshots.index') }}"
        class="nav-link {{ Request::route()->getName() == 'screenshots.index' ? ' active' : '' }}">{{ __('Screenshots') }}
        <div class="float-end"></div>
    </a></li>

<li class="nav-item"><a href="{{ route('pricing_plan.index') }}"
        class="nav-link {{ Request::route()->getName() == 'pricing_plan.index' ? ' active' : '' }}">{{ __('Pricing Plan') }}
        <div class="float-end"></div>
    </a></li>

<li class="nav-item"><a href="{{ route('faq.index') }}"
        class="nav-link {{ Request::route()->getName() == 'faq.index' ? ' active' : '' }}">{{ __('FAQ') }} <div
            class="float-end"></div></a></li>

<li class="nav-item"><a href="{{ route('testimonials.index') }}"
        class="nav-link {{ Request::route()->getName() == 'testimonials.index' ? ' active' : '' }}">{{ __('Testimonials') }}
        <div class="float-end"></div>
    </a></li>

<li class="nav-item"><a href="{{ route('join_us.index') }}"
        class="nav-link {{ Request::route()->getName() == 'join_us.index' ? ' active' : '' }}">{{ __('Join Us') }}
        <div class="float-end"></div></a></li>
