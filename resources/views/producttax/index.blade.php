@extends('layouts.admin')
@section('page-title')
    {{ __('Product Tax') }}
@endsection
@section('title')
    {{ __('Product Tax') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item active" aria-current="page">{{ __('Product Tax') }}</li>
@endsection
@section('action-btn')
<div class="row gy-4 align-items-center">
    <div class="col-auto">
        <div class="d-flex">

            @can('Create Product Tax')
            <a class="btn btn-sm btn-icon text-white btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create') }}" data-size="md" data-ajax-popup="true"
                data-title="{{ __('Create New Tax') }}" data-url="{{ route('product_tax.create') }}">
                <i data-feather="plus"></i>
            </a>
            @endcan

        </div>
    </div>
</div>
@endsection
@section('filter')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple ">
                            <thead>
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Tax Name') }}</th>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Rate %') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product_taxs as $product_tax)
                                    <tr data-name="{{ $product_tax->name }}">
                                        <td>{{ $product_tax->name }}</td>
                                        <td>{{ $product_tax->rate }}</td>
                                        <td class="Action">
                                            <div class="d-flex">
                                                @can('Edit Product Tax')
                                                <a class="btn btn-sm btn-icon  bg-light-secondary me-2" data-size="md"
                                                    data-url="{{ route('product_tax.edit', $product_tax->id) }}"
                                                    data-ajax-popup="true" data-title="{{ __('Edit') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit') }}">
                                                    <i class="ti ti-edit f-20"></i>
                                                </a>
                                                @endcan
                                                @can('Delete Product Tax')
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['product_tax.destroy', $product_tax->id],
                                                    'id' => 'delete-form-' . $product_tax->id,
                                                ]) !!}
                                                <a class="btn btn-sm btn-icon  bg-light-secondary me-2 show_confirm"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash f-20"></i>
                                                </a>
                                                {!! Form::close() !!}
                                                @endcan
                                            </div>
                                            </span>
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
        $(document).ready(function() {
            $(document).on('keyup', '.search-user', function() {
                var value = $(this).val();
                $('.employee_tableese tbody>tr').each(function(index) {
                    var name = $(this).attr('data-name').toLowerCase();
                    if (name.includes(value.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
        $('#search').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endpush
