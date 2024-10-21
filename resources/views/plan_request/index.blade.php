@extends('layouts.admin')
@section('page-title')
    {{ __('Plan-Request') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item active" aria-current="page">{{ __('Plan Request') }}</li>
@endsection

@section('action-btn')
    <div class="row  m-1">
        <div class="col-auto pe-0">
            <a class="btn btn-sm btn-icon  bg-light-secondary me-2" href="{{ route('planrequests.export', $plan_requests) }}"
                data-bs-toggle="tooltip" data-bs-original-title="{{ __('Export') }}">
                <i data-feather="download"></i>
            </a>
        </div>
    </div>
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
                                    <th>{{ __('User Name') }}</th>
                                    <th>{{ __('Plan Name') }}</th>
                                    <th>{{ __('Max Product') }}</th>
                                    <th>{{ __('Max Store') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('expiry date') }}</th>
                                    <th width="150px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($plan_requests) && !empty($plan_requests))
                                    @foreach ($plan_requests as $prequest)
                                        <tr>
                                            <td>

                                                <div class="font-style font-weight-bold">{{ $prequest->user->name }}</div>
                                            </td>
                                            <td>
                                                <div class="font-style font-weight-bold">{{ $prequest->plan->name }}</div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">{{ $prequest->plan->max_products }}</div>
                                                {{-- <div>{{__('Employee')}}</div> --}}
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">{{ $prequest->plan->max_stores }}</div>
                                                {{-- <div>{{__('Client')}}</div> --}}
                                            </td>
                                            <td>
                                                <div class="font-style font-weight-bold">
                                                    @if($prequest->duration == 'Month')
                                                        {{__('One Month')}}
                                                    @elseif($prequest->duration == 'Year')
                                                        {{__('One Year')}}
                                                    @else
                                                    {{__('Lifetime')}}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ \App\Models\Utility::getDateFormated($prequest->created_at, true) }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('response.request', [$prequest->id, 1]) }}"
                                                        class="btn btn-sm btn-icon  bg-light-secondary me-2">
                                                        <i class="ti ti-check f-20"></i>
                                                    </a>
                                                    <a href="{{ route('response.request', [$prequest->id, 0]) }}"
                                                        class="btn btn-sm btn-icon  bg-light-secondary me-2">
                                                        <i class="ti ti-x f-20"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center">
                                            <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                            <h2>Opps...</h2>
                                            <h6>No data Found. </h6>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
