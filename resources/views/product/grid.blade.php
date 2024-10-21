@extends('layouts.admin')
@section('page-title')
    {{ __('Product') }}
@endsection
@php
    $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
@endphp
@section('title')
    {{ __('Product') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Home') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Product') }}</li>
@endsection
@section('action-btn')
    <div class="row gy-4 align-items-center">
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
                    <a href="{{ route('product.create') }}" class="btn btn-sm btn-icon text-white btn-primary me-2"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
                        <i data-feather="plus"></i>
                    </a>
                @endcan
                <a href="{{ route('product.index') }}" class="btn btn-sm btn-icon  bg-light-secondary me-2"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('List View') }}">
                    <i class="fas fa-list"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Product') }}</li>
@endsection
@section('content')
    <div class="row">
        @foreach ($products as $key => $product)
            @php
                $key = $key+1;
            @endphp
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card text-white text-center">
                    <div class="card-header border-0 pb-0">
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                @if($product->product_display == 'on')
                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                @else
                                    <div class="btn">
                                        <i class="ti ti-lock"></i>
                                    </div>
                                @endif
                                <div class="dropdown-menu dropdown-menu-end" style="">
                                    @can('Show Products')
                                        <a href="{{ route('product.show', $product->id) }}" class="dropdown-item"><i
                                                class="ti ti-eye"></i>
                                            <span>{{ __('View') }}</span></a>
                                    @endcan
                                    @can('Edit Products')
                                        <a href="{{ route('product.edit', $product->id) }}" class="dropdown-item"><i
                                                class="ti ti-edit"></i>
                                            <span>{{ __('Edit') }}</span></a>
                                    @endcan
                                    @can('Delete Products')
                                        <a class="bs-pass-para dropdown-item trigger--fire-modal-1" href="#"
                                            data-title="{{ __('Delete Lead') }}" data-confirm="{{ __('Are You Sure?') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-{{ $product->id }}">
                                            <i class="ti ti-trash"></i><span>{{ __('Delete') }} </span>

                                        </a>
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['product.destroy', $product->id],
                                            'id' => 'delete-form-' . $product->id,
                                        ]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (!empty($product->is_cover))
                            <a href="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                target="_blank">
                                <img alt="Image placeholder"
                                    src="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                    class="img-fluid rounded-circle card-avatar" alt="images" style="width: 50px; height:50px">
                            </a>
                        @else
                            <a href="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                target="_blank">
                                <img alt="Image placeholder"
                                    src="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                    class="img-fluid rounded-circle card-avatar" alt="images" style="width: 50px; height:50px">
                            </a>
                        @endif
                        <h4 class="text-primary mt-2"> <a
                                href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a></h4>
                        <h4 class="text-muted">
                            <small>{{ \App\Models\Utility::priceFormat($product->price) }}</small>
                        </h4>
                        @if ($product->quantity == 0)
                            <span class="badge bg-danger p-2 px-3 rounded">
                                {{ __('Out of stock') }}
                            </span>
                        @else
                            <span class="badge bg-primary p-2 px-3 rounded">
                                {{ __('In stock') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-md-3">
            <a href="{{ route('product.create') }}" class="btn-addnew-project" data-bs-toggle="tooltip"
                data-bs-placement="top" title="{{ __('Create Product') }}"><i class="ti ti-plus text-white"></i>
                <div class="bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">New Product</h6>
                <p class="text-muted text-center">Click here to add New Product</p>
            </a>
        </div>
    </div>
@endsection
