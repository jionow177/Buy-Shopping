@extends('layouts.admin')
@section('page-title')
    {{ __('Plans') }}
@endsection
@php
    $dir = asset(Storage::url('uploads/plan'));
    $user = Auth::user();
@endphp
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item active" aria-current="page">{{ __('Plans') }}</li>
@endsection
@section('title')
    {{ __('Plans') }}
@endsection
@section('action-btn')
    @if (Auth::user()->type == 'super admin')
        @if (
            (isset($admin_payments_setting['is_stripe_enabled']) && $admin_payments_setting['is_stripe_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_paypal_enabled']) && $admin_payments_setting['is_paypal_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_paystack_enabled']) &&
                    $admin_payments_setting['is_paystack_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_flutterwave_enabled']) &&
                    $admin_payments_setting['is_flutterwave_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_razorpay_enabled']) &&
                    $admin_payments_setting['is_razorpay_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_mercado_enabled']) &&
                    $admin_payments_setting['is_mercado_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_paytm_enabled']) && $admin_payments_setting['is_paytm_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_mollie_enabled']) && $admin_payments_setting['is_mollie_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_skrill_enabled']) && $admin_payments_setting['is_skrill_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_coingate_enabled']) &&
                    $admin_payments_setting['is_coingate_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_paymentwall_enabled']) &&
                    $admin_payments_setting['is_paymentwall_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_payfast_enabled']) &&
                    $admin_payments_setting['is_payfast_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_toyyibpay_enabled']) &&
                    $admin_payments_setting['is_toyyibpay_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_manuallypay_enabled']) &&
                    $admin_payments_setting['is_manuallypay_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_bank_enabled']) && $admin_payments_setting['is_bank_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_iyzipay_enabled']) &&
                    $admin_payments_setting['is_iyzipay_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_sspay_enabled']) && $admin_payments_setting['is_sspay_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_paytab_enabled']) && $admin_payments_setting['is_paytab_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_benefit_enabled']) &&
                    $admin_payments_setting['is_benefit_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_cashfree_enabled']) &&
                    $admin_payments_setting['is_cashfree_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_aamarpay_enabled']) &&
                    $admin_payments_setting['is_aamarpay_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_paytr_enabled']) && $admin_payments_setting['is_paytr_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_yookassa_enabled']) && $admin_payments_setting['is_yookassa_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_midtrans_enabled']) && $admin_payments_setting['is_midtrans_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_xendit_enabled']) && $admin_payments_setting['is_xendit_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_paiment_pro_enabled']) && $admin_payments_setting['is_paiment_pro_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_fedapay_enabled']) && $admin_payments_setting['is_fedapay_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_nepalste_enabled']) && $admin_payments_setting['is_nepalste_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_payhere_enabled']) && $admin_payments_setting['is_payhere_enabled'] == 'on') ||
                (isset($admin_payments_setting['is_cinetpay_enabled']) && $admin_payments_setting['is_cinetpay_enabled'] == 'on'))
            <div class="row  m-1">
                <div class="col-auto pe-0">
                    <a class="btn btn-sm btn-icon text-white btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ __('Add Plan ') }}" data-size="lg" data-ajax-popup="true" data-title="{{ __('Add Plan') }}"
                        data-url="{{ route('plans.create') }}">
                        <i data-feather="plus"></i>
                    </a>
                </div>
            </div>
        @endif
    @endif
@endsection
@section('content')
    <div class="row">
        @foreach ($plans as $plan)
        <div class="col-md-4 col-xxl-3">
                <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                    style="
                                    visibility: visible;
                                    animation-delay: 0.2s;
                                    animation-name: fadeInUp;
                                  ">
                    <div class="card-body plans_card_body">
                        <span class="price-badge bg-primary">{{ $plan->name }}</span>
                        <div class="row d-flex my-2">
                            @if (\Auth::user()->type == 'super admin')
                                <div class="col-md-6 text-start">
                                    @if($plan->id != 1)
                                        <div class="d-inline-flex align-items-center mt-1">
                                            <div class="form-check form-switch custom-switch-v1 float-end">
                                                <input type="checkbox" name="plan_active"
                                                class="form-check-input input-primary is_active" value="1"
                                                data-id='{{ $plan->id }}'
                                                data-name="{{ __('plan') }}"
                                                {{ $plan->is_active == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="plan_active"></label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 text-end">
                                    <a class="btn btn-sm btn-icon  bg-light-secondary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ __('Edit Plan') }}" data-ajax-popup="true"
                                        data-size="lg" data-title="{{ __('Edit Plan') }}"
                                        data-url="{{ route('plans.edit', $plan->id) }}">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    @if($plan->id != 1)
                                        {{-- @can('Delete Store') --}}
                                        <a class="bs-pass-para btn btn-sm bg-light-secondary btn-icon"
                                            data-title="{{ __('Delete Plan') }}"
                                            data-confirm="{{ __('Are You Sure?') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-{{ $plan->id}}"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ __('Delete Plan') }}">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['plans.destroy', $plan->id],'id'=>'delete-form-'.$plan->id]) !!}
                                        {!! Form::close() !!}
                                        {{-- @endcan --}}
                                    @endif
                                </div>
                            @endif
                            @if (\Auth::user()->type == 'Owner' && \Auth::user()->plan == $plan->id)
                                <div class="d-flex flex-row-reverse plan-active-status m-0 p-0 ">
                                    <span class="d-flex align-items-center ">
                                        <i class="f-10 lh-1 fas fa-circle text-primary"></i>
                                        <span class="ms-2">{{ __('Active') }}</span>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <h3 class="mb-4 f-w-600">
                            {{ (!empty($admin_payments_setting['currency_symbol']) ? $admin_payments_setting['currency_symbol'] : '$') }}{{ $plan->price . ' / ' . __(\App\Models\Plan::$arrDuration[$plan->duration]) }}</small>
                            </h1>
                            <div class="">

                                @if ($plan->trial == 'on')
                                    <p class="mb-0">{{__('Free Trial Days : ')}}<b>{{ $plan->trial_days }}</b></p>
                                @else
                                    <p class="mb-0">{{__('Free Trial Days : ')}}<b>{{ 0 }}</b></p>
                                @endif

                                @if ($plan->description)
                                    <p class="mb-0">
                                        {{ $plan->description }}<br />
                                    </p>
                                @endif
                                {{-- <div class="row mt-1"> --}}
                                <ul class="list-unstyled my-5">
                                    <li>
                                        @if ($plan->enable_custdomain == 'on')
                                            <span class="theme-avtar">
                                                <i class="text-primary ti ti-circle-plus"></i></span>
                                            {{ __('Custom Domain') }}
                                        @else
                                            <span class="theme-avtar">
                                                <i class="text-danger ti ti-circle-plus"></i></span>
                                            {{ __('Custom Domain') }}
                                        @endif
                                    </li>
                                    <li>
                                        @if ($plan->enable_custsubdomain == 'on')
                                            <span class="theme-avtar">
                                                <i class="text-primary ti ti-circle-plus"></i></span>
                                            {{ __('Sub Domain') }}
                                        @else
                                            <span class="theme-avtar">
                                                <i class="text-danger ti ti-circle-plus"></i></span>
                                            {{ __('Sub Domain') }}
                                        @endif
                                    </li>
                                    <li>
                                        @if ($plan->shipping_method == 'on')
                                            <span class="theme-avtar">
                                                <i class="text-primary ti ti-circle-plus"></i></span>
                                            {{ __('Shipping  Method') }}
                                        @else
                                            <span class="theme-avtar">
                                                <i class="text-danger ti ti-circle-plus"></i></span>
                                            {{ __('Shipping  Method') }}
                                        @endif

                                    </li>
                                    <li>
                                        @if ($plan->pwa_store == 'on')
                                            <span class="theme-avtar">
                                                <i class="text-primary ti ti-circle-plus"></i></span>
                                            {{ __('Progressive Web App ( PWA )') }}
                                        @else
                                            <span class="theme-avtar">
                                                <i class="text-danger ti ti-circle-plus"></i></span>
                                            {{ __('Progressive Web App ( PWA )') }}
                                        @endif
                                    </li>
                                    <li>
                                        @if ($plan->storage_limit != '0')
                                            <span class="theme-avtar">
                                                <i class="text-primary ti ti-circle-plus"></i></span>
                                            @if ($plan->storage_limit == '-1')
                                                {{ __('Storage Limit : ') }}{{ __('Unlimited') }}
                                            @else
                                                {{ __('Storage Limit : ') }}{{ $plan->storage_limit }}{{ ' MB' }}
                                            @endif
                                        @else
                                            <span class="theme-avtar">
                                                <i class="text-danger ti ti-circle-plus"></i></span>
                                            {{ __('Storage Limit : ') }}{{ $plan->storage_limit }}{{ ' MB' }}
                                        @endif
                                    </li>
                                    <li>
                                        @if ($plan->enable_chatgpt == 'on')
                                            <span class="theme-avtar">
                                                <i class="text-primary ti ti-circle-plus"></i></span>
                                            {{ __('Chat GPT') }}
                                        @else
                                            <span class="theme-avtar">
                                                <i class="text-danger ti ti-circle-plus"></i></span>
                                            {{ __('Chat GPT') }}
                                        @endif
                                    </li>
                                </ul>
                            </div>

                            <div class="row mb-3">
                                <div class="col-4 text-center ">
                                    <b>
                                        @if ($plan->max_products == '-1')
                                            <span class="h6 mt-3">{{ __('Unlimited') }}</span>
                                        @else
                                            <span class="h5 mb-0">{{ $plan->max_products }}</span>
                                        @endif
                                    </b>
                                    <span class="d-block text-sm">{{ __('Products') }}</span>
                                </div>
                                <div class="col-4 text-center">
                                    <span class="h5 mb-0">
                                        @if ($plan->max_stores == '-1')
                                            <span class="h5 mb-0">{{ __('Unlimited') }}</span>
                                        @else
                                            <span class="h5 mb-0">{{ $plan->max_stores }}</span>
                                        @endif
                                    </span>
                                    <span class="d-block text-sm">{{ __('Store') }}</span>
                                </div>
                                <div class="col-4 text-center">
                                    <span class="h5 mb-0">
                                        @if ($plan->max_users == '-1')
                                            <span class="h5 mb-0">{{ __('Unlimited') }}</span>
                                        @else
                                            <span class="h5 mb-0">{{ $plan->max_users }}</span>
                                        @endif
                                    </span>
                                    <span class="d-block text-sm">{{ __('Users') }}</span>
                                </div>
                            </div>
                            <div class="row">
                                @if (\Auth::user()->type != 'super admin')
                                    @if ($plan->price <= 0)
                                        <div class="col-lg-12">
                                            <p class="server-plan font-bold text-center mx-sm-5 mt-4">
                                                {{ __('Lifetime') }}
                                            </p>
                                        </div>
                                    @elseif(\Auth::user()->trial_plan == $plan->id && \Auth::user()->trial_expire_date &&
                                            date('Y-m-d') < \Auth::user()->trial_expire_date)
                                        <div class="col-lg-12">
                                            <p class="display-total-time text-dark mb-0">
                                                {{ __('Plan Trial Expired : ') }}
                                                {{ !empty(\Auth::user()->trial_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->trial_expire_date) : 'lifetime' }}
                                            </p>
                                        </div>
                                    @elseif (
                                        \Auth::user()->plan == $plan->id &&
                                            date('Y-m-d') < \Auth::user()->plan_expire_date == null &&
                                            \Auth::user()->is_trial_done != 1)
                                        <h5 class="h6 mt-3">
                                            {{ \Auth::user()->plan_expire_date ? \App\Models\Utility::dateFormat(\Auth::user()->plan_expire_date) : __('Lifetime') }}
                                        </h5>
                                    @elseif (
                                        \Auth::user()->plan == $plan->id &&
                                            date('Y-m-d') < \Auth::user()->plan_expire_date &&
                                            \Auth::user()->is_trial_done != 1)
                                        <h5 class="h6 mt-3">
                                            {{ __('Expired : ') }}
                                            {{ \Auth::user()->plan_expire_date ? \App\Models\Utility::dateFormat(\Auth::user()->plan_expire_date) : __('Lifetime') }}
                                        </h5>
                                    @elseif(
                                        \Auth::user()->plan == $plan->id &&
                                            !empty(\Auth::user()->plan_expire_date) &&
                                            \Auth::user()->plan_expire_date < date('Y-m-d'))
                                        <div class="col-lg-12">
                                            <p class="server-plan font-weight-bold text-center mx-sm-5">
                                                {{ __('Expired') }}
                                            </p>
                                        </div>
                                    @else
                                        @if (
                                            $plan->price > 0 &&
                                                \Auth::user()->trial_plan == 0 &&
                                                \Auth::user()->plan != $plan->id && $plan->trial != 'off' && $plan->trial_days != 0)
                                            <div class="{{ $plan->id == 1 ? 'col-lg-12' : 'col-lg-5' }} p-1">
                                                <a href="{{ route('plan.trial', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                    class="btn  btn-primary d-flex justify-content-center align-items-center ">{{ __('Free Trial') }}
                                                    <i class="fas fa-arrow-right m-1"></i> 
                                                </a>
                                            </div>
                                            <div class="{{ $plan->id == 1 ? 'col-lg-12' : 'col-lg-5' }} p-1">
                                                <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                    class="btn  btn-primary d-flex justify-content-center align-items-center ">{{ __('Subscribe') }}
                                                    <i class="fas fa-arrow-right m-1"></i>
                                                </a>
                                            </div>
                                        @else
                                            <div class="{{ $plan->id == 1 ? 'col-lg-12' : 'col-lg-10' }} p-1">
                                                <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                    class="btn  btn-primary d-flex justify-content-center align-items-center ">{{ __('Subscribe') }}
                                                    <i class="fas fa-arrow-right m-1"></i>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                @if (\Auth::user()->type != 'super admin' && \Auth::user()->plan != $plan->id)
                                    @if ($plan->id != 1)
                                        @if (\Auth::user()->requested_plan != $plan->id)
                                            <div class="col-lg-2 px-1">
                                                <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                                    class="btn btn-primary d-flex justify-content-center align-items-center btn-icon m-1"
                                                    data-title="{{ __('Send Request') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Send Request') }}">
                                                    <i class="fas fa-share m-1"></i>
                                                </a>
                                            </div>
                                        @else
                                            <div class="col-lg-2 px-1">
                                                <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                                    class="btn d-flex justify-content-center align-items-center  btn-icon m-1 btn-danger"
                                                    data-title="{{ __('Cancle Request') }}"data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Cancle Request') }}">
                                                    <i class="fas fa-trash m-1"></i>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            </div>
                    </div>
                </div>
        </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple ">
                            <thead>
                                <tr>
                                    <th> {{ __('Order Id') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Plan Name') }}</th>
                                    <th> {{ __('Price') }}</th>
                                    <th> {{ __('Payment Type') }}</th>
                                    <th> {{ __('Status') }}</th>
                                    <th> {{ __('Coupon') }}</th>
                                    <th> {{ __('Invoice') }}</th>
                                    @if (Auth::user()->type == 'super admin')
                                        <th> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($orders) && !empty($orders))
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->order_id }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>{{ $order->user_name }}</td>
                                            <td>{{ $order->plan_name }}</td>
                                            <td>{{ (!empty($admin_payments_setting['currency_symbol']) ? $admin_payments_setting['currency_symbol'] : '$') . $order->price }}</td>
                                            <td>{{ $order->payment_type }}</td>
                                            <td>
                                                @if (
                                                    $order->payment_status == 'succeeded' ||
                                                        $order->payment_status == 'Approved' ||
                                                        $order->payment_status == 'success')
                                                    <i class="mdi mdi-circle text-success"></i>
                                                    <span
                                                        class="badge rounded p-2 f-w-600  bg-light-primary">{{ ucfirst($order->payment_status) }}</span>
                                                @elseif($order->payment_status == 'pending')
                                                    <i class="mdi mdi-circle text-danger"></i>
                                                    <span
                                                        class="badge rounded p-2 f-w-600  bg-light-warning">{{ ucfirst($order->payment_status) }}</span>
                                                @else
                                                    <i class="mdi mdi-circle text-danger"></i>
                                                    <span
                                                        class="badge rounded p-2 f-w-600  bg-light-danger">{{ ucfirst($order->payment_status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ !empty($order->total_coupon_used) ? (!empty($order->total_coupon_used->coupon_detail) ? $order->total_coupon_used->coupon_detail->code : '-') : '-' }}
                                            </td>
                                            <td>
                                                @if ($order->receipt != 'free coupon' && $order->payment_type == 'Bank Transfer')
                                                    <a href="{{ asset(Storage::url($order->receipt)) }}" title="Invoice"
                                                        class="text-primary" target="_blank" class=""><i
                                                            class="ti ti-file-invoice"></i>{{ 'Invoice' }}
                                                    </a>
                                                @elseif($order->payment_type == 'STRIPE' && $order->receipt != 0)
                                                    <a href="{{ $order->receipt }}" title="Invoice" class="text-primary"
                                                        target="_blank" class=""><i
                                                            class="ti ti-file-invoice"></i>{{ 'Invoice' }}
                                                    </a>
                                                @elseif($order->receipt == 'free coupon')
                                                    <p>{{ __('Used') . '100 %' . __('discount coupon code.') }}</p>
                                                @elseif($order->payment_type == 'Manually')
                                                    <p>{{ __('-') }}</p>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            @if (Auth::user()->type == 'super admin')
                                                <td>
                                                    <div class="d-flex">
                                                        @if (
                                                            $order->payment_type == 'Bank Transfer' &&
                                                                Auth::user()->type == 'super admin' &&
                                                                $order->payment_status == 'pending')
                                                            <a class="btn btn-sm btn-icon  bg-light-secondary me-2 "
                                                                data-url="{{ route('bank_transfer.show', $order->id) }}"
                                                                data-ajax-popup="true" data-size="lg"
                                                                data-title="{{ __('Payment Status') }}"><i
                                                                    class="ti ti-player-play"></i></a>
                                                        @endif

                                                        {!! Form::open(['method' => 'Delete', 'route' => ['delete.plan_order', $order->id]]) !!}
                                                        <a class="btn btn-sm btn-icon  bg-light-secondary me-2 show_confirm"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="" data-bs-original-title="Delete">
                                                            <i class="ti ti-trash f-20"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                        @php
                                                            $user = App\Models\User::find($order->user_id);
                                                        @endphp
                                                        @foreach($userOrders as $userOrder)
                                                            @if ($user->plan == $order->plan_id &&
                                                                $order->order_id == $userOrder->order_id &&
                                                                $order->is_refund == 0 && $user->plan != 1)
                                                                    {{-- <div class="badge bg-warning rounded p-2 px-3 ms-2"> --}}
                                                                        <a href="{{ route('order.refund' , [$order->id , $order->user_id])}}"
                                                                            class="badge bg-warning rounded p-2 px-4 ms-2 align-items-center"
                                                                            data-bs-toggle="tooltip" title="{{ __('Refund') }}"
                                                                            data-original-title="{{ __('Refund') }}">
                                                                            <span class ="text-white">{{ __('Refund') }}</span>
                                                                        </a>
                                                                    {{-- </div> --}}
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            <div class="text-center">
                                                <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                                <h2>Opps...</h2>
                                                <h6>No data Found. </h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    {{-- <script>
        $(document).ready(function() {
            var tohref = '';
            @if (Auth::user()->is_register_trial == 1)
                tohref = $('#trial_{{ Auth::user()->interested_plan_id }}').attr("href");
            @elseif (Auth::user()->interested_plan_id != 0)
                tohref = $('#interested_plan_{{ Auth::user()->interested_plan_id }}').attr("href");
            @endif

            if (tohref != '') {
                window.location = tohref;
            }
        });
    </script> --}}
    <script>
        $(document).on("click", ".is_active", function() {
            var id = $(this).attr('data-id');
            var is_active = ($(this).is(':checked')) ? $(this).val() : 0;
            $.ajax({
                url: '{{ route('plan.enable') }}',
                type: 'POST',
                data: {
                    "is_active": is_active,
                    "id": id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.success) {
                        show_toastr('Success', data.success, 'success');
                    } else {
                        show_toastr('Error', data.error, 'error');
                    }
                }
            });
        });
    </script>
@endpush
