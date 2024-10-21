@extends('layouts.admin')
@php
    // $profile=asset(Storage::url('uploads/profile/'));
    $profile = \App\Models\Utility::get_file('uploads/profile/');
    $users = \Auth::user();
@endphp
@section('page-title')
    {{ __('Profile') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-400 mb-0"> {{ __('Profile') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Profile') }}</li>
@endsection
@push('script-page')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,

        })
        $(".list-group-item").click(function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-primary');
        });

        function check_theme(color_val) {
            $('#theme_color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }
    </script>
@endpush
@section('action-btn')
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-3">
            <div class="card sticky-top" style="top:30px">
                <div class="list-group list-group-flush" id="useradd-sidenav">
                    <a href="#personal_info" class="list-group-item list-group-item-action">{{ __('Personal Information') }}
                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                    </a>

                    <a href="#change_password" class="list-group-item list-group-item-action">{{ __('Change Password') }}
                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-9">
            <div id="personal_info" class="card">
                <div class="card-header">
                    <h5>{{ __('Personal information') }}</h5>
                </div>
                <div class="card-body">
                    {{ Form::model($userDetail, ['route' => ['update.account'], 'method' => 'put', 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-md-6">
                            <div class="card-body pt-0 text-center">
                                <div class=" setting-card">
                                    <h4>{{__('Profile Picture')}}</h4>
                                    <div class="logo-content mt-4 d-flex justify-content-center">

                                        <img class="profile_img"
                                            src="{{ !empty($users->avatar) ? $profile . '/' . $users->avatar : $profile . '/avatar.png' }}"
                                            id="blah" width="100px" class="rounded-circle-avatar">
                                    </div>
                                    <div class="choose-files mt-4">
                                        <label for="file-1">
                                            <div class=" bg-primary profile_update" style="max-width: 100% !important;"> <i
                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                            </div>
                                            <input type="file" class="form-control file" name="profile" id="file-1"
                                                data-filename="profile_update">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-6 col-md-6">
                            <div class="card-body pt-0">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-group">
                                            {{ Form::label('name', __('Name')) }}
                                            {{ Form::text('name', null, ['class' => 'form-control font-style']) }}
                                            @error('name')
                                                <span class="invalid-name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    {{ Form::label('email', __('Email')) }}
                                    {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter User Email')]) }}
                                    @error('email')
                                        <span class="invalid-email" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="col-lg-12 text-end">
                        <input type="submit" value="{{ __('Save Changes') }}"
                        class="btn btn-print-invoice  btn-primary m-r-10">
                    </div>
                </div>
                {{ Form::close() }}

            </div>
            <div id="change_password" class="card">
                <div class="card-header">
                    <h5>{{ __('Change Password') }}</h5>
                </div>
                <div class="card-body">
                    {{ Form::model($userDetail, ['route' => ['update.password', $userDetail->id], 'method' => 'put']) }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('current_password', __('Current Password')) }}
                                {{ Form::password('current_password', ['class' => 'form-control', 'placeholder' => __('Enter Current Password')]) }}
                                @error('current_password')
                                    <span class="invalid-current_password" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('new_password', __('New Password')) }}
                                {{ Form::password('new_password', ['class' => 'form-control', 'placeholder' => __('Enter New Password')]) }}
                                @error('new_password')
                                    <span class="invalid-new_password" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('confirm_password', __('Re-type New Password')) }}
                                {{ Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => __('Enter Re-type New Password')]) }}
                                @error('confirm_password')
                                    <span class="invalid-confirm_password" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 text-end">
                            <input type="submit" value="{{ __('Change Password') }}"
                                class="btn btn-print-invoice  btn-primary m-r-10">
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>

            </div>
        </div>
    @endsection
    @push('script-page')
        <!--     <script !src="">
            $(document).ready(function() {
                $('.custom-list-group-item').on('click', function() {
                    var href = $(this).attr('data-href');
                    if (href == '#personal-info') {
                        $('#personal-info').show();
                        $('#change-password').hide();
                    } else {
                        $('#change-password').show();
                        $('#personal-info').hide();
                    }
                });
            });
        </script> -->
        <script>
            var scrollSpy = new bootstrap.ScrollSpy(document.body, {
                target: '#useradd-sidenav',
                offset: 300,

            })
            $(".list-group-item").click(function() {
                $('.list-group-item').filter(function() {
                    return this.href == id;
                }).parent().removeClass('text-primary');
            });
        </script>
    @endpush
