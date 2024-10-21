@extends('layouts.admin')
@section('page-title')
    {{ __('Products') }}
@endsection
@php
    $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
@endphp
@section('title')
    {{ __('Product') }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Products') }}</li>
@endsection
@section('action-btn')
    <div class="row gy-4 align-items-center ">
        <div class="col-auto">
            <div class="d-flex">
                <a href="{{ route('product.export', $store_id) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2"
                    data-bs-toggle="tooltip" data-bs-original-title="{{ __('Export') }}">
                    <i data-feather="download"></i>
                </a>
                @can('Create Products')
                <a class="btn btn-sm btn-icon  bg-light-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Import') }}" data-size="md" data-ajax-popup="true"
                    data-title="{{ __('Import Product CSV file') }}" data-url="{{ route('product.file.import') }}">
                    <i data-feather="upload"></i>
                </a>
                @endcan
                @can('Create Products')
                <a href="{{ route('product.create') }}" class="btn btn-sm btn-icon text-white btn-primary me-2"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
                    <i data-feather="plus"></i>
                </a>
                @endcan
                <a href="{{ route('product.grid') }}" class="btn btn-sm btn-icon  bg-light-secondary"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Grid View') }}">
                    <i class="ti ti-grid-dots f-30"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
@section('filter')
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('custom/libs/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Stock') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th class="text-right" width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if (!empty($product->is_cover))
                                                    <a href="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                                        target="_blank">
                                                        <img src="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                                            alt="" class="theme-avtar">
                                                    </a>
                                                @else
                                                    <a href="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                                        target="_blank">
                                                        <img src="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                                            alt="" class="theme-avtar">
                                                    </a>
                                                @endif

                                                <div class="ms-3">
                                                    <a href="{{ route('product.show', $product->id) }}"
                                                        class="text-dark f-w-700">{{ $product->name }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        @php
                                            $categories = $product->product_category();
                                        @endphp
                                        <td>{{ !empty($categories) ? $categories : '-' }}
                                        </td>
                                        <td>
                                            @if ($product->enable_product_variant == 'on')
                                                {{ __('In Variant') }}
                                            @else
                                                {{ \App\Models\Utility::priceFormat($product->price) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->enable_product_variant == 'on')
                                                {{ __('In Variant') }}
                                            @else
                                                {{ $product->quantity }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->enable_product_variant == 'on')
                                                <span class="badge rounded p-2 f-w-600  bg-light-warning">
                                                    {{ __('In Variant') }}
                                                </span>
                                            @else
                                                @if ($product->quantity == 0)
                                                    <span class="badge rounded p-2 f-w-600  bg-light-secondry">
                                                        {{ __('Out of stock') }}
                                                    </span>
                                                @else
                                                    <span class="badge rounded p-2 f-w-600  bg-light-primary">
                                                        {{ __('In stock') }}
                                                    </span>
                                                @endif
                                            @endif

                                        <td>
                                            {{ \App\Models\Utility::dateFormat($product->created_at) }}
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @if($product->product_display == 'on')
                                                    @can('Show Products')
                                                    <a class="btn btn-sm btn-icon  bg-light-secondary me-2"
                                                        href="{{ route('product.show', $product->id) }}" data-toggle="tooltip"
                                                        data-original-title="{{ __('View') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="{{ __('View') }}">
                                                        <i class="ti ti-eye f-20"></i>
                                                    </a>
                                                    @endcan
                                                    @can('Edit Products')
                                                    <a class="btn btn-sm btn-icon bg-light-secondary me-2"
                                                        href="{{ route('product.edit', $product->id) }}" data-toggle="tooltip"
                                                        data-original-title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="{{ __('Edit') }}">
                                                        <i class="ti ti-edit f-20"></i>
                                                    </a>
                                                    @endcan
                                                    @can('Delete Products')
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['product.destroy', $product->id],
                                                        'id' => 'delete-form-' . $product->id,
                                                    ]) !!}
                                                    <a class=" show_confirm btn btn-sm btn-icon bg-light-secondary me-2"
                                                        href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash f-20"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                    @endcan
                                                @else
                                                    <div class="btn">
                                                        <i class="ti ti-lock"></i>
                                                    </div>
                                                @endif
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
@push('script-page')
    <script>
        $(document).on('click', '#billing_data', function() {
            $("[name='shipping_address']").val($("[name='billing_address']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
        })
    </script>
@endpush
