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
                        {{ Form::open(['route' => 'testimonials.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                        @csrf
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-6 col-sm-10">
                                        <h5>{{ __('Testimonials') }}</h5>
                                    </div>
                                    <div class="col switch-width text-end">
                                        <div class="form-group mb-0">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                                    class="" name="testimonials_status" id="testimonials_status"
                                                    {{ $settings['testimonials_status'] == 'on' ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label" for="testimonials_status"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                                            {{ Form::text('testimonials_heading', $settings['testimonials_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading')]) }}
                                            @error('mail_host')
                                                <span class="invalid-mail_driver" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                                            {{ Form::text('testimonials_description', $settings['testimonials_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                                            @error('testimonials_description')
                                                <span class="invalid-testimonials_description" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('Long Description', __('Long Description'), ['class' => 'form-label']) }}
                                            {{ Form::textarea('testimonials_long_description', $settings['testimonials_long_description'], ['class' => 'form-control summernote', 'id' => 'classic-editor', 'placeholder' => __('Enter Long Description')]) }}
                                            @error('testimonials_long_description')
                                                <span class="invalid-mail_port" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-print-invoice btn-primary m-r-10"
                                    type="submit">{{ __('Save Changes') }}</button>
                            </div>
                        {{ Form::close() }}
                    </div>
    
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <h5>{{ __('Testimonial List') }}</h5>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                    <a data-size="lg" data-url="{{ route('testimonials_create') }}" data-ajax-popup="true"
                                        data-bs-toggle="tooltip" data-title="{{ __('Create Testimonial') }}" title="{{ __('Create Testimonial') }}"
                                        class="btn btn-sm btn-primary">
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
                                        @if (is_array($testimonials) || is_object($testimonials))
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($testimonials as $key => $value)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $value['testimonials_title'] }}</td>
                                                    <td>
                                                        <span>
                                                            <div class="action-btn ms-2">
                                                                <a href="#" class="btn btn-sm btn-icon  bg-light-secondary me-2"
                                                                    data-url="{{ route('testimonials_edit', $key) }}"
                                                                    data-ajax-popup="true" data-title="{{ __('Edit Testimonial') }}"
                                                                    data-size="lg" data-bs-toggle="tooltip"
                                                                    title="{{ __('Edit Testimonial') }}"
                                                                    data-original-title="{{ __('Edit Testimonial') }}">
                                                                    <i class="ti ti-pencil"></i>
                                                                </a>
                                                            </div>
    
                                                            <div class="action-btn ms-2">
                                                                {!! Form::open(['method' => 'GET', 'route' => ['testimonials_delete', $key], 'id' => 'delete-form-' . $key]) !!}
    
                                                                <a class=" show_confirm btn btn-sm btn-icon  bg-light-secondary me-2"
                                                                    href="#" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="{{ __('Delete') }}">
                                                                    <i class="ti ti-trash f-20"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
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
@push('script-page')
    <script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
@endpush
