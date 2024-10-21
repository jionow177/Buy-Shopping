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
    <div class="product-view-body">
        <div class="row">
            <div class="col-lg-5 col-md-6 col-12">
                <div class="product-view-gallery">
                    <div class="swiper pdp-main-slider">
                        <div class="swiper-wrapper">
                            @if (!$products_image->isEmpty())
                                @foreach ($products_image as $key => $product)
                                    <div class="swiper-slide pdp-main-itm">
                                        <div class="pdp-main-media">
                                            <img src="{{ $p_logo . (isset($products_image[$key]->product_images) && !empty($products_image[$key]->product_images) ? $products_image[$key]->product_images : 'default_img.png') }}"
                                                alt="product-img">
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
                    </div>
                    <div class="swiper pdp-thumb-slider">
                        <div class="swiper-wrapper">
                            @if (is_object($products_image))
                                @foreach ($products_image as $key => $product)
                                    <div class="swiper-slide pdp-thumb-itm">
                                        <div class="pdp-thumb-media">
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
            </div>
            <div class="col-lg-7 col-md-6 col-12">
                <div class="pdp-summery">
                    <div class="summery-top-header d-flex  align-items-start  justify-content-between">
                        <div class="section-title">

                            <span
                                class="ctg-badge">{{ isset($products->categories) && !empty($products->categories) ? $products->categories->name : '' }}</span>
                            <h3>{{ $products->name }}</h3>
                            <p>{{ __('ID: #') }}{{ $products->SKU }}</p>
                        </div>
                        <div class="badge-right">
                            @if ($products->enable_product_variant == 'on')
                                @if ($products->quantity == 0)
                                    <span class="stc-badge variant_stock1 d-none"
                                        style="background :#FFA5A5 !important; color: #A33636 !important">{{ __('Out Of Stock') }}</span>
                                @else
                                    <span class="stc-badge variant_stock1 d-none">{{ __('In Stock') }}</span>
                                @endif
                            @else
                                @if ($products->quantity == 0)
                                    <span class="stc-badge-danger">{{ __('Out Of Stock') }}</span>
                                @else
                                    <span class="stc-badge">{{ __('In Stock') }}</span>
                                @endif
                            @endif
                            <span class="qty-badge">{{ __('Quantity:') }}
                                <span class="qty-badge variant_qty">
                                    @if ($products->enable_product_variant == 'on')
                                        {{ __('0') }}
                                    @else
                                        {{ $products->quantity }}
                                    @endif
                                </span>
                            </span>
                        </div>
                    </div>

                    <p>{!! $products->description !!}</p>
                    @if (!empty($products->custom_field_1) && !empty($products->custom_value_1))
                        <p>{{ $products->custom_field_1 }} : {{ $products->custom_value_1 }}</p>
                    @endif
                    @if (!empty($products->custom_field_2) && !empty($products->custom_value_2))
                        <p>{{ $products->custom_field_2 }} : {{ $products->custom_value_2 }}</p>
                    @endif
                    @if (!empty($products->custom_field_3) && !empty($products->custom_value_3))
                        <p>{{ $products->custom_field_3 }} : {{ $products->custom_value_3 }}</p>
                    @endif
                    @if (!empty($products->custom_field_4) && !empty($products->custom_value_4))
                        <p>{{ $products->custom_field_4 }} : {{ $products->custom_value_4 }}</p>
                    @endif
                    @if ($products->enable_product_variant == 'on')
                        <div class="pv-selection">
                            <input type="hidden" id="product_id" value="{{ $products->id }}">
                            <input type="hidden" id="variant_id" value="">
                            <input type="hidden" id="variant_qty" value="">
                            @foreach ($product_variant_names as $key => $variant)
                                <label class="title">
                                    {{ __('Select') }}{{ $variant->variant_name }}
                                </label>
                                {{-- <label for="">Select Generation type:</label> --}}
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
                    <div class="cart-variable">
                        <div class="price variation_price1">
                            @if ($products->enable_product_variant == 'on')
                                <span> {{ __('Please Select Variants') }}</span>
                            @else
                                <span>{{ \App\Models\Utility::priceFormat($products->price) }}</span>
                            @endif

                        </div>
                        <a href="#!" type="submit" class="btn add_to_cart_variant" data-toggle="tooltip"
                            data-id="{{ $products->id }}">
                            {{ __('Add to cart') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="29" height="28" viewBox="0 0 29 28"
                                fill="none">
                                <path
                                    d="M11.4044 25.9583C10.6005 25.9583 9.94019 25.305 9.94019 24.5C9.94019 23.695 10.5877 23.0417 11.3927 23.0417H11.4044C12.2094 23.0417 12.8627 23.695 12.8627 24.5C12.8627 25.305 12.2094 25.9583 11.4044 25.9583Z"
                                    fill="white"></path>
                                <path
                                    d="M20.7377 25.9583C19.9339 25.9583 19.2736 25.305 19.2736 24.5C19.2736 23.695 19.9211 23.0417 20.7261 23.0417H20.7377C21.5427 23.0417 22.1961 23.695 22.1961 24.5C22.1961 25.305 21.5427 25.9583 20.7377 25.9583Z"
                                    fill="white"></path>
                                <path opacity="0.4"
                                    d="M23.0465 7H8.97768L8.73387 5.383C8.50753 3.78466 7.17051 2.625 5.55701 2.625H5.25366C4.77066 2.625 4.37866 3.017 4.37866 3.5C4.37866 3.983 4.77066 4.375 5.25366 4.375H5.55701C6.29084 4.375 6.89752 4.90233 7.00252 5.63733L8.75366 17.8267C8.917 18.9817 9.89697 19.8333 11.0636 19.8333H20.712C23.162 19.8333 23.8503 18.62 24.1536 16.9633L25.3319 9.75333C25.6014 8.31833 24.4931 7 23.0465 7Z"
                                    fill="white"></path>
                                <path
                                    d="M15.2417 15.8468C15.0095 15.8468 14.7867 15.7547 14.6233 15.5902L13.0681 14.035C12.7263 13.6932 12.7263 13.139 13.0681 12.7972C13.41 12.4553 13.9642 12.4553 14.306 12.7972L15.2428 13.734L17.7359 11.242C18.0778 10.9002 18.632 10.9002 18.9738 11.242C19.3157 11.5838 19.3157 12.138 18.9738 12.4798L15.8623 15.5913C15.6955 15.7547 15.4739 15.8468 15.2417 15.8468Z"
                                    fill="white"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-view-footer">
        <div class="pvw-block">
            <span>{{ __('Stock Management') }}</span>
            <span class="qty-badge">{{ __('Quantity:') }}
                <span class="qty-badge variant_qty">
                    @if ($products->enable_product_variant == 'on')
                        {{ __('0') }}
                    @else
                        {{ $products->quantity }}
                    @endif
                </span>
            </span>
        </div>
        <div class="pvw-block">
            <span>{{ __('ID') }}</span>
            <span class="qty-badge">{{ $products->SKU }}/A</span>
        </div>
        <div class="pvw-block">
            <span>{{ __('Stock') }}</span>
            @if ($products->enable_product_variant == 'on')
                @if ($products->quantity == 0)
                    <span class="stc-badge variant_stock1 d-none"
                        style="background :#FFA5A5 !important; color: #A33636 !important">{{ __('Out Of Stock') }}</span>
                @else
                    <span class="stc-badge variant_stock1 d-none">{{ __('In Stock') }}</span>
                @endif
            @else
                @if ($products->quantity == 0)
                    <span class="stc-badge-danger">{{ __('Out Of Stock') }}</span>
                @else
                    <span class="stc-badge">{{ __('In Stock') }}</span>
                @endif
            @endif
        </div>
    </div>
</div>



@if (isset($data->value) && $data->value == 'on')
    <script src="{{ asset('custom/js/rtl-custom.js') }}"></script>
@else
    <script src="{{ asset('custom/js/custom.js') }}"></script>
@endif
