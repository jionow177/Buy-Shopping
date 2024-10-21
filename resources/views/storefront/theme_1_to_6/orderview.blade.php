@php
    $s_logo = \App\Models\Utility::get_file('uploads/store_logo/');
@endphp

@foreach ($all_orders as $order_key => $order)
    <div class="content-body modal-body">

        <div class="section-title d-flex align-items-center justify-content-between">
            <a href="#" onclick="saveAsPDF();" data-toggle="tooltip" data-title="{{ __('Download') }}"
                id="download-buttons" class="btn btn-white btn-icon">

                <span class="btn-inner--text text-dark">{{ __('Print') }}</span>
            </a>
            @if ($order->status == 'pending')
                <span class="badge bg-warning rounded-pill">
                    {{ __('Pending') }}:
                    {{ \App\Models\Utility::dateFormat($order->created_at) }}
                </span>
            @elseif($order->status == 'Cancel Order')
                <span class="badge bg-danger rounded-pill">
                    {{ __('Order Canceled') }}:
                    {{ \App\Models\Utility::dateFormat($order->created_at) }}
                </span>
            @else
                <span class="badge bg-success rounded-pill">
                    {{ __('Delivered') }}:
                    {{ \App\Models\Utility::dateFormat($order->created_at) }}
                </span>
            @endif
        </div>
        <div id="printableArea">
            <div class="row row-gap ">
                <div class="col-lg-7 col-12">
                    <div class="order-detail-card">
                        <div class="detail-header">
                            <h6>{{ __('Items from Order') }} {{ $order->order_id }}</h6>
                        </div>
                        <div class="tabel-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ __('Item') }}</th>
                                        <th>{{ __('Quantity') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sub_tax = 0;
                                        $total = 0;
                                    @endphp
                                    @foreach ($order->order_products->products as $key => $product)
                                        @if ($product->variant_id != 0)
                                            <tr>
                                                <td>
                                                    {{ $product->product_name . ' - ( ' . $product->variant_name . ' )' }}
                                                    {{-- Yellow Cotton Hoodie - ( Red ) <small>SGST 10% (18)</small> --}}

                                                    @if (!empty($product->tax))
                                                        @php
                                                            $total_tax = 0;
                                                        @endphp
                                                        @foreach ($product->tax as $tax)
                                                            @php
                                                                $sub_tax = ($product->variant_price * $product->quantity * $tax->tax) / 100;
                                                                $total_tax += $sub_tax;
                                                            @endphp
                                                            {{ $tax->tax_name . ' ' . $tax->tax . '%' . ' (' . $sub_tax . ')' }}
                                                        @endforeach
                                                    @else
                                                        @php
                                                            $total_tax = 0;
                                                        @endphp
                                                    @endif
                                                </td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>
                                                    {{ App\Models\Utility::priceFormat($product->variant_price) }}
                                                </td>
                                                <td>{{ App\Models\Utility::priceFormat($product->variant_price * $product->quantity + $total_tax) }}
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    {{ $product->product_name }}
                                                    @if (!empty($product->tax))
                                                        @php
                                                            $total_tax = 0;
                                                        @endphp
                                                        @foreach ($product->tax as $tax)
                                                            @php
                                                                $sub_tax = ($product->price * $product->quantity * $tax->tax) / 100;
                                                                $total_tax += $sub_tax;
                                                            @endphp
                                                            {{ $tax->tax_name . ' ' . $tax->tax . '%' . ' (' . $sub_tax . ')' }}
                                                        @endforeach
                                                    @else
                                                        @php
                                                            $total_tax = 0;
                                                        @endphp
                                                    @endif
                                                </td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>
                                                    {{ App\Models\Utility::priceFormat($product->price) }}
                                                </td>
                                                <td>
                                                    {{ App\Models\Utility::priceFormat($product->price * $product->quantity + $total_tax) }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($order->status == 'delivered')
                            <div class="card card-body mb-0 py-0">
                                <div class="section-title d-flex align-items-center justify-content-between">
                                    <div class="col-md-6">
                                        <span class="h6 text-muted d-inline-block mr-3 mb-0"></span>
                                        <span class="h5 mb-0">{{ __('Get your product from here') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <button data-id="{{ $order->id }}"
                                            data-value="{{ asset(Storage::url('uploads/downloadable_prodcut' . '/' . $product->downloadable_prodcut)) }}"
                                            class="btn btn-sm btn-icon btn-secondry downloadable_prodcut">{{ __('Download') }}</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="order-detail-card">
                        <div class="detail-header">
                            <h6>{{ __('Items from Order ') . $order->order_id }}</h6>
                        </div>
                        <div class="detail-card-body">
                            <ul class="order-summery">
                                <li>
                                    <span class="sum-left">{{ __('Sub Total') }} :</span>
                                    <span
                                        class="sum-right">{{ App\Models\Utility::priceFormat($order->sub_total) }}</span>
                                </li>
                                <li>
                                    <span class="sum-left">{{ __('Estimated Tax') }} :</span>
                                    <span
                                        class="sum-right">{{ App\Models\Utility::priceFormat($order->final_taxs) }}</span>
                                </li>
                                @if (!empty($order->discount_price))
                                    <li>
                                        <span class="sum-left">{{ __('Apply Coupon') }} :</span>
                                        <span class="sum-right">{{ $order->discount_price }}</span>
                                    </li>
                                @endif
                                @if (!empty($order->shipping_data))
                                    @if (!empty($order->discount_value))
                                        <li>
                                            <span class="sum-left">{{ __('Shipping Price') }} :</span>
                                            <span
                                                class="sum-right">{{ App\Models\Utility::priceFormat($order->shipping_data->shipping_price) }}</span>
                                        </li>
                                        <li>
                                            <span class="sum-left">{{ __('Grand Total') }} :</span>
                                            <span
                                                class="sum-right">{{ App\Models\Utility::priceFormat($order->grand_total + $order->shipping_data->shipping_price - $order->discount_value) }}</span>
                                        </li>
                                        <li>
                                            <span class="sum-left">{{ __('Payment Type') }} :</span>
                                            <span class="sum-right">{{ $order['payment_type'] }}</span>
                                        </li>
                                    @else
                                        <li>
                                            <span class="sum-left">{{ __('Shipping Price') }} :</span>
                                            <span
                                                class="sum-right">{{ App\Models\Utility::priceFormat($order->shipping_data->shipping_price) }}</span>
                                        </li>
                                        <li>
                                            <span class="sum-left">{{ __('Grand Total') }} :</span>
                                            <span
                                                class="sum-right">{{ App\Models\Utility::priceFormat($order->grand_total + $order->shipping_data->shipping_price) }}</span>
                                        </li>
                                        <li>
                                            <span class="sum-left">{{ __('Payment Type') }} :</span>
                                            <span class="sum-right">{{ $order['payment_type'] }}</span>
                                        </li>
                                    @endif
                                @elseif(!empty($order->discount_value))
                                    <li>
                                        <span class="sum-left">{{ __('Grand Total') }} :</span>
                                        <span
                                            class="sum-right">{{ App\Models\Utility::priceFormat($order->grand_total - $order->discount_value) }}</span>
                                    </li>
                                    <li>
                                        <span class="sum-left">{{ __('Payment Type') }} :</span>
                                        <span class="sum-right">{{ $order['payment_type'] }}</span>
                                    </li>
                                @else
                                    <li>
                                        <span class="sum-left">{{ __('Grand Total') }} :</span>
                                        <span
                                            class="sum-right">{{ App\Models\Utility::priceFormat($order->price) }}</span>
                                    </li>
                                    <li>
                                        <span class="sum-left">{{ __('Payment Type') }} :</span>
                                        <span class="sum-right">{{ $order['payment_type'] }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="order-detail-card">
                        <div class="detail-header">
                            <h6>{{ __('Shipping Information') }}</h6>
                        </div>
                        <div class="detail-card-body">
                            <ul class="address-info">
                                <li>
                                    <span class="address-left">{{ __('Name') }}</span>
                                    <span class="address-right">{{ $order->user_details->name }}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{ __('Phone') }}</span>
                                    <span class="address-right">{{ $order->user_details->phone }}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{ __('Billing Address') }}</span>
                                    <span class="address-right">{{ $order->user_details->billing_address }}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{ __('Shipping Address') }}</span>
                                    <span class="address-right">{{ $order->user_details->shipping_address }}</span>
                                </li>
                                @if (!empty($order->location_data && $order->shipping_data))
                                    <li>
                                        <span class="address-left">{{ __('Location') }}</span>
                                        <span class="address-right">{{ $order->location_data->name }}</span>
                                    </li>
                                    <li>
                                        <span class="address-left">{{ __('Shipping Method') }}</span>
                                        <span class="address-right">{{ $order->shipping_data->shipping_name }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="order-detail-card">
                        <div class="detail-header">
                            <h6>{{ __('Billing Information') }}</h6>
                        </div>
                        <div class="detail-card-body">
                            <ul class="address-info">
                                <li>
                                    <span class="address-left">{{ __('Name') }}</span>
                                    <span class="address-right">{{ $order->user_details->name }}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{ __('Phone') }}</span>
                                    <span class="address-right">{{ $order->user_details->phone }}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{ __('Billing Address') }}</span>
                                    <span class="address-right">{{ $order->user_details->billing_address }}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{ __('Shipping Address') }}</span>
                                    <span class="address-right">{{ $order->user_details->shipping_address }}</span>
                                </li>
                                @if (!empty($order->location_data && $order->shipping_data))
                                    <li>
                                        <span class="address-left">{{ __('Location') }}</span>
                                        <span class="address-right">{{ $order->location_data->name }}</span>
                                    </li>
                                    <li>
                                        <span class="address-left">{{ __('Shipping Method') }}</span>
                                        <span class="address-right"> {{ $order->shipping_data->shipping_name }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @if (
                    !empty($store['custom_field_title_1']) ||
                        !empty($user_details->custom_field_title_1) ||
                        !empty($store['custom_field_title_2']) ||
                        !empty($user_details->custom_field_title_2) ||
                        !empty($store['custom_field_title_3']) ||
                        !empty($user_details->custom_field_title_3) ||
                        !empty($store['custom_field_title_4']) ||
                        !empty($user_details->custom_field_title_4))
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="order-detail-card">
                            <div class="detail-header">
                                <h6>{{ __('Extra Information') }}</h6>
                            </div>
                            <div class="detail-card-body">
                                <ul class="address-info">
                                    <li>
                                        <span class="address-left">{{ $store['custom_field_title_1'] }}</span>
                                        <span
                                            class="address-right">{{ $order->user_details->custom_field_title_1 }}</span>
                                    </li>
                                    <li>
                                        <span class="address-left">{{ $store['custom_field_title_2'] }}</span>
                                        <span
                                            class="address-right">{{ $order->user_details->custom_field_title_2 }}</span>
                                    </li>
                                    <li>
                                        <span class="address-left">{{ $store['custom_field_title_3'] }}</span>
                                        <span
                                            class="address-right">{{ $order->user_details->custom_field_title_3 }}</span>
                                    </li>
                                    <li>
                                        <span class="address-left"> {{ $store['custom_field_title_4'] }}</span>
                                        <span
                                            class="address-right">{{ $order->user_details->custom_field_title_4 }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach
<script type="text/javascript" src="{{ asset('custom/js/html2pdf.bundle.min.js') }}"></script>
<script>
    var filename = $('#filesname').val();

    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var logo_html = $('#invoice_logo_img').html();
        $('.invoice_logo').empty();
        $('.invoice_logo').html(logo_html);

        var opt = {
            margin: 0.3,
            filename: filename,
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 4,
                dpi: 72,
                letterRendering: true
            },
            jsPDF: {
                unit: 'in',
                format: 'A2'
            }
        };

        html2pdf().set(opt).from(element).save();
        setTimeout(function() {
            $('.invoice_logo').empty();
        }, 0);
    }

    $(document).on('click', '.downloadable_prodcut', function() {

        var download_product = $(this).attr('data-value');
        var order_id = $(this).attr('data-id');

        var data = {
            download_product: download_product,
            order_id: order_id,
        }

        $.ajax({
            url: '{{ route('user.downloadable_prodcut', $store->slug) }}',
            method: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.status == 'success') {
                    show_toastr("success", data.message + '<br> <b>' + data.msg + '<b>', data[
                        "status"]);
                    $('.downloadab_msg').html('<span class="text-success">' + data.msg + '</sapn>');
                } else {
                    show_toastr("Error", data.message + '<br> <b>' + data.msg + '<b>', data[
                        "status"]);
                }
            }
        });
    });
</script>
