@extends('layouts.admin')
@section('page-title')
    {{ __('Coupons') }}
@endsection
@section('title')
    {{ __('Coupons') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Coupons') }}</li>
@endsection

@section('action-btn')
    <div class="row gy-4 m-1">
        <div class="col-auto pe-0">
            <a href="{{ route('coupons.export', $coupons) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2"
                data-bs-toggle="tooltip" data-bs-original-title="{{ __('Export') }}">
                <i data-feather="download"></i>
            </a>
            <a class="btn btn-sm btn-icon text-white btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create') }}" data-size="md" data-ajax-popup="true" data-title="{{ __('Add Coupon') }}"
                data-url="{{ route('coupons.create') }}">
                <i data-feather="plus"></i>
            </a>
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
        <!-- [ basic-table ] start -->
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table pc-dt-simple">
                            <thead>
                                <tr>
                                    <th> {{ __('Name') }}</th>
                                    <th> {{ __('Code') }}</th>
                                    <th> {{ __('Discount (%)') }}</th>
                                    <th> {{ __('Limit') }}</th>
                                    <th> {{ __('Used') }}</th>
                                    <th width="300px"> {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->name }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ $coupon->discount }}</td>
                                        <td>{{ $coupon->limit }}</td>
                                        <td>{{ $coupon->used_coupon() }}</td>
                                        <td class="Action">

                                            <div class="d-flex">
                                                <a class="btn btn-sm btn-icon  bg-light-secondary me-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View') }}" data-title="{{ __('View') }}"
                                                    href="{{ route('coupons.show', $coupon->id) }}">
                                                    <i class="ti ti-eye f-20"></i>
                                                </a>
                                                <a class="btn btn-sm btn-icon bg-light-secondary me-2"
                                                    data-url="{{ route('coupons.edit', [$coupon->id]) }}"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Coupon') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit') }}">
                                                    <i class="ti ti-edit f-20"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['coupons.destroy', $coupon->id]]) !!}
                                                <a class="btn btn-sm btn-icon bg-light-secondary show_confirm" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash f-20"></i>
                                                </a>
                                                {!! Form::close() !!}
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
        <!-- [ basic-table ] end -->
    </div>
@endsection
