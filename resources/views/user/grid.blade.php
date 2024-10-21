@extends('layouts.admin')
@section('page-title')
    {{ __('WhatsStore') }}
@endsection
@section('title')
    {{ __('Store') }}
@endsection
@php
    $profile = \App\Models\Utility::get_file('uploads/profile/');
@endphp
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Home') }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('User') }}</li>
@endsection
@section('action-btn')
    <div class="row gy-4  m-1">
        <div class="col-auto pe-0">
            <a href="{{ route('store.subDomain') }}" class="btn btn-sm btn-light-primary me-2">
                {{ __('Sub Domain') }}
            </a>
        </div>
        <div class="col-auto pe-0">
            <a class="btn btn-sm btn-light-primary me-2" href="{{ route('store.customDomain') }}">
                {{ __('Custom Domain') }}
            </a>
        </div>
        <div class="col-auto pe-0">
            <a href="{{ route('store-resource.index') }}" class="btn btn-sm btn-icon  bg-light-secondary me-2"
                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('List View') }}">
                <i class="ti ti-list f-30"></i>
            </a>
        </div>
        <div class="col-auto pe-0">
            <a class="btn btn-sm btn-icon text-white btn-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create ') }}" data-size="md" data-ajax-popup="true"
                data-title="{{ __('Create New Store') }}" data-url="{{ route('store-resource.create') }}">
                <i data-feather="plus"></i>
            </a>
        </div>
    </div>



    {{-- <div class="row  m-1">
        <div class="col-auto pe-0">
            <a href="{{ route('store.customDomain') }}" class="btn btn-sm btn-primary btn-icon">
                {{ __('Sub Domain') }}
            </a>
        </div>
        <div class="col-auto pe-0">
            <a href="{{ route('store.customDomain') }}" class="btn btn-sm btn-primary btn-icon">
                {{ __('Custom Domain') }}
            </a>
        </div>
        <div class="col-auto pe-0">
            <div class="col-auto pe-0">
                <a href="{{ route('store-resource.index') }}" class="btn btn-sm btn-primary btn-icon"
                    class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('List View') }}"><i class="fas fa-list text-white"></i></a>
            </div>
        </div>
        <div class="col-auto pe-0">
            <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create New Store') }}" data-size="lg" data-ajax-popup="true"
                data-title="{{ __('Create New Store') }}" data-url="{{ route('store-resource.create') }}">
                <i class="ti ti-plus text-white"></i>
            </a>
        </div>
    </div> --}}
@endsection
@section('filter')
@endsection
@section('content')
    @if (\Auth::user()->type = 'super admin')
        <div class="row">
            @foreach ($users as $user)
                <div class="col-md-4 col-xxl-3">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a href="#" data-size="md" data-url="{{ route('user.edit', $user->id) }}"
                                            data-ajax-popup="true" data-title="{{ __('Edit User') }}"
                                            class="dropdown-item"><i class="ti ti-edit"></i>
                                            <span>{{ __('Edit') }}</span></a>

                                        <a href="#" data-size="md" data-url="{{ route('plan.upgrade', $user->id) }}"
                                            data-ajax-popup="true" data-title="{{ __('Upgrade Plan') }}"
                                            class="dropdown-item"><i class="ti ti-trophy"></i>
                                            <span>{{ __('Upgrade Plan') }}</span></a>

                                        @if(Auth::user()->type == "super admin")
                                            <a href="{{ route('login.with.owner',$user->id) }}" class="dropdown-item"
                                                data-bs-original-title="{{ __('Login As Owner') }}">
                                                <i class="ti ti-replace"></i>
                                                <span> {{ __('Login As Owner') }}</span>
                                            </a>
                                            <a href="#" data-size="lg" data-url="{{ route('store.links', $user->id) }}"
                                                data-ajax-popup="true" data-title="{{ __('Store Links') }}"
                                                class="dropdown-item"><i class="ti ti-adjustments"></i>
                                                <span>{{ __('Store Links') }}</span></a>
                                        @endif

                                        <a href="#" data-size="md" data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"
                                            data-ajax-popup="true" data-title="{{ __('Reset Password') }}"
                                            class="dropdown-item"><i class="ti ti-key"></i>
                                            <span>{{ __('Reset Password') }}</span>
                                        </a>


                                        @if ($user->id != 2)
                                            {{ Form::open(['route' => ['store-resource.destroy', $user->id], 'class' => 'm-0']) }}
                                                @method('DELETE')
                                                <a href="#!" class="dropdown-item bs-pass-para show_confirm" aria-label="Delete"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $user->id }}">
                                                    <i class="ti ti-trash"></i>
                                                    <span>{{ __('Delete') }}</span>
                                                </a>
                                            {!! Form::close() !!}
                                        @endif

                                        {{-- @permission('user login manage')  --}}
                                            @if ($user->is_enable_login == 1)
                                                <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                                    class="dropdown-item">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-danger"> {{ __('Login Disable') }}</span>
                                                </a>
                                            @elseif ($user->is_enable_login == 0 && $user->password == null)
                                                <a href="#" data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"
                                                    data-ajax-popup="true" data-size="md" class="dropdown-item login_enable"
                                                    data-title="{{ __('New Password') }}">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-success"> {{ __('Login Enable') }}</span>
                                                </a>
                                            @else
                                                <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                                    class="dropdown-item login_enable">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-success"> {{ __('Login Enable') }}</span>
                                                </a>
                                            @endif
                                        {{-- @endpermission  --}}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="avatar-parent-child">
                                <img alt=""
                                    src="{{ !empty($user->avatar) ? $profile . '/' . $user->avatar : $profile . '/avatar.png' }}"class="img-fluid rounded-circle card-avatar">
                            </div>

                            <h5 class="h6 mt-4 mb-0"> {{ $user->name }}</h5>
                            <a href="#" class="d-block text-sm text-muted my-4"> {{ $user->email }}</a>
                            <div class="card mb-0 mt-3">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6 class="mb-0">{{ $user->countProducts($user->id) }}</h6>
                                            <p class="text-muted text-sm mb-0">{{ __('Products') }}</p>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-0">{{ $user->countStores($user->id) }}</h6>
                                            <p class="text-muted text-sm mb-0">{{ __('Stores') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer row">
                            <div class="col-6 pe-0">
                                <div class="actions d-flex justify-content-between">
                                    <span class="d-block text-sm text-muted"> {{ __('Plan') }} :
                                        {{ $user->currentPlan->name }}</span>
                                </div>
                                <div class="actions d-flex justify-content-between mt-1">
                                    <span class="d-block text-sm text-muted">{{ __('Plan Expired') }} :
                                        {{ !empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date) : 'Lifetime' }}</span>
                                </div>
                            </div>
                            <div class="col-6 text-center Id ">
                                <a href="#" data-url="{{route('owner.info', $user->id)}}" data-size="lg" data-ajax-popup="true" class="btn btn-outline-primary" data-title="{{__('Owner Info')}}">{{__('Admin Hub')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-md-4 col-xxl-3">
                {{-- <a href="{{ route('product.create') }}" class="btn-addnew-project" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create')}}"><i class="ti ti-plus text-white"></i> --}}
                <a href="#" class="btn-addnew-project" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Create New Store') }}" data-size="lg" data-ajax-popup="true"
                    data-title="{{ __('Create New Store') }}" data-url="{{ route('store-resource.create') }}">
                    <div class="bg-primary proj-add-icon">
                        <i class="ti ti-plus"></i>
                    </div>
                    <h6 class="mt-4 mb-2">{{__('New Store')}}</h6>
                    <p class="text-muted text-center">{{__('Click here to add New Store')}}</p>
                </a>
                <div class="card-body text-center">
                </div>
                <div class="card-footer">
                    <div class="actions d-flex justify-content-between">
                    </div>
                    <div class="actions d-flex justify-content-between mt-1">
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script-page')

    {{-- Password  --}}
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
@endpush