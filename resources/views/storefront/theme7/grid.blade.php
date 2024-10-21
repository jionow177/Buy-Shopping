@php
    \App::setLocale(isset($store->lang) ? $store->lang : 'en');
    $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
@endphp
@if ($flag == 'my_orders')
    <div id="tab-1" class="tab-content active purchased-list">
        <div class="tab-content-body order-list-wrapper">
            <div class="table-responsive">
                <table class="order-list">
                    <tr>
                        <th>{{ __('Order') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Value') }}</th>
                        <th>{{ __('Payment type') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                    @foreach ($orders as $order_key => $order_items)
                        <tr>
                            <td>
                                <a href="#" data-size="lg"
                                    data-url="{{ route('store.product.product_order_view', [$order_items->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                    data-title="{{ $order_items->order_id }}" data-ajax-popup="true">
                                    <span class="btn-inner--text">{{ $order_items->order_id }}</span>
                            </td>
                            <td>{{ \App\Models\Utility::dateFormat($order_items->created_at) }}</td>
                            <td>{{ \App\Models\Utility::priceFormat($order_items->price) }}</td>
                            <td>{{ $order_items->payment_type }}</td>
                            <td>
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
                            </td>
                            <td>
                                <a href="#" class="ac-viewbtn"
                                    data-url="{{ route('store.product.product_order_view', [$order_items->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                    data-toggle="tooltip" class="view-btn order_view" data-title="{{ __('Your Order Details') }}"
                                   data-size="lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 18 18" fill="none">
                                        <path opacity="0.4"
                                            d="M15.1663 10.1416C15.5421 9.51286 15.5421 8.72933 15.1663 8.10063C14.3186 6.68155 12.4152 4.28491 9.23019 4.28491C6.04522 4.28491 4.1418 6.68155 3.29409 8.10063C2.91825 8.72933 2.91825 9.51286 3.29409 10.1416C4.1418 11.5606 6.04522 13.9573 9.23019 13.9573C12.4152 13.9573 14.3186 11.5606 15.1663 10.1416Z"
                                            fill="#25314C"></path>
                                        <path
                                            d="M9.23011 6.703C8.97724 6.703 8.73754 6.75276 8.50886 6.82462C8.64496 7.01115 8.72712 7.23912 8.72712 7.48784C8.72712 8.1124 8.22141 8.61811 7.59685 8.61811C7.34813 8.61811 7.12085 8.53527 6.93362 8.39985C6.86177 8.62923 6.81201 8.86823 6.81201 9.1211C6.81201 10.4566 7.89463 11.5392 9.23011 11.5392C10.5656 11.5392 11.6482 10.4566 11.6482 9.1211C11.6482 7.78562 10.5656 6.703 9.23011 6.703Z"
                                            fill="#25314C"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@else
    @foreach ($products as $key => $items)
        <div
            class="tab-content {{ $loop->iteration != 1 ? 'd-none' : 'active' }} {{ $loop->iteration }}{!! str_replace(' ', '_', $key) !!} product_tableese">
            <div class="tab-content-body">
                <div class="row o-gutters">
                    @foreach ($items as $product)
                        <div class="col-lg-3 col-sm-6 product-card product_item" data-name="{{ $product->name }}">
                            <div class="product-card-inner">
                                <div class="product-card-image">
                                    <a href="#" class="" data-size="lg"
                                        data-url="{{ $flag != 'my_orders' ? route('store.product.product_view', [$store->slug, $product->id]) : route('store.product.product_order_view', [$product->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                        data-title="{{ $product->name }}" data-ajax-popup="true">
                                        <img src="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                            class="default-img" target="_blank" alt="logitech Keys">
                                    </a>
                                </div>
                                <div class="product-content">
                                    @if (isset($product->categories))
                                        <span
                                            class="badge">{{ isset($product->categories) && !empty($product->categories) ? $product->categories->name : '' }}</span>
                                    @endif
                                    <a href="#" class="view-btn" data-size="lg"
                                        data-url="{{ $flag != 'my_orders' ? route('store.product.product_view', [$store->slug, $product->id]) : route('store.product.product_order_view', [$product->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                        data-title="{{ $product->name }}" data-ajax-popup="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 18 18" fill="none">
                                            <path opacity="0.4"
                                                d="M15.1663 10.1416C15.5421 9.51286 15.5421 8.72933 15.1663 8.10063C14.3186 6.68155 12.4152 4.28491 9.23019 4.28491C6.04522 4.28491 4.1418 6.68155 3.29409 8.10063C2.91825 8.72933 2.91825 9.51286 3.29409 10.1416C4.1418 11.5606 6.04522 13.9573 9.23019 13.9573C12.4152 13.9573 14.3186 11.5606 15.1663 10.1416Z"
                                                fill="#25314C" />
                                            <path
                                                d="M9.23011 6.703C8.97724 6.703 8.73754 6.75276 8.50886 6.82462C8.64496 7.01115 8.72712 7.23912 8.72712 7.48784C8.72712 8.1124 8.22141 8.61811 7.59685 8.61811C7.34813 8.61811 7.12085 8.53527 6.93362 8.39985C6.86177 8.62923 6.81201 8.86823 6.81201 9.1211C6.81201 10.4566 7.89463 11.5392 9.23011 11.5392C10.5656 11.5392 11.6482 10.4566 11.6482 9.1211C11.6482 7.78562 10.5656 6.703 9.23011 6.703Z"
                                                fill="#25314C" />
                                        </svg>
                                    </a>
                                    <div class="product-content-top">
                                        <h5><a id="view" href="#" data-size="lg"
                                                data-url="{{ $flag != 'my_orders' ? route('store.product.product_view', [$store->slug, $product->id]) : route('store.product.product_order_view', [$product->id, Auth::guard('customers')->user()->id, $store->slug]) }}"
                                                data-title="{{ $product->name }}"
                                                data-ajax-popup="true">{{ $product->name }}</a></h5>
                                        <p>{{ $product->SKU }}</p>
                                    </div>

                                    <div class="price">
                                        @if ($product->enable_product_variant == 'on')
                                            <ins>{{ __('In Variant') }}</ins>
                                        @else
                                            <ins>{{ \App\Models\Utility::priceFormat($product->price) }}</ins>
                                        @endif

                                    </div>
                                    @if ($flag != 'my_orders')
                                        @if ($product->enable_product_variant == 'on')
                                            <a href="#!" class="btn cart-btn btn-addcart modal-target"
                                                data-size="md"
                                                data-url="{{ route('store-variant.variant', [$store->slug, $product->id]) }}"
                                                data-ajax-popup="true" data-title="{{ __('Add Variant') }}"
                                                data-name="custom-addcart" id="add_to_cart">{{ __('Add To Cart') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15"
                                                    viewBox="0 0 16 15" fill="none">
                                                    <path
                                                        d="M6.23602 13.0429C5.83275 13.0429 5.50146 12.7151 5.50146 12.3112C5.50146 11.9074 5.8263 11.5796 6.23016 11.5796H6.23602C6.63988 11.5796 6.96765 11.9074 6.96765 12.3112C6.96765 12.7151 6.63988 13.0429 6.23602 13.0429Z"
                                                        fill="white" />
                                                    <path
                                                        d="M10.9186 13.0429C10.5154 13.0429 10.1841 12.7151 10.1841 12.3112C10.1841 11.9074 10.5089 11.5796 10.9128 11.5796H10.9186C11.3225 11.5796 11.6503 11.9074 11.6503 12.3112C11.6503 12.7151 11.3225 13.0429 10.9186 13.0429Z"
                                                        fill="white" />
                                                    <path opacity="0.4"
                                                        d="M12.0768 3.53173H5.01869L4.89638 2.7205C4.78283 1.91864 4.11206 1.33685 3.30259 1.33685H3.1504C2.90809 1.33685 2.71143 1.53351 2.71143 1.77583C2.71143 2.01814 2.90809 2.21481 3.1504 2.21481H3.30259C3.67074 2.21481 3.97511 2.47936 4.02778 2.8481L4.90631 8.96333C4.98825 9.54278 5.47989 9.97005 6.06519 9.97005H10.9056C12.1348 9.97005 12.4801 9.36133 12.6323 8.5302L13.2234 4.91305C13.3586 4.19312 12.8026 3.53173 12.0768 3.53173Z"
                                                        fill="white" />
                                                    <path
                                                        d="M8.16095 7.97008C8.04448 7.97008 7.93267 7.92385 7.85073 7.84132L7.07051 7.06111C6.89902 6.88962 6.89902 6.6116 7.07051 6.4401C7.24201 6.26861 7.52005 6.26861 7.69154 6.4401L8.16152 6.91011L9.41229 5.6599C9.58379 5.48841 9.86182 5.48841 10.0333 5.6599C10.2048 5.83139 10.2048 6.10941 10.0333 6.28091L8.47232 7.8419C8.38862 7.92384 8.27743 7.97008 8.16095 7.97008Z"
                                                        fill="white" />
                                                </svg>
                                            </a>
                                        @else
                                            <a href="#!" class="btn cart-btn btn-addcart add_to_cart"
                                                data-id="{{ $product->id }}" id="add_to_cart">{{ __('Add To Cart') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15"
                                                    viewBox="0 0 16 15" fill="none">
                                                    <path
                                                        d="M6.23602 13.0429C5.83275 13.0429 5.50146 12.7151 5.50146 12.3112C5.50146 11.9074 5.8263 11.5796 6.23016 11.5796H6.23602C6.63988 11.5796 6.96765 11.9074 6.96765 12.3112C6.96765 12.7151 6.63988 13.0429 6.23602 13.0429Z"
                                                        fill="white" />
                                                    <path
                                                        d="M10.9186 13.0429C10.5154 13.0429 10.1841 12.7151 10.1841 12.3112C10.1841 11.9074 10.5089 11.5796 10.9128 11.5796H10.9186C11.3225 11.5796 11.6503 11.9074 11.6503 12.3112C11.6503 12.7151 11.3225 13.0429 10.9186 13.0429Z"
                                                        fill="white" />
                                                    <path opacity="0.4"
                                                        d="M12.0768 3.53173H5.01869L4.89638 2.7205C4.78283 1.91864 4.11206 1.33685 3.30259 1.33685H3.1504C2.90809 1.33685 2.71143 1.53351 2.71143 1.77583C2.71143 2.01814 2.90809 2.21481 3.1504 2.21481H3.30259C3.67074 2.21481 3.97511 2.47936 4.02778 2.8481L4.90631 8.96333C4.98825 9.54278 5.47989 9.97005 6.06519 9.97005H10.9056C12.1348 9.97005 12.4801 9.36133 12.6323 8.5302L13.2234 4.91305C13.3586 4.19312 12.8026 3.53173 12.0768 3.53173Z"
                                                        fill="white" />
                                                    <path
                                                        d="M8.16095 7.97008C8.04448 7.97008 7.93267 7.92385 7.85073 7.84132L7.07051 7.06111C6.89902 6.88962 6.89902 6.6116 7.07051 6.4401C7.24201 6.26861 7.52005 6.26861 7.69154 6.4401L8.16152 6.91011L9.41229 5.6599C9.58379 5.48841 9.86182 5.48841 10.0333 5.6599C10.2048 5.83139 10.2048 6.10941 10.0333 6.28091L8.47232 7.8419C8.38862 7.92384 8.27743 7.97008 8.16095 7.97008Z"
                                                        fill="white" />
                                                </svg>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

@endif
