<style>
    .product_item .product_details_icon {
        visibility: hidden;
    }

    .product_item:hover .product_details_icon {
        visibility: visible;
    }

    .product_details_active {
        color: #ffffff;
        font-size: 18px;
        margin-left: 12px;
    }

    .collection-list .btn-addcart {
        margin-left: 12px;
    }

    .modal-lg {
        max-width: 70% !important;
    }

    @media only screen and (max-width: 991px) {
        .modal.fade.show {
            z-index: 9999;
        }

        .modal-lg {
            max-width: 100% !important;
        }
    }
</style>
@php
    \App::setLocale(isset($store->lang) ? $store->lang : 'en');
    $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
@endphp
@if ($flag == 'my_orders')

<div class="section-title d-flex align-items-center justify-content-between" style="margin:20px">
        <h4><b>{{ __('Products you purchased') }}</b></h4>
        <a href="" class="btn btn-secondary">{{ __('Back to home') }}</a>
    </div>
    <div class="purchased-list">
        <table class="purchased-tabel">
            <thead>
                <tr>
                    <th scope="col">{{ __('Order') }}</th>
                    <th scope="col" class="sort">{{ __('Date') }}</th>
                    <th scope="col" class="sort">{{ __('Value') }}</th>
                    <th scope="col" class="sort">{{ __('Payment Type') }}</th>
                    <th scope="col" class="sort text-center">{{ __('Status') }}</th>
                    <th scope="col" class="text-right">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order_key => $order_items)
                    <tr>
                        <td>
                            <a href="#" data-size="lg"
                                data-url="{{ route('store.product.product_order_view', [$order_items->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                data-title="{{ $order_items->order_id }}" data-ajax-popup="true">
                                <span class="btn-inner--text">{{ $order_items->order_id }}</span>
                        </td>
                        <td>
                            <span
                                class="h6 text-sm font-weight-bold mb-0">{{ \App\Models\Utility::dateFormat($order_items->created_at) }}</span>
                        </td>
                        <td>
                            <span
                                class="value text-sm mb-0">{{ \App\Models\Utility::priceFormat($order_items->price) }}</span>
                        </td>
                        <td>
                            <span class="taxes text-sm mb-0">{{ $order_items->payment_type }}</span>
                        </td>
                        <td>
                            <div class="actions-wrapper">
                                @if ($order_items->status == 'pending')
                                    <span class="badge bg-warning rounded-pill">
                                        {{ __('Pending') }}:
                                        {{ \App\Models\Utility::dateFormat($order_items->created_at) }}
                                    </span>
                                @elseif($order_items->status == 'Cancel Order')
                                    <span class="badge bg-danger rounded-pill">
                                        {{ __('Order Canceled') }}:
                                        {{ \App\Models\Utility::dateFormat($order_items->created_at) }}
                                    </span>
                                @else
                                    <span class="badge bg-success rounded-pill">
                                        {{ __('Delivered') }}:
                                        {{ \App\Models\Utility::dateFormat($order_items->created_at) }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td><a href="#"
                                data-url="{{ route('store.product.product_order_view', [$order_items->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                data-toggle="tooltip" class="view-btn" data-title="{{ $order_items->id }}"
                                data-ajax-popup="true" data-size="lg">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    contentscripttype="text/ecmascript" contentstyletype="text/css"
                                    enable-background="new 0 0 2048 2048" height="2048px" id="Layer_1"
                                    preserveAspectRatio="xMidYMid meet" version="1.1" viewBox="0.0 0 1792.0 2048"
                                    width="1792.0px" xml:space="preserve" zoomAndPan="magnify">
                                    <path
                                        d="M1664,1088c-101.333-157.333-228.333-275-381-353c40.667,69.333,61,144.333,61,225c0,123.333-43.833,228.833-131.5,316.5  S1019.333,1408,896,1408s-228.833-43.833-316.5-131.5S448,1083.333,448,960c0-80.667,20.333-155.667,61-225  c-152.667,78-279.667,195.667-381,353c88.667,136.667,199.833,245.5,333.5,326.5S740,1536,896,1536s300.833-40.5,434.5-121.5  S1575.333,1224.667,1664,1088z M944,704c0-13.333-4.667-24.667-14-34s-20.667-14-34-14c-83.333,0-154.833,29.833-214.5,89.5  S592,876.667,592,960c0,13.333,4.667,24.667,14,34s20.667,14,34,14s24.667-4.667,34-14s14-20.667,14-34  c0-57.333,20.333-106.333,61-147s89.667-61,147-61c13.333,0,24.667-4.667,34-14S944,717.333,944,704z M1792,1088  c0,22.667-6.667,45.667-20,69c-93.333,153.333-218.833,276.167-376.5,368.5S1071.333,1664,896,1664s-341.833-46.333-499.5-139  S113.333,1309.667,20,1157c-13.333-23.333-20-46.333-20-69s6.667-45.667,20-69c93.333-152.667,218.833-275.333,376.5-368  S720.667,512,896,512s341.833,46.333,499.5,139s283.167,215.333,376.5,368C1785.333,1042.333,1792,1065.333,1792,1088z">
                                    </path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    @foreach ($products as $key => $items)
        {{-- @dd($loop->iteration) --}}
        <div
            class="tab-content collection-items {{ $loop->iteration != 1 ? 'd-none' : 'active' }} {{ $loop->iteration }}{!! str_replace(' ', '_', $key) !!} product_tableese">
            <div class="row o-gutters ">
                @foreach ($items as $product)
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 product-card theme-colored-card product_item h-grid"
                        data-name="{{ $product->name }}">
                        <div class="product-card-inner">
                            <a href="#" class="img-like product_details_icon"
                                data-url="{{ $flag != 'my_orders' ? route('store.product.product_view', [$store->slug, $product->id]) : route('store.product.product_order_view', [$product->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                data-title="{{ $product->name }}" data-ajax-popup="true">
                            </a>
                            <a href="#" class="img-like" data-size="lg"
                                data-url="{{ $flag != 'my_orders' ? route('store.product.product_view', [$store->slug, $product->id]) : route('store.product.product_order_view', [$product->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                data-title="{{ $product->name }}" data-ajax-popup="true">

                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    contentScriptType="text/ecmascript" contentStyleType="text/css"
                                    enable-background="new 0 0 2048 2048" height="2048px" id="Layer_1"
                                    preserveAspectRatio="xMidYMid meet" version="1.1" viewBox="0.0 0 1792.0 2048"
                                    width="1792.0px" xml:space="preserve" zoomAndPan="magnify">
                                    <path
                                        d="M1664,1088c-101.333-157.333-228.333-275-381-353c40.667,69.333,61,144.333,61,225c0,123.333-43.833,228.833-131.5,316.5  S1019.333,1408,896,1408s-228.833-43.833-316.5-131.5S448,1083.333,448,960c0-80.667,20.333-155.667,61-225  c-152.667,78-279.667,195.667-381,353c88.667,136.667,199.833,245.5,333.5,326.5S740,1536,896,1536s300.833-40.5,434.5-121.5  S1575.333,1224.667,1664,1088z M944,704c0-13.333-4.667-24.667-14-34s-20.667-14-34-14c-83.333,0-154.833,29.833-214.5,89.5  S592,876.667,592,960c0,13.333,4.667,24.667,14,34s20.667,14,34,14s24.667-4.667,34-14s14-20.667,14-34  c0-57.333,20.333-106.333,61-147s89.667-61,147-61c13.333,0,24.667-4.667,34-14S944,717.333,944,704z M1792,1088  c0,22.667-6.667,45.667-20,69c-93.333,153.333-218.833,276.167-376.5,368.5S1071.333,1664,896,1664s-341.833-46.333-499.5-139  S113.333,1309.667,20,1157c-13.333-23.333-20-46.333-20-69s6.667-45.667,20-69c93.333-152.667,218.833-275.333,376.5-368  S720.667,512,896,512s341.833,46.333,499.5,139s283.167,215.333,376.5,368C1785.333,1042.333,1792,1065.333,1792,1088z" />
                                </svg>
                            </a>
                            <div class="product-card-image">
                                <div class="product-card-image">
                                    <a href="#" data-size="lg"
                                        data-url="{{ $flag != 'my_orders' ? route('store.product.product_view', [$store->slug, $product->id]) : route('store.product.product_order_view', [$product->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                        data-title="{{ $product->name }}" data-ajax-popup="true">
                                        <img src="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'is_cover_image.png') }}"
                                            alt="logitech Keys">
                                    </a>
                                    {{-- <a href="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                        target="_blank" tabindex="0">
                                        <img src="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                            class="default-img" target="_blank">
                                    </a> --}}
                                </div>
                            </div>
                            <div class="product-content">
                                <div class="product-content-top">
                                    <h4>
                                        <a id="view" href="#" data-size="lg"
                                                data-url="{{ $flag != 'my_orders' ? route('store.product.product_view', [$store->slug, $product->id]) : route('store.product.product_order_view', [$product->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                                data-title="{{ $product->name }}" data-ajax-popup="true">
                                                <h4 class="title">{{ $product->name }}</h4>
                                            </a>
                                    </h4>
                                    <div class="top-place-info">
                                        <div class="new-labl">
                                            {{ isset($product->categories) && !empty($product->categories) ? $product->categories->name : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="product-content-bottom">
                                    @if ($product->enable_product_variant == 'on')
                                        <a href="#" class="variant-btn ">{{ __('In Variant') }}</a>
                                    @else
                                        <a href="#"
                                            class="variant-btn ">{{ \App\Models\Utility::priceFormat($product->price) }}</a>
                                    @endif
                                    <div class="product-btn">
                                        @if ($flag != 'my_orders')
                                            @if ($product->enable_product_variant == 'on')
                                                <a href="#" class="btn btn-addcart btn-secondary modal-target"
                                                    data-size="lg"
                                                    data-url="{{ route('store-variant.variant', [$store->slug, $product->id]) }}"
                                                    data-ajax-popup="true"
                                                    data-name="custom-addcart"
                                                    data-title="{{ __('Add Variant') }}">{{ __('Add To Cart') }}
                                                    <svg class="cart-icon" width="14" height="12"
                                                        viewBox="0 0 14 12" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M2.70269 4.66667H5.14854H9.14854H11.5944L10.7583 10.1014C10.7082 10.4266 10.4284 10.6667 10.0994 10.6667H4.19771C3.86866 10.6667 3.58883 10.4266 3.5388 10.1014L2.70269 4.66667ZM9.81521 2.66667V3.33333H11.5944H13.1485C13.5167 3.33333 13.8152 3.63181 13.8152 4C13.8152 4.36819 13.5167 4.66667 13.1485 4.66667H12.928C12.9279 4.73342 12.9227 4.80113 12.9122 4.86941L12.0761 10.3041C11.926 11.2798 11.0865 12 10.0994 12H4.19771C3.21057 12 2.37107 11.2798 2.22097 10.3041L1.38486 4.86941C1.37435 4.80113 1.3692 4.73342 1.36907 4.66667H1.14854C0.780349 4.66667 0.481873 4.36819 0.481873 4C0.481873 3.63181 0.780349 3.33333 1.14854 3.33333H2.70269H4.48187V2.66667C4.48187 1.19391 5.67578 0 7.14854 0C8.6213 0 9.81521 1.19391 9.81521 2.66667ZM5.81521 2.66667V3.33333H8.48187V2.66667C8.48187 1.93029 7.88492 1.33333 7.14854 1.33333C6.41216 1.33333 5.81521 1.93029 5.81521 2.66667ZM7.14854 9.33333C6.78035 9.33333 6.48187 9.03486 6.48187 8.66667V6.66667C6.48187 6.29848 6.78035 6 7.14854 6C7.51673 6 7.81521 6.29848 7.81521 6.66667V8.66667C7.81521 9.03486 7.51673 9.33333 7.14854 9.33333ZM4.48187 8.66667C4.48187 9.03486 4.78035 9.33333 5.14854 9.33333C5.51673 9.33333 5.81521 9.03486 5.81521 8.66667V6.66667C5.81521 6.29848 5.51673 6 5.14854 6C4.78035 6 4.48187 6.29848 4.48187 6.66667V8.66667ZM8.48187 8.66667C8.48187 9.03486 8.78035 9.33333 9.14854 9.33333C9.51673 9.33333 9.81521 9.03486 9.81521 8.66667V6.66667C9.81521 6.29848 9.51673 6 9.14854 6C8.78035 6 8.48187 6.29848 8.48187 6.66667V8.66667Z"
                                                            fill="white">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="#"
                                                    class="btn btn-secondary btn btn-addcart add_to_cart"
                                                    data-id="{{ $product->id }}">{{ __('Add To Cart') }}
                                                    <svg class="cart-icon" width="14" height="12"
                                                        viewBox="0 0 14 12" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M2.70269 4.66667H5.14854H9.14854H11.5944L10.7583 10.1014C10.7082 10.4266 10.4284 10.6667 10.0994 10.6667H4.19771C3.86866 10.6667 3.58883 10.4266 3.5388 10.1014L2.70269 4.66667ZM9.81521 2.66667V3.33333H11.5944H13.1485C13.5167 3.33333 13.8152 3.63181 13.8152 4C13.8152 4.36819 13.5167 4.66667 13.1485 4.66667H12.928C12.9279 4.73342 12.9227 4.80113 12.9122 4.86941L12.0761 10.3041C11.926 11.2798 11.0865 12 10.0994 12H4.19771C3.21057 12 2.37107 11.2798 2.22097 10.3041L1.38486 4.86941C1.37435 4.80113 1.3692 4.73342 1.36907 4.66667H1.14854C0.780349 4.66667 0.481873 4.36819 0.481873 4C0.481873 3.63181 0.780349 3.33333 1.14854 3.33333H2.70269H4.48187V2.66667C4.48187 1.19391 5.67578 0 7.14854 0C8.6213 0 9.81521 1.19391 9.81521 2.66667ZM5.81521 2.66667V3.33333H8.48187V2.66667C8.48187 1.93029 7.88492 1.33333 7.14854 1.33333C6.41216 1.33333 5.81521 1.93029 5.81521 2.66667ZM7.14854 9.33333C6.78035 9.33333 6.48187 9.03486 6.48187 8.66667V6.66667C6.48187 6.29848 6.78035 6 7.14854 6C7.51673 6 7.81521 6.29848 7.81521 6.66667V8.66667C7.81521 9.03486 7.51673 9.33333 7.14854 9.33333ZM4.48187 8.66667C4.48187 9.03486 4.78035 9.33333 5.14854 9.33333C5.51673 9.33333 5.81521 9.03486 5.81521 8.66667V6.66667C5.81521 6.29848 5.51673 6 5.14854 6C4.78035 6 4.48187 6.29848 4.48187 6.66667V8.66667ZM8.48187 8.66667C8.48187 9.03486 8.78035 9.33333 9.14854 9.33333C9.51673 9.33333 9.81521 9.03486 9.81521 8.66667V6.66667C9.81521 6.29848 9.51673 6 9.14854 6C8.78035 6 8.48187 6.29848 8.48187 6.66667V8.66667Z"
                                                            fill="white">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endif
