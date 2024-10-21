@extends('layouts.admin')
@section('page-title')
    {{ __('Product') }}
@endsection
@php
    $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
    $p_logo = \App\Models\Utility::get_file('uploads/product_image/');
    $plan = Utility::user_plan();
@endphp
@section('title')
    {{ __('Product') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">{{ __('Product') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Product Edit') }}</li>
@endsection

@section('action-btn')
    <div class="pr-2">
        @if ($plan['enable_chatgpt'] == 'on')
            <a class="btn btn-sm btn-primary me-3" href="#" data-size="lg" data-ajax-popup-over="true"
                data-url="{{ route('generate', ['products']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Generate') }}" data-title="{{ __('Generate product Name') }}"> <i class="fas fa-robot"></i>
                {{ __('Generate With AI') }}
            </a>
        @endif
        <a href="{{ route('product.index') }}" class="btn btn-light-secondary me-3"> <i data-feather="x-circle"
                class="me-2"></i>{{ __('Cancel') }}</a>
        <a type="submit" id="submit-all" class="btn btn-primary text-white"> <i data-feather="check-circle"
                class="me-2"></i>{{ __('Update') }}</a>
    </div>
@endsection

@section('filter')
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('custom/libs/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
    <script>
        var Dropzones = function() {
            var e = $('[data-toggle="dropzone1"]'),
                t = $(".dz-preview");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            e.length && (Dropzone.autoDiscover = !1, e.each(function() {
                var e, a, n, o, i;
                e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                    url: "{{ route('products.update', $product->id) }}",
                    method: 'PUT',
                    headers: {
                        'x-csrf-token': CSRF_TOKEN,
                    },
                    thumbnailWidth: null,
                    thumbnailHeight: null,
                    previewsContainer: n.get(0),
                    previewTemplate: n.html(),
                    maxFiles: 10,
                    parallelUploads: 10,
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    acceptedFiles: a ? null : "image/*",
                    success: function(file, response) {
                        if (response.flag == "success") {
                            show_toastr('success', response.msg, 'success');
                            setTimeout(() => {
                                window.location.href = "{{ route('product.index') }}";
                            }, 2000);
                        } else {
                            show_toastr('Error', response.msg, 'error');
                        }
                    },
                    error: function(file, response) {
                        // Dropzones.removeFile(file);
                        if (response.error) {
                            show_toastr('Error', response.msg, 'error');
                        } else {
                            show_toastr('Error', response.msg, 'error');
                        }
                    },
                    init: function() {
                        var myDropzone = this;

                        this.on("addedfile", function(e) {
                            !a && o && this.removeFile(o), o = e
                        })
                    }
                }, n.html(""), e.dropzone(i)
            }))
        }()

        $('#submit-all').on('click', function(e) {
            e.preventDefault();

            var form = $(this).parents('form');
            var variantNameEle = $('#variant_name');
            var variantOptionsEle = $('#variant_options');
            var isValid = true;
            if (variantNameEle.val() == '') {
                variantNameEle.focus();
                isValid = false;
            } else if (variantOptionsEle.val() == '') {
                variantOptionsEle.focus();
                isValid = false;
            }

            if (isValid) {

                $.ajax({
                    url: form.attr('action'),
                    datType: 'json',
                    data: {
                        variant_name: variantNameEle.val(),
                        variant_options: variantOptionsEle.val(),
                        hiddenVariantOptions: $('#hiddenVariantOptions').val()

                    },
                    success: function(data) {
                        $('#hiddenVariantOptions').val(data.hiddenVariantOptions);
                        $('.variant-table').html(data.varitantHTML);
                        $("#commonModal").modal('hide');
                    }
                })
            }


            $('#cost').trigger('keyup');

            var fd = new FormData();
            var file = document.getElementById('is_cover_image').files[0];
            var downloadable_prodcutfile = document.getElementById('downloadable_prodcut').files[0];

            if (file) {
                fd.append('is_cover_image', file);
            }
            if (downloadable_prodcutfile) {
                fd.append('downloadable_prodcut', downloadable_prodcutfile);
            }

            var files = $('[data-toggle="dropzone1"]').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('multiple_files[' + key + ']', $('[data-toggle="dropzone1"]')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });
            var other_data = $('#frmTarget').serializeArray();

            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
            });

            $.ajax({
                url: "{{ route('products.update', $product->id) }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.flag == "success") {
                        show_toastr('success', data.msg, 'success');
                        setTimeout(() => {
                            window.location.href = "{{ route('product.index') }}";
                        }, 1500);
                    } else {
                        show_toastr('Error', data.msg, 'error');
                    }
                },
                error: function(data) {
                    if (data.error) {
                        show_toastr('Error', data.error, 'error');
                    } else {
                        show_toastr('Error', data, 'error');
                    }
                },
            });
        });

        $(".deleteRecord").click(function() {

            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: '{{ route('products.file.delete', '__product_id') }}'.replace('__product_id', id),
                type: 'DELETE',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function(data) {
                    if (data.success == "success") {
                        show_toastr('success', data.success, 'success');
                        $('.product_Image[data-id="' + data.id + '"]').remove();
                        location.reload();
                    } else {
                        show_toastr('Error', data.msg, 'error');
                    }
                }
            });
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            {{ Form::model($product, ['method' => 'POST', 'id' => 'frmTarget', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h5>{{ __('Main Informations') }}</h5>
                            <div class="card shadow-none border border-primary">
                                <div class="card-body ">
                                    <div class="form-group">
                                        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name')]) !!}
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            {{ Form::label('product_categorie', __('Product Categories'), ['class' => 'col-form-label']) }}
                                            {!! Form::select('product_categorie[]', $product_categorie, explode(',', $product->product_categorie), [
                                                'class' => 'form-control multi-select',
                                                'id' => 'note1',
                                                'data-toggle' => 'select',
                                                'multiple',
                                            ]) !!}
                                            @if (count($product_categorie) == 0)
                                                {{ __('Add product category') }}
                                                <a href="{{ route('product_categorie.index') }}">
                                                    {{ __('Click here') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('SKU', __('SKU'), ['class' => 'form-label']) }}
                                        {!! Form::text('SKU', null, ['class' => 'form-control', 'placeholder' => __('Enter SKU')]) !!}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('product_tax', __('Product Tax')) }}
                                        {!! Form::select('product_tax[]', $product_tax, explode(',', $product->product_tax), [
                                            'class' => 'form-control multi-select',
                                            'id' => 'note2',
                                            'data-toggle' => 'select',
                                            'multiple',
                                        ]) !!}
                                        @if (count($product_tax) == 0)
                                            {{ __('Add product tax') }}
                                            <a href="{{ route('product_tax.index') }}">
                                                {{ __('Click here') }}
                                            </a>
                                        @endif
                                        @error('product_tax')
                                            <span class="invalid-product_tax" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group proprice">
                                        <div class="row gy-4">
                                            <div class="col-md-6">
                                                {{ Form::label('price', __('Price'), ['class' => 'form-label']) }}
                                                {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => __('Enter Price ')]) !!}
                                            </div>
                                            <div class="col-md-6">
                                                {{ Form::label('quantity', __('Stock Quantity'), ['class' => 'form-label']) }}
                                                {!! Form::text('quantity', null, ['class' => 'form-control', 'placeholder' => __('Enter Stock Quantity')]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="form-label">{{ __('Downloadable Product') }}</label>
                                        <input type="file" name="downloadable_prodcut" id="downloadable_prodcut"
                                            class="form-control"
                                            onchange="document.getElementById('downProduct').src = window.URL.createObjectURL(this.files[0])"
                                            multiple>
                                        <img id="downProduct" src="" width="25%" />
                                        <small>{{ $product->downloadable_prodcut }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <h5>{{ __('Custom Field') }}</h5>
                            <div class="card shadow-none border border-primary">
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            {{ Form::label('custom_field_1', __('Custom Field'), ['class' => 'form-label']) }}
                                            {{ Form::text('custom_field_1', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Field'), 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('custom_value_1', __('Custom Value'), ['class' => 'form-label']) }}
                                            {{ Form::text('custom_value_1', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Value'), 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('custom_field_2', __('Custom Field'), ['class' => 'form-label']) }}
                                            {{ Form::text('custom_field_2', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Field'), 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('custom_value_2', __('Custom Value'), ['class' => 'form-label']) }}
                                            {{ Form::text('custom_value_2', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Value'), 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('custom_field_3', __('Custom Field'), ['class' => 'form-label']) }}
                                            {{ Form::text('custom_field_3', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Field'), 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('custom_value_3', __('Custom Value'), ['class' => 'form-label']) }}
                                            {{ Form::text('custom_value_3', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Value'), 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('custom_field_4', __('Custom Field'), ['class' => 'form-label']) }}
                                            {{ Form::text('custom_field_4', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Field'), 'required' => 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('custom_value_4', __('Custom Value'), ['class' => 'form-label']) }}
                                            {{ Form::text('custom_value_4', null, ['class' => 'form-control', 'placeholder' => __('Enter Custom Value'), 'required' => 'required']) }}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="card shadow-none border border-primary">
                                    <div class="card-body">
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <div class="row gy-3">
                                                    <div class="col-lg-6">
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="checkbox" class="form-check-input input-primary"
                                                                id="customswitchv1-1"
                                                                {{ $product['product_display'] == 'on' ? 'checked' : '' }}
                                                                name="product_display">
                                                            <label class="form-check-label"
                                                                for="customswitchv1-1">{{ __('Product Display') }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="checkbox" class="form-check-input input-primary"
                                                                name="enable_product_variant" id="enable_product_variant"
                                                                {{ $product['enable_product_variant'] == 'on' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="enable_product_variant">{{ __('Display Variants') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (isset($product_variant_names))
                            <div id="productVariant" class="col-lg-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card my-3 border border-primary">
                                            <div class="card-header">
                                                <div class="row flex-grow-1">
                                                    <div class="col-md d-flex align-items-center">
                                                        <h5 class="card-header-title">{{ __('Product Variants') }}</h5>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        @can('Create Variants')
                                                            <button type="button" class="btn btn-sm btn-primary get-variants"
                                                                dataa-url="{{ route('product.variants.edit', $product->id) }}"><i
                                                                    class="fas fa-plus"></i>
                                                                {{ __('Add Variant') }}
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row form-group">
                                                    <div class="table-responsive">
                                                        <div class="card-body">
                                                            {{--  @DD($product)  --}}
                                                            <input type="hidden" id="hiddenVariantOptions"
                                                                name="hiddenVariantOptions"
                                                                value="{{ $product->variants_json }}">
                                                            <div class="variant-table">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr class="text-center">
                                                                            @if (isset($product_variant_names))
                                                                                @foreach ($product_variant_names as $variant)
                                                                                    <th><span>{{ ucwords($variant) }}</span>
                                                                                    </th>
                                                                                @endforeach
                                                                            @endif
                                                                            <th><span>{{ __('Price') }}</span></th>
                                                                            <th><span>{{ __('Quantity') }}</span></th>
                                                                            <th><span>{{ __('Action') }}</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        @if (isset($productVariantArrays))
                                                                            @foreach ($productVariantArrays as $counter => $productVariant)
                                                                                <tr
                                                                                    data-id="{{ $productVariant['product_variants']['id'] }}">
                                                                                    @foreach (explode(' : ', $productVariant['product_variants']['name']) as $key => $values)
                                                                                        <td>
                                                                                            <input type="text"
                                                                                                name="variants[{{ $productVariant['product_variants']['id'] }}][variants][{{ $key }}][]"
                                                                                                autocomplete="off"
                                                                                                spellcheck="false"
                                                                                                class="form-control"
                                                                                                value="{{ $values }}"
                                                                                                readonly>
                                                                                        </td>
                                                                                    @endforeach
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            name="variants[{{ $productVariant['product_variants']['id'] }}][price]"
                                                                                            autocomplete="off"
                                                                                            spellcheck="false"
                                                                                            placeholder="{{ __('Enter Price') }}"
                                                                                            class="form-control vprice_{{ $counter }}"
                                                                                            value="{{ $productVariant['product_variants']['price'] }}">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            name="variants[{{ $productVariant['product_variants']['id'] }}][quantity]"
                                                                                            autocomplete="off"
                                                                                            spellcheck="false"
                                                                                            placeholder="{{ __('Enter Quantity') }}"
                                                                                            class="form-control vquantity_{{ $counter }}"
                                                                                            value="{{ $productVariant['product_variants']['quantity'] }}">
                                                                                    </td>
                                                                                    <td
                                                                                        class="d-flex align-items-center mt-3 border-0">

                                                                                        @can('Delete Variants')
                                                                                            <div class="action-btn ms-2">
                                                                                                <a class="bs-pass-para  btn btn-sm btn-icon bg-light-secondary me-2"
                                                                                                    href="#"
                                                                                                    data-title="{{ __('Delete Lead') }}"
                                                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                                                    data-confirm-yes="delete-form-{{ $productVariant['product_variants']['id'] }}">

                                                                                                    <i class="ti ti-trash"></i>

                                                                                                </a>
                                                                                                @if ($loop->iteration == 1)
                                                                                                    <form action=""
                                                                                                        method="">
                                                                                                        @csrf
                                                                                                    </form>
                                                                                                @endif

                                                                                                {!! Form::open([
                                                                                                    'method' => 'DELETE',
                                                                                                    'route' => [
                                                                                                        'products.variant.delete',
                                                                                                        [$productVariant['product_variants']['id'], $productVariant['product_variants']['product_id']],
                                                                                                    ],
                                                                                                    'id' => 'delete-form-' . $productVariant['product_variants']['id'],
                                                                                                ]) !!}
                                                                                                {!! Form::hidden('variant_options', $productVariant['product_variants']['name'], ['id' => 'invisible_id']) !!}
                                                                                                {!! Form::close() !!}
                                                                                            </div>
                                                                                        @endcan
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <h5>{{ __('Product Image') }}</h5>
                    <div class="card shadow-none border border-primary">
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <label class="form-label">{{ __('Upload Cover Image') }}</label>
                                    <input name="is_cover_image" id="is_cover_image" type="file" class="form-control"
                                        onchange="document.getElementById('coverImg').src = window.URL.createObjectURL(this.files[0])"
                                        multiple>
                                    <img id="coverImg"src="" width="20%" class="mt-2" />
                                </div>
                                <div class="form-group">
                                    <div class="row gy-3 gx-3">
                                        <div class="col-sm-6">
                                            <div
                                                class="position-relative p-2 border rounded border-primary overflow-hidden rounded">
                                                <img src="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                                    alt="images" class="w-100">
                                                <div
                                                    class="position-absolute actions text-center top-50 end-0 start-0 ps-3 pb-3">
                                                    <a class="btn btn-sm btn-primary me-2"
                                                        href="{{ $logo . (isset($product->is_cover) && !empty($product->is_cover) ? $product->is_cover : 'default_img.png') }}"
                                                        download="" data-toggle="tooltip"
                                                        data-original-title="{{ __('Download') }}" target="_blank">
                                                        <i class="ti ti-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('sub_images', __('Upload Product Images'), ['class' => 'col-form-label']) }}
                                    <div class="dropzone dropzone-multiple" data-toggle="dropzone1"
                                        data-dropzone-url="http://" data-dropzone-multiple>
                                        <div class="fallback">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="dropzone-1"
                                                    name="file" multiple>
                                                <label class="custom-file-label"
                                                    for="customFileUpload">{{ __('Choose file') }}</label>
                                            </div>
                                        </div>
                                        <ul
                                            class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="col">
                                                            <div class="avatar">
                                                                <img class="rounded" src=""
                                                                    alt="Image placeholder" data-dz-thumbnail>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="text-sm mb-1" data-dz-name>...</h6>
                                                        <p class="small text-muted mb-0" data-dz-size></p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#" class="dropdown-item" data-dz-remove>
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row gy-3 gx-3">
                                        @foreach ($product_image as $file)
                                            <div class="col-sm-6 product_Image" data-id="{{ $file->id }}">
                                                <div
                                                    class="position-relative p-2 border rounded border-primary overflow-hidden rounded">
                                                    <img src="{{ $p_logo . (isset($file->product_images) && !empty($file->product_images) ? $file->product_images : 'default_img.png') }}"
                                                        alt="images" class="w-100">
                                                    <div
                                                        class="position-absolute actions text-center top-50 end-0 start-0 ps-3 pb-3">
                                                        <a class="btn btn-sm btn-primary me-2"
                                                            href="{{ $p_logo . (isset($file->product_images) && !empty($file->product_images) ? $file->product_images : 'favicon.png') }}"
                                                            download="" data-toggle="tooltip"
                                                            data-original-title="{{ __('Download') }}" target="_blank">
                                                            <i class="ti ti-download"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-danger deleteRecord" name="deleteRecord"
                                                            data-id="{{ $file->id }}">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <h5>{{ __('About product') }}</h5>
                    <div class="card shadow-none border border-primary">
                        <div class="card-body">
                            <div class="form-group">
                                {{ Form::label('description', __('Product Description'), ['class' => 'col-form-label']) }}
                                {!! Form::textarea('description', !empty($product->description) ? $product->description : '', [
                                    'class' => 'form-control summernote',
                                    'rows' => 2,
                                    'rows' => 2,
                                    'placeholder' => __('Product Description'),
                                ]) !!}
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
    <!-- [ sample-page ] end -->
@endsection
@push('script-page')
    <script>
        $(document).on('click', '.get-variants', function(e) {
            $("#commonModal .modal-title").html('{{ __('Add Variants') }}');
            $("#commonModal .modal-dialog").addClass('modal-md');
            $("#commonModal").modal('show');
            var data_url = $(this).attr('dataa-url');

            $.get(data_url, {}, function(data) {
                $('#commonModal .body').html(data);
            });
        });

        $(document).on('click', '.add-variants', function(e) {
            e.preventDefault();
            var form = $(this).parents('form');
            var variantNameEle = $('#variant_name');
            var variantOptionsEle = $('#variant_options');
            var isValid = true;
            var hiddenVariantOptions = $('#hiddenVariantOptions').val();

            if (variantNameEle.val() == '') {
                variantNameEle.focus();
                isValid = false;
            } else if (variantOptionsEle.val() == '') {
                variantOptionsEle.focus();
                isValid = false;
            }

            if (isValid) {
                $.ajax({
                    url: form.attr('action'),
                    datType: 'json',
                    data: {
                        variant_name: variantNameEle.val(),
                        variant_options: variantOptionsEle.val(),
                        hiddenVariantOptions: hiddenVariantOptions
                    },
                    success: function(data) {
                        $('#hiddenVariantOptions').val(data.hiddenVariantOptions);
                        $('.variant-table').html(data.varitantHTML);
                        $("#commonModal").modal('hide');
                    }
                })
            }
        });
        // enable_product_variant
    </script>
@endpush
