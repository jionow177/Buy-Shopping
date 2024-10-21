@php
    $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
@endphp
<div class="modal-body">
    <input type="hidden" id="product_id" value="{{ $products->id }}">
    <input type="hidden" id="variant_id" value="">
    <input type="hidden" id="variant_qty" value="">
    <div class="cart-variant-body">
        <div class="row">
            <div class="col-lg-4 col-md-5 col-12">
                <div class="cart-variant-img">
                    <div class="variant-main-media">
                        <img src="{{ $logo . (isset($products->is_cover) && !empty($products->is_cover) ? $products->is_cover : 'default_img.png') }}"
                            class="default-img" target="_blank" alt="logitech Keys">
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-12">
                <div class="cart-variant-detail">
                    <span
                        class="ctg-badge">{{ isset($products->categories) && !empty($products->categories) ? $products->categories->name : '' }}</span>
                    <h3>{{ $products->name }}</h3>
                    <div class="pv-selection">
                        @foreach ($product_variant_names as $key => $variant)
                            <label for="">{{ ucfirst($variant->variant_name) }}</label>
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
                    <div class="cart-variable">
                        <div class="variation_price1">
                            @if ($products->enable_product_variant == 'on')
                                <ins>{{__('Please Select Variants')}}</ins>
                            @else
                                <ins>{{ \App\Models\Utility::priceFormat($products->price) }}</ins>

                            @endif

                        </div>
                        <a href="#!" type="submit" class="btn add_to_cart_variant" data-toggle="tooltip" data-id="{{ $products->id }}">
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
</div>
<script type="text/javascript" src="{{ asset('custom/js/custom.js') }}"></script>
