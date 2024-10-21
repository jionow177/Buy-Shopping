@extends('layouts.admin')
@section('page-title')
    {{ __('Custom Domain Request') }}
@endsection
@section('title')
    {{ __('Custom Domain Request') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Custom Domain Request') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table pc-dt-simple">
                            <thead>
                                <tr>
                                    <th> {{ __('Owner Name') }}</th>
                                    <th> {{ __('Store Name') }}</th>
                                    <th> {{ __('Custom Domain') }}</th>
                                    <th> {{ __('Status') }}</th>
                                    <th width="200px"> {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($custom_domain_requests as $custom_domain_request)
                                    <tr>
                                        <td>
                                            <div class="font-style font-weight-bold">
                                                {{ $custom_domain_request->user->name }}</div>
                                        </td>
                                        <td>
                                            <div class="font-style font-weight-bold">{{ $custom_domain_request->store->name }}</div>
                                        </td>
                                        <td>
                                            <div class="font-style font-weight-bold">
                                                {{ $custom_domain_request->custom_domain }}</div>
                                        </td>
                                        <td>
                                            @if ($custom_domain_request->status == 0)
                                                <span
                                                    class="badge fix_badges bg-danger p-2 px-3 rounded">{{ __(App\Models\CustomDomainRequest::$statues[$custom_domain_request->status]) }}</span>
                                            @elseif($custom_domain_request->status == 1)
                                                <span
                                                    class="badge fix_badges bg-primary p-2 px-3 rounded">{{ __(App\Models\CustomDomainRequest::$statues[$custom_domain_request->status]) }}</span>
                                            @elseif($custom_domain_request->status == 2)
                                                <span
                                                    class="badge fix_badges bg-warning p-2 px-3 rounded">{{ __(App\Models\CustomDomainRequest::$statues[$custom_domain_request->status]) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @if($custom_domain_request->status == 0)
                                                    <a href="{{ route('custom_domain_request.request',[$custom_domain_request->id,1]) }}"
                                                        class="btn btn-sm btn-icon  bg-light-secondary me-2">
                                                        <i class="ti ti-check f-20"></i>
                                                    </a>
                                                    <a href="{{ route('custom_domain_request.request',[$custom_domain_request->id,0]) }}"
                                                        class="btn btn-sm btn-icon  bg-light-secondary me-2">
                                                        <i class="ti ti-x f-20"></i>
                                                    </a>
                                                @endif

                                                {!! Form::open(['method' => 'Delete', 'route' => ['custom_domain_request.destroy',$custom_domain_request->id]]) !!}
                                                <a class="btn btn-sm btn-icon  bg-light-secondary me-2 show_confirm"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash f-20"></i>
                                                </a>
                                                {!! Form::close() !!}
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
