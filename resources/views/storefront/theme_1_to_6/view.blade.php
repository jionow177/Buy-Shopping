<style>
    .shoping_count:after {
        content: attr(value);
        font-size: 14px;
        background: #273444;
        border-radius: 50%;
        padding: 1px 5px 1px 4px;
        position: relative;
        left: -5px;
        top: -10px;
    }

    @media (min-width: 768px) {
        .header-account-page {
            height: 100px;
        }
    }
</style>

<body>
    @php
        if (!empty(session()->get('lang'))) {
            $currantLang = session()->get('lang');
        } else {
            $currantLang = $store->lang;
        }
        $data = DB::table('settings');
        $languages = \App\Models\Utility::languages();
        $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
        $p_logo = \App\Models\Utility::get_file('uploads/product_image/');
        $data = $data
            ->where('created_by', '>', 1)
            ->where('store_id', $store->id)
            ->where('name', 'SITE_RTL')
            ->first();
    @endphp
    <div class="modal-body">
        <div class="row pop-inner">
            <div class="col-lg-4 col-12">
                <div class="slider-wrapper">
                    <div class="product-main-slider lightbox">
                        @if (!$products_image->isEmpty())
                            @foreach ($products_image as $key => $product)
                                <div class="product-main-item">
                                    <div class="product-item-img">
                                        {{-- @if (!empty($products_image[$key]->product_images)) --}}
                                        <img src="{{ $p_logo . (isset($products_image[$key]->product_images) && !empty($products_image[$key]->product_images) ? $products_image[$key]->product_images : 'is_cover_image.png') }}"
                                            alt="product-img">
                                        {{-- <a href="{{ $p_logo . $products_image[$key]->product_images }}"
                                            data-caption="Caption 1" class="open-lightbox" target="_blank">
                                            <div class="img-prew-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20"
                                                    viewBox="0 0 25 25" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M0 9.375C0 14.5527 4.19733 18.75 9.375 18.75C11.5395 18.75 13.5328 18.0164 15.1196 16.7843C15.1794 16.9108 15.2615 17.0293 15.3661 17.1339L22.8661 24.6339C23.3543 25.122 24.1457 25.122 24.6339 24.6339C25.122 24.1457 25.122 23.3543 24.6339 22.8661L17.1339 15.3661C17.0293 15.2615 16.9108 15.1794 16.7844 15.1196C18.0164 13.5328 18.75 11.5395 18.75 9.375C18.75 4.19733 14.5527 0 9.375 0C4.19733 0 0 4.19733 0 9.375ZM2.5 9.375C2.5 5.57804 5.57804 2.5 9.375 2.5C13.172 2.5 16.25 5.57804 16.25 9.375C16.25 13.172 13.172 16.25 9.375 16.25C5.57804 16.25 2.5 13.172 2.5 9.375Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                        </a> --}}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <img src="{{ $logo . (isset($products->is_cover) && !empty($products->is_cover) ? $products->is_cover : 'default_img.png') }}"
                                alt="product-img">
                            {{-- <a href="{{ $logo . (isset($products->is_cover) && !empty($products->is_cover) ? $products->is_cover : 'default_img.png') }}"
                                data-caption="Caption 1" class="open-lightbox ">
                                <div class="img-prew-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="20"
                                        viewBox="0 0 25 25" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0 9.375C0 14.5527 4.19733 18.75 9.375 18.75C11.5395 18.75 13.5328 18.0164 15.1196 16.7843C15.1794 16.9108 15.2615 17.0293 15.3661 17.1339L22.8661 24.6339C23.3543 25.122 24.1457 25.122 24.6339 24.6339C25.122 24.1457 25.122 23.3543 24.6339 22.8661L17.1339 15.3661C17.0293 15.2615 16.9108 15.1794 16.7844 15.1196C18.0164 13.5328 18.75 11.5395 18.75 9.375C18.75 4.19733 14.5527 0 9.375 0C4.19733 0 0 4.19733 0 9.375ZM2.5 9.375C2.5 5.57804 5.57804 2.5 9.375 2.5C13.172 2.5 16.25 5.57804 16.25 9.375C16.25 13.172 13.172 16.25 9.375 16.25C5.57804 16.25 2.5 13.172 2.5 9.375Z"
                                            fill="white" />
                                    </svg>
                                </div>
                            </a> --}}
                        @endif

                    </div>
                    <div class="product-thumb-slider">
                        @if (is_object($products_image))
                            @foreach ($products_image as $key => $product)
                                <div class="product-thumb-item">
                                    <div class="thumb-img">
                                        @if (!empty($products_image[$key]->product_images))
                                            <img src="{{ $p_logo . $products_image[$key]->product_images }}"
                                                alt="phoneimg">
                                        @else
                                            <img src="{{ $p_logo . $products_image[$key]->product_images }}"
                                                alt="phoneimg">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-12">
                <div class="right-text-contant">
                    @if ($products->description != null)
                        <div class="right-taxt">
                            <p>{!! $products->description !!}</p>
                        </div>
                        <div class="right-taxt">
                            <ul class="line-list">
                                @if (!empty($products->custom_field_1) && !empty($products->custom_value_1))
                                    <li>{{ $products->custom_field_1 }} : {{ $products->custom_value_1 }}</li>
                                @endif
                                @if (!empty($products->custom_field_2) && !empty($products->custom_value_2))
                                    <li>{{ $products->custom_field_2 }} : {{ $products->custom_value_2 }}</li>
                                @endif
                                @if (!empty($products->custom_field_3) && !empty($products->custom_value_3))
                                    <li>{{ $products->custom_field_3 }} : {{ $products->custom_value_3 }}</li>
                                @endif
                                @if (!empty($products->custom_field_4) && !empty($products->custom_value_4))
                                    <li>{{ $products->custom_field_4 }} : {{ $products->custom_value_4 }}</li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    @if ($products->enable_product_variant == 'on')
                        <div class="right-taxt" id="var_selection">
                            <input type="hidden" id="product_id" value="{{ $products->id }}">
                            <input type="hidden" id="variant_id" value="">
                            <input type="hidden" id="variant_qty" value="">
                            @foreach ($product_variant_names as $key => $variant)
                                <span class="title">
                                    {{ $variant->variant_name }}
                                </span>
                                <select name="product[{{ $key }}]" id="pro_variants_name"
                                    class="form-control custom-select variant-selection pro_variants_name{{ $key }}">
                                    <option value="0">{{ __('Select') }}</option>
                                    @foreach ($variant->variant_options as $key => $values)
                                        <option value="{{ $values }}">
                                            {{ $values }}
                                        </option>
                                    @endforeach
                                </select>
                            @endforeach
                        </div>
                    @endif
                    <div class="right-taxt">
                        <span class="price variation_price1">
                            @if ($products->enable_product_variant == 'on')
                                {{-- {{ \App\Models\Utility::priceFormat(0) }} --}}
                                {{ __('Please Select Variants') }}
                            @else
                                {{ \App\Models\Utility::priceFormat($products->price) }}
                            @endif
                        </span>
                        <ul class="d-flex align-items-center" style="margin: 15px 0;gap: 20px;">
                            <li>
                                <span class="badge-pill">{{ __('ID: #') }}{{ $products->SKU }}</span>
                            </li>
                            <li>
                                @if ($products->quantity == 0)
                                    <span class="badge-pill badge-soft-danger">{{ __('Out of stock') }}</span>
                                @else
                                    <span class="badge-pill badge-soft-success">{{ __('In stock') }}</span>
                                @endif
                            </li>
                        </ul>
                        <button type="button" class="btn btn-secondary active text-link">
                            <span class="btn-inner--icon variant_qty">
                                @if ($products->enable_product_variant == 'on')
                                    0
                                @else
                                    {{ $products->quantity }}
                                @endif
                            </span>
                            <span class="btn-inner--text">
                                {{ __('Total Avl.Quantity') }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('custom/js/slick.min.js') }}"></script>
    @if (isset($data->value) && $data->value == 'on')
        <script src="{{ asset('custom/js/rtl-custom.js') }}"></script>
    @else
        <script src="{{ asset('custom/js/custom.js') }}"></script>
    @endif
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
            s.parentNode.insertBefore()
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ !empty($store_settings->facebook_pixel) }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ $store_settings->facebook_pixel }}&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->

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
</body>

</html>
