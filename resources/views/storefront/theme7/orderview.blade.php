@php
    $s_logo = \App\Models\Utility::get_file('uploads/store_logo/');
@endphp
@foreach ($all_orders as $order_key => $order)
    <div class="modal-body">
        <div class="order-view-body">
            <div class="order-view-header">
                <div class="title-left">
                    <h5 class="order">{{ __('Items from Order') }} <b>{{ $order->order_id }}</b></h5>
                </div>
                <div class="sub-header">
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
                    <a href="#" onclick="saveAsPDF();" data-toggle="tooltip" data-title="{{ __('Download') }}"
                        class="print-btn">{{ __('Print') }} <svg xmlns="http://www.w3.org/2000/svg" width="25"
                            height="24" viewBox="0 0 25 24" fill="none">
                            <path opacity="0.4"
                                d="M16.4622 5H17.4622C19.4622 5 20.4622 6 20.4622 8V18C20.4622 20 19.4622 21 17.4622 21H7.46216C5.46216 21 4.46216 20 4.46216 18V8C4.46216 6 5.46216 5 7.46216 5H8.46216"
                                fill="#0CAF60" />
                            <path
                                d="M16.4622 4.5V5.5C16.4622 6.5 15.9622 7 14.9622 7H9.96216C8.96216 7 8.46216 6.5 8.46216 5.5V4.5C8.46216 3.5 8.96216 3 9.96216 3H14.9622C15.9622 3 16.4622 3.5 16.4622 4.5Z"
                                fill="#0CAF60" />
                            <path
                                d="M11.6292 15.75C11.4372 15.75 11.2451 15.6771 11.0991 15.5301L9.43215 13.863C9.13915 13.57 9.13915 13.095 9.43215 12.802C9.72515 12.509 10.2002 12.509 10.4932 12.802L11.6302 13.938L14.4331 11.135C14.7261 10.842 15.2012 10.842 15.4942 11.135C15.7872 11.428 15.7872 11.903 15.4942 12.196L12.1612 15.529C12.0132 15.677 11.8212 15.75 11.6292 15.75Z"
                                fill="#0CAF60" />
                        </svg></a>
                </div>
            </div>
            <div id="printableArea">
                <div class="order-view-details">
                    <div class="order-view-left">
                        <div class="table-responsive">
                            <table class="order-view">
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
                    </div>
                    <div class="order-view-right">
                        <div class="order-subtotal">
                            <ul>
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
                <div class="order-view-footer">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12 vf-info">
                            <h6>{{ __('Shipping Information') }}</h6>
                            <ul>
                                <li>
                                    <span class="il-left">{{ __('Name') }}</span>
                                    <span class="il-right">{{ $order->user_details->name }}</span>
                                </li>
                                <li>
                                    <span class="il-left">{{ __('Phone') }}</span>
                                    <span class="il-right">{{ $order->user_details->phone }}</span>
                                </li>
                                <li>
                                    <span class="il-left">{{ __('Billing Address') }}</span>
                                    <span class="il-right">{{ $order->user_details->billing_address }}</span>
                                </li>
                                <li>
                                    <span class="il-left">{{ __('Shipping Address') }}</span>
                                    <span class="il-right">{{ $order->user_details->shipping_address }}</span>
                                </li>
                                @if (!empty($order->location_data && $order->shipping_data))
                                    <li>
                                        <span class="il-left">{{ __('Location') }}</span>
                                        <span class="il-right">{{ $order->location_data->name }}</span>
                                    </li>
                                    <li>
                                        <span class="il-left">{{ __('Shipping Method') }}</span>
                                        <span class="il-right">{{ $order->shipping_data->shipping_name }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12 vf-info">

                            <h6>{{ __('Billing Information') }}</h6>
                            <ul>
                                <li>
                                    <span class="il-left">{{ __('Name') }}</span>
                                    <span class="il-right">{{ $order->user_details->name }}</span>
                                </li>
                                <li>
                                    <span class="il-left">{{ __('Phone') }}</span>
                                    <span class="il-right">{{ $order->user_details->phone }}</span>
                                </li>
                                <li>
                                    <span class="il-left">{{ __('Billing Address') }}</span>
                                    <span class="il-right">{{ $order->user_details->billing_address }}</span>
                                </li>
                                <li>
                                    <span class="il-left">{{ __('Shipping Address') }}</span>
                                    <span class="il-right">{{ $order->user_details->shipping_address }}</span>
                                </li>
                                @if (!empty($order->location_data && $order->shipping_data))
                                    <li>
                                        <span class="il-left">{{ __('Location') }}</span>
                                        <span class="il-right">{{ $order->location_data->name }}</span>
                                    </li>
                                    <li>
                                        <span class="il-left">{{ __('Shipping Method') }}</span>
                                        <span class="il-right">{{ $order->shipping_data->shipping_name }}</span>
                                    </li>
                                @endif
                            </ul>
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
                            <div class="col-lg-4 col-12 vf-info">
                                <h6>{{ __('Extra Information') }}</h6>
                                <ul>
                                    <li>
                                        <span class="il-left">{{ $store['custom_field_title_1'] }}</span>
                                        <span class="il-right">{{ $order->user_details->custom_field_title_1 }}</span>
                                    </li>
                                    <li>
                                        <span class="il-left">{{ $store['custom_field_title_2'] }}</span>
                                        <span class="il-right">{{ $order->user_details->custom_field_title_2 }}</span>
                                    </li>
                                    <li>
                                        <span class="il-left">{{ $store['custom_field_title_3'] }}</span>
                                        <span class="il-right">{{ $order->user_details->custom_field_title_3 }}</span>
                                    </li>
                                    <li>
                                        <span class="il-left"> {{ $store['custom_field_title_4'] }}</span>
                                        <span class="il-right">{{ $order->user_details->custom_field_title_4 }}</span>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
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
