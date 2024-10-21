@extends('layouts.admin')
@section('page-title')
    {{ __('Roles') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{ __('Roles') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Roles') }}</li>
@endsection
@section('action-btn')
    @can('Create Role')
        <a class="btn btn-sm btn-icon  btn-primary text-white me-2" data-url="{{ route('roles.create') }}" data-title="{{ __('Add Role') }}"
            data-size="lg" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
            <i data-feather="plus"></i>
        </a>
    @endcan
@endsection
@section('filter')
@endsection
@section('content')
    <div class="row">

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Permissions') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td style="white-space: inherit">
                                            @foreach ($role->permissions()->pluck('name') as $permission)
                                                <span class="badge rounded p-2 m-1 px-3 bg-primary ">
                                                    <a href="#" class="text-white">{{ $permission }}</a>
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="Action">
                                            <span>
                                                @can('Edit Role')
                                                    <div class="action-btn ms-2">
                                                        <a class="btn btn-sm btn-icon  bg-light-secondary me-2"
                                                            data-url="{{ URL::to('roles/' . $role->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Role') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Role')
                                                    <div class="action-btn ms-2">
                                                        <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary"
                                                            href="#" data-title="{{ __('Delete Role') }}"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $role->id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}">
                                                            <i class="ti ti-trash f-20"></i>
                                                        </a>
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['roles.destroy', $role->id],
                                                            'id' => 'delete-form-' . $role->id,
                                                        ]) !!}
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
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
