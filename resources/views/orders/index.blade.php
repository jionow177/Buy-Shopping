@extends('layouts.admin')
@section('page-title')
    {{ __('Order') }}
@endsection
@section('title')
    {{ __('Orders') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item active" aria-current="page">{{ __('Orders') }}</li>
@endsection
@section('action-btn')
    <div class="row  m-1">
        <div class="col-auto pe-0">
            <a href="{{ route('order.export', $store->id) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2"
                data-bs-toggle="tooltip" data-bs-original-title="{{ __('Export') }}">
                <i data-feather="download"></i>
            </a>

        </div>
    </div>
@endsection
@section('filter')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Order') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Value') }}</th>
                                    <th>{{ __('Payment Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('orders.show', \Illuminate\Support\Facades\Crypt::encrypt($order->id)) }}"
                                                    class="btn btn-sm btn-white btn-icon order-badge btn-outline-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-original-title="{{ __('Click to copy link') }}">
                                                    <span class="btn-inner--text">{{ $order->order_id }}</span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ \App\Models\Utility::dateFormat($order->created_at) }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ \App\Models\Utility::priceFormat($order->price) }}</td>
                                        <td>{{ $order->payment_type }}</td>
                                        <td>
                                            @if ($order->status != 'Cancel Order')
                                                <button type="button"
                                                    class="btn btn-sm {{ $order->status == 'pending' ? 'btn-soft-info' : 'btn-soft-primary' }} btn-icon rounded-pill">
                                                    <span class="btn-inner--icon">
                                                        @if ($order->status == 'pending')
                                                            <i class="fas fa-check soft-primary"></i>
                                                        @else
                                                            <i class="fa fa-check-double soft-primary"></i>
                                                        @endif
                                                    </span>
                                                    @if ($order->status == 'pending')
                                                        <span class="btn-inner--text">
                                                            {{ __('Pending') }}:
                                                            {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                        </span>
                                                    @else
                                                        <span class="btn-inner--text">
                                                            {{ __('Delivered') }}:
                                                            {{ \App\Models\Utility::dateFormat($order->updated_at) }}
                                                        </span>
                                                    @endif
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-secondary btn-icon rounded-pill">
                                                    <span class="btn-inner--icon">
                                                        @if ($order->status == 'pending')
                                                            <i class="fas fa-check soft-primary"></i>
                                                        @else
                                                            <i class="fa fa-check-double soft-primary"></i>
                                                        @endif
                                                    </span>
                                                    <span class="btn-inner--text">
                                                        {{ __('Cancel Order') }}:
                                                        {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                    </span>
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @can('Show Orders')
                                                    <a class="btn btn-sm btn-icon  bg-light-secondary me-2"
                                                        href="{{ route('orders.show', \Illuminate\Support\Facades\Crypt::encrypt($order->id)) }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"  data-bs-original-title="{{ __('View') }}">
                                                        <i class="ti ti-eye f-20"></i>
                                                    </a>
                                                @endcan
                                                @can('Delete Orders')
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['orders.destroy', $order->id],
                                                        'id' => 'delete-form-' . $order->id,
                                                    ]) !!}
                                                    <a class=" show_confirm btn btn-sm btn-icon  bg-light-secondary me-2"
                                                        href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash f-20"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
