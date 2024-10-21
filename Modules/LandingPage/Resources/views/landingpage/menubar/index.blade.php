@extends('layouts.admin')
@section('page-title')
    {{ __('Landing Page') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Landing Page') }}</li>
@endsection

@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $logo = \App\Models\Utility::get_file('uploads/landing_page_image');
@endphp

@push('css-page')
    <link rel="stylesheet" href="{{ asset('custom/libs/summernote/summernote-bs4.css') }}">
@endpush

@push('script-page')
    <script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
@endpush

@push('script-page')
    <script>
        document.getElementById('site_logo').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        }
    </script>

    <script src="{{ Module::asset('LandingPage:Resources/assets/js/plugins/tinymce.min.js') }}" referrerpolicy="origin">
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Landing Page') }}</li>
@endsection

@section('action-btn')
    <ul class="nav nav-pills cust-nav   rounded  mb-3" id="pills-tab" role="tablist">
        @include('landingpage::layouts.tab')
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                {{--  Start for all settings tab --}}
                <div>
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <h5>{{ __('Custom Page') }}</h5>
                                </div>
                            </div>
                        </div>
    
                        {{ Form::open(['route' => 'custom_store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('Site Logo Light', __('Site Logo Light'), ['class' => 'form-label']) }}
                                        <div class="logo-content mt-4">
                                            <img id="image"
                                                src="{{ $logo . '/' . $settings['site_logo_light'] . '?timestamp=' . time() }}"
                                                class="big-logo" style="filter: drop-shadow(2px 3px 7px #011C4B);">
                                        </div>
                                        <div class="choose-files mt-5">
                                            <label for="site_logo_light">
                                                <div class=" bg-primary company_logo_update" style="cursor: pointer;">
                                                    <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                </div>
                                                <input type="file" name="site_logo_light" id="site_logo_light"
                                                    class="form-control file" data-filename="site_logo_light">
                                            </label>
                                        </div>
                                        @error('site_logo_light')
                                            <div class="row">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('Site Logo Dark', __('Site Logo Dark'), ['class' => 'form-label']) }}
                                        <div class="logo-content mt-4">
                                            <img id="image"
                                                src="{{ $logo . '/' . $settings['site_logo_dark'] . '?timestamp=' . time() }}"
                                                class="big-logo" style="filter: drop-shadow(2px 3px 7px #011C4B);">
                                        </div>
                                        <div class="choose-files mt-5">
                                            <label for="site_logo_dark">
                                                <div class=" bg-primary company_logo_update" style="cursor: pointer;">
                                                    <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                </div>
                                                <input type="file" name="site_logo_dark" id="site_logo_dark"
                                                    class="form-control file" data-filename="site_logo_dark">
                                            </label>
                                        </div>
                                        @error('site_logo_dark')
                                            <div class="row">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Site Description', __('Site Description'), ['class' => 'form-label']) }}
                                        {{ Form::text('site_description', $settings['site_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                                        @error('mail_port')
                                            <span class="invalid-mail_port" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                                value="{{ __('Save Changes') }}">
                        </div>
                        {{ Form::close() }}
                    </div>
    
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <h5>{{ __('Menu Bar') }}</h5>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                    <a data-size="lg" data-url="{{ route('custom_page.create') }}" data-ajax-popup="true"
                                        data-bs-toggle="tooltip" data-title="{{ __('Create Page') }}" title="{{ __('Create') }}" class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus text-light"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
    
                            {{-- <div class="justify-content-end d-flex">
    
                                        <a data-size="lg" data-url="{{ route('users.create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus text-light"></i>
                                        </a>
                                    </div> --}}
    
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (is_array($pages) || is_object($pages))
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($pages as $key => $value)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $value['menubar_page_name'] }}</td>
                                                    <td>
                                                        <span>
                                                            <div class="action-btn ms-2">
                                                                <a href="#"
                                                                    class="btn btn-sm btn-icon  bg-light-secondary me-2"
                                                                    data-url="{{ route('custom_page.edit', $key, $value['page_slug']) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Edit Page') }}"
                                                                    data-size="lg" data-bs-toggle="tooltip"
                                                                    title="{{ __('Edit') }}"
                                                                    data-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil"></i>
                                                                </a>
                                                            </div>
    
                                                            @if (
                                                                $value['page_slug'] != 'terms_and_conditions' &&
                                                                    $value['page_slug'] != 'about_us' &&
                                                                    $value['page_slug'] != 'privacy_policy')
                                                                <div class="action-btn ms-2">
                                                                    {!! Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['custom.page.delete', $value['page_slug'], $key],
                                                                        'id' => 'delete-form-' . $key,
                                                                    ]) !!}
                                                                    <a class=" show_confirm btn btn-sm btn-icon  bg-light-secondary me-2"
                                                                        href="#" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top" title="{{ __('Delete') }}">
                                                                        <i class="ti ti-trash f-20"></i>
                                                                    </a>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            @endif
                                                        </span>
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
                {{--  End for all settings tab --}}
            </div>
        </div>
    </div>
@endsection
