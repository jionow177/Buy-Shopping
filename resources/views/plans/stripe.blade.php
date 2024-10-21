@extends('layouts.admin')
@php
    $dir = asset(Storage::url('uploads/plan'));
@endphp
@push('script-page')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script type="text/javascript">
        @if (
            $plan->price > 0.0 &&
                isset($admin_payments_details['is_stripe_enabled']) &&
                $admin_payments_details['is_stripe_enabled'] == 'on')
            var stripe = Stripe('{{ $admin_payments_details['stripe_key'] }}');
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    // Add your base input styles here. For example:
                    fontSize: '14px',
                    color: '#32325d',
                },
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            // Create a token or display an error when the form is submitted.
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        $("#card-errors").html(result.error.message);
                        show_toastr('Error', result.error.message, 'error');
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });

            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }
        @endif
        function preparePayment(ele, payment) {
            var coupon = $(ele).closest('.row').find('.coupon').val();
            var amount = 0;
            $.ajax({
                url: '{{ route('plan.prepare.amount') }}',
                datType: 'json',
                data: {
                    plan_id: '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}',
                    coupon: coupon,
                    payment: payment
                },
                success: function(data) {
                    if (data.is_success == true) {
                        amount = data.price;
                        $('#coupon_use_id').val(data.coupon_id);
                        if (payment == 'paystack') {
                            payWithPaystack(amount);
                        }
                        if (payment == 'flutterwave') {
                            payWithRave(amount);
                        }
                        if (payment == 'razorpay') {
                            payRazorPay(amount);
                        }
                        if (payment == 'mercado') {
                            payMercado(amount);
                        }
                    } else if (data.final_price == 0) {
                        show_toastr('Success', 'Plan activated Successfully', 'success');
                        setTimeout(() => {
                            window.location.href = "{{ route('plans.index') }}";
                        }, 1500);
                    } else {
                        show_toastr('Error', 'Paymenent request failed', 'error');
                    }

                }
            })
        }
        @if (isset($admin_payments_details['is_paystack_enabled']) && $admin_payments_details['is_paystack_enabled'] == 'on')

            function payWithPaystack(amount) {

                var coupon_id = $('#coupon_use_id').val();
                var paystack_callback = "{{ url('/paystack-plan') }}";
                var handler = PaystackPop.setup({
                    key: '{{ $admin_payments_details['paystack_public_key'] }}',
                    email: '{{ Auth::user()->email }}',
                    amount: amount * 100,
                    currency: 'NGN',
                    ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                        1
                    ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                    metadata: {
                        custom_fields: [{
                            display_name: "Mobile Number",
                            variable_name: "mobile_number",
                        }]
                    },

                    callback: function(response) {
                        {{-- console.log(paystack_callback +'/'+ response.reference + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}'); --}}
                        window.location.href = paystack_callback + '/' + response.reference + '/' +
                            '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                            coupon_id;
                    },
                    onClose: function() {
                        alert('window closed');
                    }
                });
                handler.openIframe();
            }
        @endif
        @if (isset($admin_payments_details['is_flutterwave_enabled']) &&
                $admin_payments_details['is_flutterwave_enabled'] == 'on')
            {{-- Flutterwave JAVASCRIPT FUNCTION --}}

            function payWithRave(amount) {
                var coupon_id = $('#coupon_use_id').val();
                var API_publicKey = '{{ $admin_payments_details['flutterwave_public_key'] }}';
                var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                var flutter_callback = "{{ url('/flutterwave-plan') }}";
                var x = getpaidSetup({
                    PBFPubKey: API_publicKey,
                    customer_email: '{{ Auth::user()->email }}',
                    amount: amount,
                    currency: '{{ !empty($admin_payments_details['currency']) ? $admin_payments_details['currency'] : 'USD' }}',
                    txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' +
                        {{ date('Y-m-d') }},
                    meta: [{
                        metaname: "payment_id",
                        metavalue: "id"
                    }],
                    onclose: function() {},
                    callback: function(response) {

                        var txref = response.tx.txRef;

                        if (
                            response.tx.chargeResponseCode == "00" ||
                            response.tx.chargeResponseCode == "0"
                        ) {
                            window.location.href = flutter_callback + '/' + txref + '/' +
                                '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                                coupon_id;
                        } else {
                            // redirect to a failure page.
                        }
                        x.close(); // use this to close the modal immediately after payment.
                    }
                });
            }
        @endif
        @if (isset($admin_payments_details['is_razorpay_enabled']) && $admin_payments_details['is_razorpay_enabled'] == 'on')
            {{-- Razorpay JAVASCRIPT FUNCTION --}}
            @php
                $logo = asset(Storage::url('uploads/logo/'));
                if (\Auth::user()->type == 'Super Admin') {
                    $company_logo = Utility::get_superadmin_logo();
                } else {
                    $company_logo = Utility::get_company_logo();
                }
            @endphp

            function payRazorPay(amount) {
                var razorPay_callback = '{{ url('razorpay-plan') }}';
                var totalAmount = amount * 100;
                var coupon_id = $('#coupon_use_id').val();
                var options = {
                    "key": "{{ $admin_payments_details['razorpay_public_key'] }}", // your Razorpay Key Id
                    "amount": totalAmount,
                    "name": 'Plan',
                    "currency": '{{ !empty($admin_payments_details['currency']) ? $admin_payments_details['currency'] : 'USD' }}',
                    "description": "",
                    "image": "{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo.png') }}",
                    "handler": function(response) {
                        window.location.href = razorPay_callback + '/' + response.razorpay_payment_id + '/' +
                            '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' + coupon_id;
                    },
                    "theme": {
                        "color": "#528FF0"
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
            }
        @endif
        @if (isset($admin_payments_details['is_mercado_enabled']) && $admin_payments_details['is_mercado_enabled'] == 'on')
            {{-- Mercado JAVASCRIPT FUNCTION --}}

            function payMercado(amount) {
                var coupon_id = $('#coupon_use_id').val();
                var data = {
                    coupon_id: coupon_id,
                    total_price: amount,
                    plan: {{ $plan->id }},
                }
                console.log(data);
                $.ajax({
                    url: '{{ route('mercadopago.prepare.plan') }}',
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            window.location.href = data.url;
                        } else {
                            show_toastr("Error", data.error, data["status"]);
                        }
                    }
                });
            }
        @endif

        @if (
            $admin_payments_details['is_payfast_enabled'] == 'on' &&
                !empty($admin_payments_details['payfast_merchant_id']) &&
                !empty($admin_payments_details['payfast_merchant_key']))
            $(document).ready(function() {
                get_payfast_status(amount = 0, coupon = null);
            })

            function get_payfast_status(amount, coupon) {
                var plan_id = $('#plan_id').val();
                if (amount == 0.00 && coupon != null) {
                    $.ajax({
                        url: '{{ route('payfast_zero.payment') }}',
                        method: 'POST',
                        data: {
                            'plan_id': plan_id,
                            'coupon_amount': amount,
                            'coupon_code': coupon,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            show_toastr('Success', 'Plan activated Successfully', 'success');
                            setTimeout(() => {
                                window.location.href = "{{ route('plans.index') }}";
                            }, 1500);
                        }
                    });
                } else {
                    var plan_id = $('#plan_id').val();
                    var payfast_coupon = $('#payfast_coupon').val();
                    $.ajax({
                        method: 'POST',
                        url: '{{ route('payfast.payment') }}',
                        data: {
                            'plan_id': plan_id,
                            'coupon_amount': amount,
                            'coupon_code': payfast_coupon
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {

                            if (data.success == true) {
                                $('#get-payfast-inputs').append(data.inputs);

                            } else {
                                show_toastr('Error', data.inputs, 'error')
                            }
                        }
                    });
                }
            }
        @endif

        function addCommas(num) {
            var number = parseFloat(num).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            return ((site_currency_symbol_position == "pre") ? site_currency_symbol : '') + number + ((
                site_currency_symbol_position == "post") ? site_currency_symbol : '');
        }

        var site_currency_symbol_position = '{{ \App\Models\Utility::getValByName('currency_symbol_position') }}';
        var site_currency_symbol = '{{ \App\Models\Utility::getValByName('site_currency_symbol') }}';

        $(document).ready(function() {
            $(document).on('click', '.apply-coupon', function() {
                var ele = $(this);
                var coupon = ele.closest('.row').find('.coupon').val();
                $.ajax({
                    url: '{{ route('apply.coupon') }}',
                    datType: 'json',
                    data: {
                        plan_id: '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}',
                        coupon: coupon
                    },
                    success: function(data) {
                        $('.final-price').text(data.final_price);
                        $('#final_price_pay').val(data.price);
                        $('#mollie_total_price').val(data.price);
                        $('#skrill_total_price').val(data.price);
                        $('#coingate_total_price').val(data.price);
                        $('#stripe_coupon, #paypal_coupon, #skrill_coupon,#coingate_coupon,#toyyibpay_coupan,#bank_transfer_coupon')
                            .val(coupon);

                        var html = '';
                        // html += '<span data-value="' + data.final_price + '">' + data.final_price + '</span>'
                        //html += '<span data-value="' + data.final_price + '">' + data.final_price + '</span>'
                        html += data.final_price;
                        if (data.final_price != undefined) {
                            $('.final-price').html(addCommas(html));
                            $('.final-price value').html(html);
                        }

                        if (ele.closest($('#payfast-form')).length == 1) {
                            get_payfast_status(data.price, coupon);

                        }

                        if (data.is_success == true) {
                            show_toastr('Success', data.message, 'success');
                        } else if (data.is_success == false) {
                            show_toastr('Error', data.message, 'error');
                        } else {
                            show_toastr('Error', 'Coupon code is required', 'error');
                        }
                    }
                })
            });
        });
    </script>

    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,

        })
        $(".list-group-item").click(function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-primary');
        });
    </script>
@endpush
@php
    $dir = asset(Storage::url('uploads/plan'));
    $dir_payment = asset(Storage::url('uploads/payments'));
@endphp
@section('page-title')
    {{ __('Order Summary') }}
@endsection
@section('title')
    {{ __('Order Summary') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">{{ __('Plan') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Order Summary') }}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
    <div class="row ">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="sticky-top" style="top:30px">
                        <div class="card">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                @if (isset($admin_payments_details['is_manuallypay_enabled']) &&
                                        $admin_payments_details['is_manuallypay_enabled'] == 'on')
                                    <a href="#maually-payment"
                                        class="list-group-item list-group-item-action border-0 active">{{ __('Manually') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payments_details['is_bank_enabled']) && $admin_payments_details['is_bank_enabled'] == 'on')
                                    <a href="#bank-transfer-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Bank Transfer') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payments_details['is_stripe_enabled']) && $admin_payments_details['is_stripe_enabled'] == 'on')
                                    <a href="#stripe-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Stripe') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_paypal_enabled']) && $admin_payments_details['is_paypal_enabled'] == 'on')
                                    <a href="#paypal-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Paypal') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_paystack_enabled']) && $admin_payments_details['is_paystack_enabled'] == 'on')
                                    <a href="#paystack-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Paystack') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payments_details['is_flutterwave_enabled']) &&
                                        $admin_payments_details['is_flutterwave_enabled'] == 'on')
                                    <a href="#flutterwave-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Flutterwave') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payments_details['is_razorpay_enabled']) && $admin_payments_details['is_razorpay_enabled'] == 'on')
                                    <a href="#razorpay-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Razorpay') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payments_details['is_paytm_enabled']) && $admin_payments_details['is_paytm_enabled'] == 'on')
                                    <a href="#paytm-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Paytm') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payments_details['is_mercado_enabled']) && $admin_payments_details['is_mercado_enabled'] == 'on')
                                    <a href="#mercado-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Mercado Pago') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payments_details['is_mollie_enabled']) && $admin_payments_details['is_mollie_enabled'] == 'on')
                                    <a href="#mollie-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Mollie') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payments_details['is_skrill_enabled']) && $admin_payments_details['is_skrill_enabled'] == 'on')
                                    <a href="#skrill-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Skrill') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payments_details['is_coingate_enabled']) && $admin_payments_details['is_coingate_enabled'] == 'on')
                                    <a href="#coingate-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('CoinGate') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif

                                @if (isset($admin_payments_details['is_paymentwall_enabled']) &&
                                        $admin_payments_details['is_paymentwall_enabled'] == 'on')
                                    <a href="#paymentwall-payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Paymentwall') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_payfast_enabled']) && $admin_payments_details['is_payfast_enabled'] == 'on')
                                    <a href="#payfast_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Payfast') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payments_details['is_toyyibpay_enabled']) && $admin_payments_details['is_toyyibpay_enabled'] == 'on')
                                    <a href="#toyyibpay_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Toyyibpay') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_iyzipay_enabled']) && $admin_payments_details['is_iyzipay_enabled'] == 'on')
                                    <a href="#iyzipay_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('IyziPay') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_sspay_enabled']) && $admin_payments_details['is_sspay_enabled'] == 'on')
                                    <a href="#sspay_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('SS Pay') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_paytab_enabled']) && $admin_payments_details['is_paytab_enabled'] == 'on')
                                    <a href="#paytab_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Paytab') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_benefit_enabled']) && $admin_payments_details['is_benefit_enabled'] == 'on')
                                    <a href="#banifit_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Benefit') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_cashfree_enabled']) && $admin_payments_details['is_cashfree_enabled'] == 'on')
                                    <a href="#cashfree_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Cashfree') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_aamarpay_enabled']) && $admin_payments_details['is_aamarpay_enabled'] == 'on')
                                    <a href="#aamar_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('AamarPay') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_paytr_enabled']) && $admin_payments_details['is_paytr_enabled'] == 'on')
                                    <a href="#paytr_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Pay TR') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_yookassa_enabled']) && $admin_payments_details['is_yookassa_enabled'] == 'on')
                                    <a href="#yookassa_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Yookassa') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_midtrans_enabled']) && $admin_payments_details['is_midtrans_enabled'] == 'on')
                                    <a href="#midtrans_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Midtrans') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_xendit_enabled']) && $admin_payments_details['is_xendit_enabled'] == 'on')
                                    <a href="#xendit_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Xendit') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_paiment_pro_enabled']) && $admin_payments_details['is_paiment_pro_enabled'] == 'on')
                                    <a href="#paiment_pro_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Paiment Pro') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_fedapay_enabled']) && $admin_payments_details['is_fedapay_enabled'] == 'on')
                                    <a href="#fedapay_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Fedapay') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_nepalste_enabled']) && $admin_payments_details['is_nepalste_enabled'] == 'on')
                                    <a href="#nepalste_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Nepalste') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_payhere_enabled']) && $admin_payments_details['is_payhere_enabled'] == 'on')
                                    <a href="#payhere_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Payhere') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payments_details['is_cinetpay_enabled']) && $admin_payments_details['is_cinetpay_enabled'] == 'on')
                                    <a href="#cinetpay_payment"
                                        class="list-group-item list-group-item-action border-0">{{ __('Cinetpay') }}<div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                            </div>
                        </div>


                        <div class="mt-5">
                            <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                                style="
                                                                        visibility: visible;
                                                                        animation-delay: 0.2s;
                                                                        animation-name: fadeInUp;
                                                                      ">
                                <div class="card-body">
                                    <span class="price-badge bg-primary">{{ $plan->name }}</span>
                                    @if (\Auth::user()->type == 'Owner' && \Auth::user()->plan == $plan->id)
                                        <div class="d-flex flex-row-reverse m-0 p-0 ">
                                            <span class="d-flex align-items-center ">
                                                <i class="f-10 lh-1 fas fa-circle text-success"></i>
                                                <span class="ms-2">{{ __('Active') }}</span>
                                            </span>
                                        </div>
                                    @endif

                                    <div class="text-end">
                                        <div class="">
                                            @if (\Auth::user()->type == 'super admin')
                                                <a title="Edit Plan" data-size="lg" href="#" class="action-item"
                                                    data-url="{{ route('plans.edit', $plan->id) }}"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Plan') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Plan') }}"><i class="fas fa-edit"></i></a>
                                            @endif
                                        </div>
                                    </div>

                                    <h3 class="mb-4 f-w-600  ">
                                        {{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') }}{{ $plan->price . ' / ' . __(\App\Models\Plan::$arrDuration[$plan->duration]) }}</small>
                                        </h1>
                                        <p class="mb-0">
                                            {{ __('Trial : ') . $plan->trial_days . __(' Days') }}<br />
                                        </p>
                                        @if ($plan->description)
                                            <p class="mb-0">
                                                {{ $plan->description }}<br />
                                            </p>
                                        @endif
                                        <div class="row mt-1">
                                            <ul class="plan-detail">
                                                @if ($plan->enable_custdomain == 'on')
                                                    <li>{{ __('Custom Domain') }}</li>
                                                @else
                                                    <div>{{ __('Custom Domain') }}</div>
                                                @endif
                                                @if ($plan->enable_custsubdomain == 'on')
                                                    <li>{{ __('Sub Domain') }}</li>
                                                @else
                                                    <div>{{ __('Sub Domain') }}</div>
                                                @endif
                                                @if ($plan->shipping_method == 'on')
                                                    <li>{{ __('Shipping Method') }}</li>
                                                @else
                                                    <div>{{ __('Shipping Method') }}</div>
                                                @endif
                                                @if ($plan->pwa_store == 'on')
                                                    <li>{{ __('Progressive Web App ( PWA )') }}</li>
                                                @else
                                                    <div>{{ __('Progressive Web App ( PWA )') }}</div>
                                                @endif
                                                @if ($plan->storage_limit != 0)
                                                    <li>{{ __('Storage Limit : ') }}{{ $plan->storage_limit }}{{ ' MB' }}
                                                    </li>
                                                @else
                                                    <div>
                                                        {{ __('Storage Limit : ') }}{{ $plan->storage_limit }}{{ ' MB' }}
                                                    </div>
                                                @endif
                                            </ul>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-6 text-center">
                                                @if ($plan->max_products == '-1')
                                                    <span class="h5 mb-0">{{ __('Unlimited') }}</span>
                                                @else
                                                    <span class="h5 mb-0">{{ $plan->max_products }}</span>
                                                @endif
                                                <span class="d-block text-sm">{{ __('Products') }}</span>
                                            </div>
                                            <div class="col-6 text-center">
                                                <span class="h5 mb-0">
                                                    @if ($plan->max_stores == '-1')
                                                        <span class="h5 mb-0">{{ __('Unlimited') }}</span>
                                                    @else
                                                        <span class="h5 mb-0">{{ $plan->max_stores }}</span>
                                                    @endif
                                                </span>
                                                <span class="d-block text-sm">{{ __('Store') }}</span>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    @if (isset($admin_payments_details['is_manuallypay_enabled']) &&
                            $admin_payments_details['is_manuallypay_enabled'] == 'on')
                        <div class="card active" id="maually-payment">
                            <div class="card-header">
                                <h5>{{ __('Manually') }}</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ __('Requesting manual payment for the planned amount for the subscriptions plan.') }}
                                </p>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="text-sm-end mr-2">
                                                @if (\Auth::user()->type != 'super admin' && \Auth::user()->plan != $plan->id)
                                                    @if ($plan->id != 1)
                                                        @if (\Auth::user()->requested_plan != $plan->id)
                                                            <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                                                class="btn btn-primary btn-icon m-1"
                                                                data-title="{{ __('Send Request') }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Send Request') }}">
                                                                <span class="btn-inner--icon">{{ 'Send Request' }}</span>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                                                class="btn btn-icon m-1 btn-danger"
                                                                data-title="{{ __('Cancle Request') }}"data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="{{ __('Cancle Request') }}">
                                                                <span
                                                                    class="btn-inner--icon">{{ 'Cancle Request' }}</span>
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_bank_enabled']) && $admin_payments_details['is_bank_enabled'] == 'on')
                        <div class="card active" id="bank-transfer-payment">
                            <div class="card-header">
                                <h5>{{ __('Bank Transfer') }}</h5>
                            </div>
                            <form role="form" action="{{ route('plan.pay.with.banktransfer') }}" method="post"
                                class="require-validation" id="banktransfer-payment-form" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="border p-3 mb-3 rounded stripe-payment-div">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <br>
                                                <label class="form-label"><b>{{ __('Bank Details:') }}</b></label>
                                                <div class="form-group">
                                                    @if (isset($admin_payments_details['bank_detail']) && !empty($admin_payments_details['bank_detail']))
                                                        {!! $admin_payments_details['bank_detail'] !!}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mt-3">
                                                    <label class="form-label"
                                                        for="bank_transfer_invoice">{{ 'Payment Receipt' }}</label>
                                                    <input type="file" class="form-control "
                                                        name="bank_transfer_invoice" id="bank_transfer_invoice"
                                                        onchange="document.getElementById('bank_transfer_invoice').src = window.URL.createObjectURL(this.files[0])">
                                                </div>
                                                @error('bank_transfer_invoice')
                                                    <div class="row">
                                                        <span class="invalid-bank_transfer_invoice" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <br>
                                                <div class="form-group">
                                                    <label for="bank_transfer_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="bank_transfer_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn  btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="text-sm-end mr-2">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_stripe_enabled']) && $admin_payments_details['is_stripe_enabled'] == 'on')
                        <div class="card active" id="stripe-payment">
                            <div class="card-header">
                                <h5>{{ __('Stripe') }}</h5>
                            </div>
                            <div class="card-body">
                                <form role="form" action="{{ route('stripe.payment') }}" method="post"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded stripe-payment-div">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="custom-radio">
                                                    <label
                                                        class="font-16 font-weight-bold">{{ __('Credit / Debit Card') }}</label>
                                                </div>
                                                <p class="mb-0 pt-1 text-sm">
                                                    {{ __('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.') }}
                                                </p>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="card-name-on">{{ __('Name on card') }}</label>
                                                    <input type="text" name="name" id="card-name-on"
                                                        class="form-control required"
                                                        placeholder="{{ \Auth::user()->name }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="card-element"></div>
                                                <div id="card-errors" role="alert"></div>
                                            </div>
                                            <div class="col-md-10">
                                                <br>
                                                <div class="form-group">
                                                    <label for="stripe_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="stripe_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="text-sm-end mr-2">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_paypal_enabled']) && $admin_payments_details['is_paypal_enabled'] == 'on')
                        <div id="paypal-payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Paypal') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('plan.pay.with.paypal') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn  btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_paystack_enabled']) && $admin_payments_details['is_paystack_enabled'] == 'on')
                        <div id="paystack-payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Paystack') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="border p-3 mb-3 rounded payment-box">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="paypal_coupon"
                                                    class="col-form-label">{{ __('Coupon') }}</label>
                                                <input type="text" id="paypal_coupon" name="coupon"
                                                    class="form-control coupon"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                            <a href="#"
                                                class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                    <span
                                                        class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for=""
                                                    class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                        class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-end">
                                    <input type="button" onclick="preparePayment(this,'paystack')" value="{{__('Pay Now')}}"
                                        class="btn btn-print-invoice  btn-primary m-b-10 m-r-10">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_flutterwave_enabled']) &&
                            $admin_payments_details['is_flutterwave_enabled'] == 'on')
                        <div id="flutterwave-payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Flutterwave') }}</h5>
                            </div>
                            <div class="card-body">

                                <input type="hidden" name="plan_id"
                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                <div class="border p-3 mb-3 rounded payment-box">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="paypal_coupon"
                                                    class="col-form-label">{{ __('Coupon') }}</label>
                                                <input type="text" id="paypal_coupon" name="coupon"
                                                    class="form-control coupon"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                            <a href="#"
                                                class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                    <span
                                                        class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for=""
                                                    class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                        class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-end">
                                    <input type="button" onclick="preparePayment(this,'flutterwave')" value="{{__('Pay Now')}}"
                                        class="btn btn-print-invoice  btn-primary m-b-10 m-r-10">
                                </div>

                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_razorpay_enabled']) && $admin_payments_details['is_razorpay_enabled'] == 'on')
                        <div id="razorpay-payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Razorpay') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('plan.pay.with.paypal') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="button" onclick="preparePayment(this,'razorpay')" value="{{__('Pay Now')}}"
                                            class="btn btn-print-invoice  btn-primary m-b-10 m-r-10">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_paytm_enabled']) && $admin_payments_details['is_paytm_enabled'] == 'on')
                        <div id="paytm-payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Paytm') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('paytm.prepare.plan') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                    <input type="hidden" name="total_price" id="paytm_total_price"
                                        value="{{ $plan->price }}" class="form-control">
                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">{{ __('Mobile Number') }}</label>
                                                    <input type="text" id="mobile_number" name="mobile_number"
                                                        class="form-control "
                                                        placeholder="{{ __('Enter Mobile Number') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_mercado_enabled']) && $admin_payments_details['is_mercado_enabled'] == 'on')
                        <div id="mercado-payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Mercado Pago') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('plan.pay.with.paypal') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="button" onclick="preparePayment(this,'mercado')" value="{{__('Pay Now')}}"
                                            class="btn btn-print-invoice  btn-primary m-b-10 m-r-10">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_mollie_enabled']) && $admin_payments_details['is_mollie_enabled'] == 'on')
                        <div id="mollie-payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Mollie') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('mollie.prepare.plan') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                    <input type="hidden" name="total_price" id="mollie_total_price"
                                        value="{{ $plan->price }}" class="form-control">
                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_skrill_enabled']) && $admin_payments_details['is_skrill_enabled'] == 'on')
                        <div id="skrill-payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Skrill') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('skrill.prepare.plan') }}">
                                    @csrf
                                    <input type="hidden" name="id"
                                        value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                    <input type="hidden" name="order_id"
                                        value="{{ str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, '100', STR_PAD_LEFT) }}">
                                    @php
                                        $skrill_data = [
                                            'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                            'user_id' => 'user_id',
                                            'amount' => 'amount',
                                            'currency' => 'currency',
                                        ];
                                        session()->put('skrill_data', $skrill_data);

                                    @endphp
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                    <input type="hidden" name="total_price" id="skrill_total_price"
                                        value="{{ $plan->price }}" class="form-control">
                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="skrill_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="skrill_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_coingate_enabled']) && $admin_payments_details['is_coingate_enabled'] == 'on')
                        <div id="coingate-payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('CoinGate') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('coingate.prepare.plan') }}">
                                    @csrf
                                    <input type="hidden" name="counpon" id="coingate_coupon" value="">
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                    <input type="hidden" name="total_price" id="coingate_total_price"
                                        value="{{ $plan->price }}" class="form-control">
                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="coingate_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="coingate_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for=""
                                                            class="col-form-label">{{ __('Plan Price : ') }}
                                                            <span
                                                                class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for=""
                                                            class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                                class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                        <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_paymentwall_enabled']) &&
                            $admin_payments_details['is_paymentwall_enabled'] == 'on')
                        <div id="paymentwall-payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Paymentwall') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id=""
                                    action="{{ route('paymentwall') }}">
                                    @csrf
                                    <input type="hidden" name="counpon" id="paymentwall_coupon" value="">
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                    <input type="hidden" name="total_price" id="paymentwall_total_price"
                                        value="{{ $plan->price }}" class="form-control">
                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="coingate_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paymentwall_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_payfast_enabled']) && $admin_payments_details['is_payfast_enabled'] == 'on')
                        <div id="payfast_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Payfast') }}</h5>
                            </div>

                            <div class="card-body">
                                @if (
                                    $admin_payments_details['is_payfast_enabled'] == 'on' &&
                                        !empty($admin_payments_details['payfast_merchant_id']) &&
                                        !empty($admin_payments_details['payfast_merchant_key']) &&
                                        !empty($admin_payments_details['payfast_signature']) &&
                                        !empty($admin_payments_details['payfast_mode']))
                                    <div
                                        class="tab-pane {{ ($admin_payments_details['is_payfast_enabled'] == 'on' && !empty($admin_payments_details['payfast_merchant_id']) && !empty($admin_payments_details['payfast_merchant_key'])) == 'on' ? 'active' : '' }}">
                                        @php
                                            $pfHost = $admin_payments_details['payfast_mode'] == 'sandbox' ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
                                        @endphp
                                        <form role="form" action={{ 'https://' . $pfHost . '/eng/process' }}
                                            method="post" class="require-validation" id="payfast-form">
                                            <div class="border p-3 rounded ">
                                                <div class="col-md-12 mt-4 row">
                                                    <div class="d-flex align-items-center">
                                                        <div class="form-group col-10">
                                                            <label for="paypal_coupon"
                                                                class="form-label">{{ __('Coupon') }}</label>
                                                            <input type="text" id="payfast_coupon" name="coupon"
                                                                class="form-control coupon"
                                                                placeholder="{{ __('Enter Coupon Code') }}">
                                                        </div>
                                                        <div class="col-md-2 coupon-apply-btn ms-4 mb-2 mt-1">
                                                            <a href="#"
                                                                class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for=""
                                                                class="col-form-label">{{ __('Plan Price : ') }}
                                                                <span
                                                                    class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for=""
                                                                class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                                    class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                            <small
                                                                class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="get-payfast-inputs"></div>
                                            <div class="col-sm-12 my-2 px-2">
                                                <div class="text-end">
                                                    <input type="hidden" name="plan_id" id="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <input type="submit" value="{{__('Pay Now')}}" id="payfast-get-status"
                                                        class="btn btn-xs btn-primary final-price">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_toyyibpay_enabled']) && $admin_payments_details['is_toyyibpay_enabled'] == 'on')
                        <div id="toyyibpay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Toyyibpay') }}</h5>
                            </div>
                            <div class="tab-pane " id="Toyyibpay_payment">
                                <form role="form" action="{{ route('toyyibpay.prepare.plan') }}" method="post"
                                    id="toyyibpay-payment-form" class="w3-container w3-display-middle w3-card-4">
                                    @csrf
                                    <div class="card-body">
                                        <div class="border p-3 mb-3 rounded payment-box row">
                                            <div class="d-flex align-items-center">
                                                <div class="form-group w-100">
                                                    <label for="toyyibpay_coupan"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="toyyibpay_coupan" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>

                                                <div class="col-md-2 coupon-apply-btn ms-4 mb-2 mt-1">
                                                    <a href="#"
                                                        class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for=""
                                                            class="col-form-label">{{ __('Plan Price : ') }}
                                                            <span
                                                                class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for=""
                                                            class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                                class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                        <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 my-2 px-2">
                                            <div class="text-end">
                                                <input type="hidden" name="plan_id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_iyzipay_enabled']) && $admin_payments_details['is_iyzipay_enabled'] == 'on')
                        <div id="iyzipay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('IyziPay') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('iyzipay.payment.init') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="iyzipay_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="iyzipay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn  btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_sspay_enabled']) && $admin_payments_details['is_sspay_enabled'] == 'on')
                        <div id="sspay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('SS Pay') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('sspay.prepare.plan') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="iyzipay_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="iyzipay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn  btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_paytab_enabled']) && $admin_payments_details['is_paytab_enabled'] == 'on')
                        <div id="paytab_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Paytab') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('plan.pay.with.paytab') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="iyzipay_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="iyzipay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn  btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_benefit_enabled']) && $admin_payments_details['is_benefit_enabled'] == 'on')
                        <div id="banifit_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Benefit') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                    action="{{ route('benefit.initiate', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="iyzipay_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="iyzipay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn  btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_cashfree_enabled']) && $admin_payments_details['is_cashfree_enabled'] == 'on')
                        <div id="cashfree_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Cashfree') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('cashfree.payment', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="iyzipay_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="iyzipay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn  btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_aamarpay_enabled']) && $admin_payments_details['is_aamarpay_enabled'] == 'on')
                        <div id="aamar_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('AamarPay') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('pay.aamarpay.payment', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="iyzipay_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="iyzipay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn  btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_paytr_enabled']) && $admin_payments_details['is_paytr_enabled'] == 'on')
                        <div id="paytr_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Pay TR') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('pay.paytr.payment', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="iyzipay_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="iyzipay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn  btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_yookassa_enabled']) && $admin_payments_details['is_yookassa_enabled'] == 'on')
                        <div id="yookassa_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Yookassa') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('plan.pay.with.yookassa', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="yookassa_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="yookassa_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_midtrans_enabled']) && $admin_payments_details['is_midtrans_enabled'] == 'on')
                        <div id="midtrans_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Midtrans') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('plan.pay.with.midtrans', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="midtrans_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="midtrans_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_xendit_enabled']) && $admin_payments_details['is_xendit_enabled'] == 'on')
                        <div id="xendit_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Xendit') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('plan.pay.with.xendit', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="xendit_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="xendit_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_paiment_pro_enabled']) && $admin_payments_details['is_paiment_pro_enabled'] == 'on')
                        <div id="paiment_pro_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Paiment Pro') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('plan.pay.with.paiementpro', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobile_number"
                                                        class="form-control-label text-dark">{{ __('Mobile Number') }}</label>

                                                    <input type="text" id="mobile_number"
                                                        name="mobile_number"
                                                        class="form-control mobile_number"
                                                        data-from="mobile_number"
                                                        placeholder="{{ __('Enter Mobile Number') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="channel"
                                                        class="form-control-label text-dark">{{ __('Channel') }}</label>

                                                    <input type="text" id="channel"
                                                        name="channel" class="form-control channel"
                                                        data-from="channel"
                                                        placeholder="{{ __('Enter Channel') }}" required>
                                                    <small class="text-danger">{{ __('Example : OMCIV2,MOMO,CARD,FLOOZ ,PAYPAL') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paiment_pro_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paiment_pro_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_fedapay_enabled']) && $admin_payments_details['is_fedapay_enabled'] == 'on')
                        <div id="fedapay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Fedapay') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('plan.pay.with.fedapay', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="fedapay_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="fedapay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_nepalste_enabled']) && $admin_payments_details['is_nepalste_enabled'] == 'on')
                        <div id="nepalste_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Nepalste') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('plan.pay.with.nepalste', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="nepalste_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="nepalste_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_payhere_enabled']) && $admin_payments_details['is_payhere_enabled'] == 'on')
                        <div id="payhere_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Payhere') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('plan.pay.with.payhere', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="payhere_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="payhere_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payments_details['is_cinetpay_enabled']) && $admin_payments_details['is_cinetpay_enabled'] == 'on')
                        <div id="cinetpay_payment" class="card">
                            <div class="card-header">
                                <h5>{{ __('Cinetpay') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="payment-form" action="{{ route('plan.pay.with.cinetpay', $plan->id) }}">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="cinetpay_coupon"
                                                        class="col-form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="cinetpay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mb-2 mt-1">
                                                <a href="#"
                                                    class="btn btn-primary m-b-10 m-r-10 apply-coupon">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">{{ __('Plan Price : ') }}
                                                        <span
                                                            class="paypal-final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for=""
                                                        class="col-form-label mb-0">{{ __('Net Ammount : ') }}<span
                                                            class="final-price">{{ (!empty($admin_payments_details['currency_symbol']) ? $admin_payments_details['currency_symbol'] : '$') . $plan->price }}</span></label><br>
                                                    <small class="text-muted">{{ __('(After Coupon Apply )') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{__('Pay Now')}}" class="btn btn-xs btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
                <!-- [ sample-page ] end -->
            </div>
        </div>
    </div>
@endsection
