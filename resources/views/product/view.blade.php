@extends('layouts.admin')
@section('page-title')
    {{ __('Product') }}
@endsection
@section('title')
    {{ __('Product') }}
@endsection
@php
    $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
    $p_logo = \App\Models\Utility::get_file('uploads/product_image/');
@endphp
@section('action-btn')
    @can('Edit Products')
        <div class="row  m-1">
            <div class="col-auto pe-0">
                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary btn-icon"
                    class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Edit Product') }}"><i class="ti ti-edit text-white"></i></a>
            </div>
        </div>
    @endcan
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">{{ __('Product') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Product Detail') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <!-- [ sample-page ] start -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card border border-primary shadow-none">
                        <div class="card-body">
                            <div
                                class="d-flex mb-3 align-items-center gap-2 flex-sm-row flex-column justify-content-between">
                                <h4>{{ $product->name }}</h4>
                                <div class="ps-3 d-flex align-items-center ">
                                    <span
                                        class="badge rounded p-2 f-w-600  bg-light-dark">{{ __('ID: #') }}{{ $product->SKU }}</span>
                                    <div class="text-end ms-3">
                                        @if ($product->enable_product_variant == 'on')
                                            <span class="badge rounded p-2 f-w-600  bg-light-info">
                                                {{ __('In Variant') }}
                                            </span>
                                        @else
                                            @if ($product->quantity == 0)
                                                <span class="badge rounded p-2 f-w-600  bg-light-danger">
                                                    {{ __('Out of stock') }}
                                                </span>
                                            @else
                                                <span class="badge rounded p-2 f-w-600  bg-light-primary">
                                                    {{ __('In stock') }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="border  mb-4 rounded border-primary">
                                @if (!empty($product->is_cover))
                                    <a class="d-block"
                                        href="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                        target="_blank" data-fancybox="product">
                                        <img class="m-auto d-block w-50"
                                            src="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                            alt="">
                                    </a>
                                @else
                                    <img src="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                        alt="" class="m-auto d-block w-50">
                                @endif
                            </div>
                            <p class="mb-2">{{ __('Description') }}:</p>
                            <p> {!! $product->description !!}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4>{{ __('Express Checkout') }}</h4>
                                        <small
                                            class="text-dark font-weight-bold">{{ __('Note:Create Express Checkout Url For Direct Order') }}</small>
                                    </div>
                                    <a href="#" class="btn btn-primary" data-ajax-popup="true"
                                        data-url="{{ route('expresscheckout.create', [$product->id]) }}"
                                        data-title="{{ __('Add Product') }}" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ __('Create') }}" data-tooltip="Create">
                                        {{ __('Add') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body table-border-style mt-3">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Quantity') }}</th>
                                                <th>{{ __('Variant Name') }}</th>
                                                <th>{{ __('URL') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($expresscheckout))
                                                @foreach ($expresscheckout as $key => $value)
                                                    <tr>
                                                        <td>{{ $value->product->name }}</td>
                                                        <td>{{ $value->quantity }}</td>
                                                        <td>{{ isset($value->variant_name) ? $value->variant_name : '-' }}
                                                        </td>
                                                        <td><a href="#" class="btn btn-light-primary cp_link"
                                                                data-link="{{ $value->url }}" data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                data-bs-original-title="{{ __('Click to copy Checkout link') }}">{{ __('Copy Link') }}</a>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn bg-light-secondary btn-icon btn-sm"
                                                                data-ajax-popup="true"
                                                                data-url="{{ route('expresscheckout.edit', [$value->id]) }}"
                                                                data-title="{{ __('Edit Expresscheckout') }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Edit') }}"><i class="ti ti-edit"></i></a>
                                                            <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary"
                                                                href="#"
                                                                data-title="{{ __('Delete Checkout Link') }}"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $value->id }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ __('Delete') }}">
                                                                <i class="ti ti-trash f-20"></i>
                                                            </a>
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['expresscheckout.destroy', $value->id],
                                                                'id' => 'delete-form-' . $value->id,
                                                            ]) !!}
                                                            {!! Form::close() !!}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-3 gx-3">
                        <div class="col-sm-6">
                            @if ($product->enable_product_variant == 'on')
                                <div class="card border  shadow-none">
                                    <div class="card-body">
                                        <h4>{{ __('Color') }}</h4>
                                        <div class="row align-items-center">
                                            <input type="hidden" id="product_id" value="{{ $product->id }}">
                                            <input type="hidden" id="variant_id" value="">
                                            <input type="hidden" id="variant_qty" value="">
                                            @foreach ($product_variant_names as $key => $variant)
                                                <div class=" mb-4 mb-sm-0">
                                                    <span class="d-block h6 mb-0">
                                                        <select name="product[{{ $key }}]"
                                                            id='choices-multiple-{{ $key }}'
                                                            class="form-control multi-select  pro_variants_name{{ $key }} change_price">
                                                            <option value="">{{ __('Select') }}</option>
                                                            @foreach ($variant->variant_options as $key => $values)
                                                                <option value="{{ $values }}"
                                                                    class="price_options">
                                                                    {{ $values }}</option>
                                                            @endforeach
                                                        </select>
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <div class="card border  shadow-none">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-sm-6 mb-4 mb-sm-0">
                                            <span class="d-block h6 mb-0">
                                                <span class="d-block h3 mb-0 variasion_price">
                                                    @if ($product->enable_product_variant == 'on')
                                                        {{ \App\Models\Utility::priceFormat(0) }}
                                                    @else
                                                        {{ \App\Models\Utility::priceFormat($product->price) }}
                                                    @endif

                                                </span>
                                                {{ !empty($product->product_taxs) ? $product->product_taxs->name : '' }}
                                                {{ !empty($product->product_taxs->rate) ? $product->product_taxs->rate . '%' : '' }}
                                            </span>
                                        </div>
                                        <div class="col-sm-6 mb-4 mb-sm-0 text-end">
                                            <span class="d-block h6 mb-0">
                                                <button type="button" class="btn btn-primary btn-icon">
                                                    <span class="btn-inner--icon variant_qty">
                                                        @if ($product->enable_product_variant == 'on')
                                                            0
                                                        @else
                                                            {{ $product->quantity }}
                                                        @endif
                                                    </span>
                                                    <span class="btn-inner--text">
                                                        {{ __('Total Avl.Quantity') }}
                                                    </span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border  shadow-none">
                        <div class="card-header">
                            <h4>{{ __('Gallery') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row gy-3 gx-3">
                                @foreach ($product_image as $key => $products)
                                    <div class="col-sm-3">
                                        @if (!empty($products->product_images))
                                            <div class="p-2 border border-primary rounded">
                                                <a href="{{ $p_logo . (isset($products->product_images) && !empty($products->product_images) ? $products->product_images : 'default_img.png') }}"
                                                    target="_blank" data-fancybox="product">
                                                    <img src="{{ $p_logo . (isset($products->product_images) && !empty($products->product_images) ? $products->product_images : 'default_img.png') }}"
                                                        alt="" class="w-100">
                                                </a>
                                            </div>
                                        @else
                                            <div class="p-2 border border-primary rounded">
                                                <a href="{{ $p_logo . (isset($products->product_images) && !empty($products->product_images) ? $products->product_images : 'default_img.png') }}"
                                                    target="_blank" data-fancybox="product">
                                                    <img src="{{ $p_logo . (isset($products->product_images) && !empty($products->product_images) ? $products->product_images : 'default_img.png') }}"
                                                        alt="" class="w-100">
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
@endsection

@push('script-page')
    <script>
        $(document).on('change', '.change_price', function() {
            var variants = [];
            $(".change_price").each(function(index, element) {
                variants.push(element.value);
            });
            if (variants.length > 0) {
                $.ajax({
                    url: '{{ route('get.products.variant.quantity') }}',
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        variants: variants.join(' : '),
                        product_id: $('#product_id').val()
                    },

                    success: function(data) {
                        console.log(data);
                        $('.variasion_price').html(data.price);
                        $('#variant_id').val(data.variant_id);
                        $('.variant_qty').html(data.quantity);
                    }
                });
            }
        });
        $(document).ready(function() {
            $('.cp_link').on('click', function() {
                var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                show_toastr('Success', '{{ __('Link copied') }}', 'success')
            });
        });
    </script>
@endpush
