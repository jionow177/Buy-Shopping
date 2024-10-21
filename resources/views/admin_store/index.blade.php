@extends('layouts.admin')
@section('page-title')
    {{ __('WhatsStore') }}
@endsection
@section('title')
    {{ __('Store') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item active" aria-current="page">{{ __('Stores') }}</li>
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
            <a href="{{ route('store.grid') }}" class="btn btn-sm btn-icon  bg-light-secondary me-2"
                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Grid View') }}">
                <i class="ti ti-grid-dots f-30"></i>
            </a>
        </div>
        <div class="col-auto pe-0">
            <a href="{{ route('store.export', $store) }} " class="btn btn-sm btn-icon  bg-light-secondary me-2"
                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Export') }}">
                <i class="ti ti-download"></i>
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
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('custom/libs/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
@endpush
@section('content')
    <!-- Listing -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('User Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Stores') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Store Display') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $usr)
                                    <tr>
                                        <td>{{ $usr->name }}</td>
                                        <td>{{ $usr->email }}</td>
                                        <td>{{ $usr->stores->count() }}</td>
                                        <td>{{ !empty($usr->currentPlan->name) ? $usr->currentPlan->name : '-' }}</td>
                                        <td>{{ \App\Models\Utility::dateFormat($usr->created_at) }}</td>
                                        <td>
                                                <a href="#" data-size="md" class="form-switch disabled-form-switch"
                                                    data-url="{{ route('store-resource.edit.display', $usr->id) }}"
                                                    data-ajax-popup="true" class="action-item"
                                                    data-title="{{ __('Are You Sure?') }}"
                                                    data-title="{{ $usr->store_display == 1 ? 'Stores disable' : 'Store enable' }}">
                                                    <input type="checkbox" class="form-check-input" disabled="disabled"
                                                        name="store_display" id="{{ $usr->id }}"
                                                        {{ $usr->store_display == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $usr->id }}"></label>
                                                </a>
                                        </td>
                                        <td class="Action">
                                            <div class="d-flex">
                                                @if(Auth::user()->type == "super admin")
                                                    <a class="btn btn-sm btn-icon  bg-light-secondary me-2" data-size="lg"
                                                    data-url="{{ route('owner.info', $usr->id) }}" data-ajax-popup="true"
                                                    data-title="{{ __('Admin Hub') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Admin Hub') }}">
                                                    <i class="ti ti-atom"></i>
                                                    </a>

                                                    <a class="btn btn-sm btn-icon bg-light-secondary me-2"
                                                    href="{{ route('login.with.owner', $usr->id) }}"
                                                        data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Login As Owner') }}">
                                                    <i class="ti ti-replace"></i>
                                                    </a>

                                                    <a class="btn btn-sm btn-icon  bg-light-secondary me-2" data-size="lg"
                                                    data-url="{{ route('store.links', $usr->id) }}" data-ajax-popup="true"
                                                    data-title="{{ __('Store Links') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Store Links') }}">
                                                    <i class="ti ti-adjustments"></i>
                                                    </a>
                                                @endif

                                                {{-- @can('user login manage') --}}
                                                    @if ($usr->is_enable_login == 1)
                                                        <a href="{{ route('users.login', \Crypt::encrypt($usr->id)) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-bs-toggle="tooltip"
                                                        title="{{ __('Login Disable')}}">
                                                        <i class="ti ti-road-sign"></i>
                                                        </a>
                                                    @elseif ($usr->is_enable_login == 0 && $usr->password == null)
                                                        <a class="btn btn-sm btn-icon login_enable bg-light-secondary me-2" data-size="md"
                                                        data-url="{{ route('user.reset', \Crypt::encrypt($usr->id)) }}" data-ajax-popup="true"
                                                        data-title="{{ __('New Password') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-original-title="{{ __('Login Enable')}}">
                                                        <i class="ti ti-road-sign"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('users.login', \Crypt::encrypt($usr->id)) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2 login_enable" data-bs-toggle="tooltip"
                                                        title="{{ __('Login Enable')}}" data-bs-original-title="{{ __('Login Enable')}}">
                                                        <i class="ti ti-road-sign"></i>
                                                        </a>
                                                    @endif
                                                {{-- @endcan --}}
                                                @can('Edit Store')
                                                    <a class="btn btn-sm btn-icon  bg-light-secondary me-2" data-size="md"
                                                        data-url="{{ route('store-resource.edit', $usr->id) }}"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Store') }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit') }}">
                                                        <i class="ti ti-edit f-20"></i>
                                                    </a>
                                                @endcan
                                                @can('Upgrade Plans')
                                                    <a class="btn btn-sm btn-icon  bg-light-secondary me-2" data-size="md"
                                                        data-url="{{ route('plan.upgrade', $usr->id) }}" data-ajax-popup="true"
                                                        data-title="{{ __('Upgrade Plan') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="{{ __('Upgrade Plan') }}">
                                                        <i class="ti ti-trophy f-20"></i>
                                                    </a>
                                                @endcan
                                                @if ($usr->id != 2)
                                                    @can('Delete Store')
                                                        {!! Form::open(['method' => 'Delete', 'route' => ['store-resource.destroy', $usr->id]]) !!}
                                                        <a class="btn btn-sm btn-icon  bg-light-secondary me-2 show_confirm"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}">
                                                            <i class="ti ti-trash f-20"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    @endcan
                                                @endif
                                                @can('Reset Password')
                                                    <a class="btn btn-sm btn-icon  bg-light-secondary me-2" data-size="md"
                                                        data-url="{{ route('user.reset', \Crypt::encrypt($usr->id)) }}" data-ajax-popup="true"
                                                        data-title="{{ __('Reset Password') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="{{ __('Reset Password') }}">
                                                        <i class="ti ti-key f-20"></i>
                                                    </a>
                                                @endcan
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
