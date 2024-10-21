@extends('layouts.admin')
@section('page-title')
    {{ __('Product Coupons') }}
@endsection
@section('title')
    {{ __('Product Coupons') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item active" aria-current="page">{{ __('Product Coupons') }}</li>
@endsection
@section('action-btn')
    <div class="row gy-4 align-items-center">
        <div class="col-auto">
            <div class="d-flex">
                <a href="{{ route('coupon.export') }} " class="btn btn-sm btn-icon  bg-light-secondary me-2"
                    data-bs-toggle="tooltip" data-bs-original-title="{{ __('Export') }}">
                    <i data-feather="download"></i>
                </a>
                @can('Create Product Coupan')
                    <a class="btn btn-sm btn-icon  bg-light-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ __('Import') }}" data-size="md" data-ajax-popup="true"
                        data-title="{{ __('Import Product CSV file') }}" data-url="{{ route('coupon.file.import') }}">
                        <i data-feather="upload"></i>
                    </a>
                @endcan
                @can('Create Product Coupan')
                    <a href="#" class="btn btn-sm btn-icon  btn-primary me-2" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="{{ __('Create Product Category') }}" data-size="lg"
                        data-ajax-popup="true" data-title="{{ __('Add Coupon') }}"
                        data-url="{{ route('product-coupon.create') }}">
                        <i data-feather="plus"></i>
                    </a>
                @endcan
            </div>
        </div>
    </div>
@endsection
@push('script-page')
    <script>
        $(document).on('click', '#code-generate', function() {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple ">
                            <thead>
                                <tr>
                                    <th> {{ __('Name') }}</th>
                                    <th> {{ __('Code') }}</th>
                                    <th> {{ __('Discount (%)') }}</th>
                                    <th> {{ __('Limit') }}</th>
                                    <th> {{ __('Used') }}</th>
                                    <th width="200px"> {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productcoupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->name }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        @if ($coupon->enable_flat == 'off')
                                            <td>{{ $coupon->discount . '%' }}</td>
                                        @endif
                                        @if ($coupon->enable_flat == 'on')
                                            <td>{{ $coupon->flat_discount . ' ' . '(Flat)' }}</td>
                                        @endif
                                        <td>{{ $coupon->limit }}</td>
                                        <td>{{ $coupon->product_coupon() }}</td>
                                        <td class="Action">
                                            <div class="d-flex">
                                                @can('Show Product Coupan')
                                                    <a href="{{ route('product-coupon.show', $coupon->id) }}"
                                                        class="btn btn-sm btn-icon  bg-light-secondary me-2"
                                                        data-toggle="tooltip" data-original-title="{{ __('View') }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('View') }}">
                                                        <i class="ti ti-eye f-20"></i>
                                                    </a>
                                                @endcan
                                                @can('Edit Product Coupan')
                                                    <a class="btn btn-sm btn-icon  bg-light-secondary me-2" data-size="lg"
                                                        data-url="{{ route('product-coupon.edit', [$coupon->id]) }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit') }}" data-ajax-popup="true"
                                                        data-title="{{ __('Edit') }}">
                                                        <i class="ti ti-edit f-20"></i>
                                                    </a>
                                                @endcan
                                                @can('Delete Product Coupan')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['product-coupon.destroy', $coupon->id]]) !!}
                                                    <a class="btn btn-sm btn-icon  bg-light-secondary show_confirm"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
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
