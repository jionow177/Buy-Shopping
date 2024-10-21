@extends('layouts.admin')
@section('page-title')
    {{__('Location')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h5 d-inline-block text-white font-weight-bold mb-0 ">{{__('Location')}}</h5>
    </div>
@endsection
@section('action-btn')

@endsection
@section('filter')
@endsection
@section('content')
    <div class="card">
        <!-- Table -->
        <div class="table-responsive">
            <div class="employee_menu view_employee">
                <div class="card-header actions-toolbar border-0">
                    <div class="row justify-content-between align-items-center">
                        <div class="col">
                            <h6 class="d-inline-block mb-0 text-capitalize">{{__('Location')}}</h6>
                        </div>
                        <div class="col text-right">
                            <div class="actions">
                                <div class="rounded-pill d-inline-block search_round">
                                    <div class="input-group input-group-sm input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                                        </div>
                                        <input type="text" id="user_keyword" class="form-control form-control-flush search-user" placeholder="{{__('Search Location')}}">
                                    </div>
                                </div>
                                <a href="#" data-size="lg" data-url="{{ route('location.create') }}" data-ajax-popup="true" data-title="{{__('Create New Location')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table align-items-center employee_tableese">
                        <thead>
                        <tr>
                            <th scope="col" class="sort" data-sort="name">{{__('Name')}}</th>
                            <th scope="col" class="sort" data-sort="name">{{__('Created At')}}</th>
                            <th class="text-right">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($locations as $location)
                            <tr data-name="{{$location->name}}">
                                <td class="sorting_1">{{$location->name}}</td>
                                <td class="sorting_1">{{\App\Models\Utility::dateFormat($location->created_at)}}</td>
                                <td class="action text-right">   <div class="banner-col-right col-lg-10 col-12">
                                    <div class="content-wrapper">
                                        <div class="content-body">
                                            <div class="section-title d-flex align-items-center justify-content-between">
                                                <h2><b>Products you purchased</b></h2>
                                                <a href="index.html" class="btn">Back to home</a>
                                            </div>
                                            <div class="purchased-list">
                                                <table class="purchased-tabel">
                                                    <thead>
                                                        <tr>
                                                            <th>Order</th>
                                                            <th>Date</th>
                                                            <th>Value</th>
                                                            <th>Payment Type</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><a href="order-detail.html">#1659337530</a></td>
                                                            <td>Aug 1, 2022</td>
                                                            <td>$ 198</td>
                                                            <td>COD</td>
                                                            <td>
                                                                <div class="actions-wrapper">
                                                                    <span class="badge rounded-pill"> <i class="fas fa-check soft-success"></i>
                                                                        Pending: Aug 1, 2022 </span>
                                                                </div>
                                                            </td>
                                                            <td><a href="order-detail.html" class="view-btn">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" contentscripttype="text/ecmascript" contentstyletype="text/css" enable-background="new 0 0 2048 2048" height="2048px" id="Layer_1" preserveAspectRatio="xMidYMid meet" version="1.1" viewBox="0.0 0 1792.0 2048" width="1792.0px" xml:space="preserve" zoomAndPan="magnify"><path d="M1664,1088c-101.333-157.333-228.333-275-381-353c40.667,69.333,61,144.333,61,225c0,123.333-43.833,228.833-131.5,316.5  S1019.333,1408,896,1408s-228.833-43.833-316.5-131.5S448,1083.333,448,960c0-80.667,20.333-155.667,61-225  c-152.667,78-279.667,195.667-381,353c88.667,136.667,199.833,245.5,333.5,326.5S740,1536,896,1536s300.833-40.5,434.5-121.5  S1575.333,1224.667,1664,1088z M944,704c0-13.333-4.667-24.667-14-34s-20.667-14-34-14c-83.333,0-154.833,29.833-214.5,89.5  S592,876.667,592,960c0,13.333,4.667,24.667,14,34s20.667,14,34,14s24.667-4.667,34-14s14-20.667,14-34  c0-57.333,20.333-106.333,61-147s89.667-61,147-61c13.333,0,24.667-4.667,34-14S944,717.333,944,704z M1792,1088  c0,22.667-6.667,45.667-20,69c-93.333,153.333-218.833,276.167-376.5,368.5S1071.333,1664,896,1664s-341.833-46.333-499.5-139  S113.333,1309.667,20,1157c-13.333-23.333-20-46.333-20-69s6.667-45.667,20-69c93.333-152.667,218.833-275.333,376.5-368  S720.667,512,896,512s341.833,46.333,499.5,139s283.167,215.333,376.5,368C1785.333,1042.333,1792,1065.333,1792,1088z"></path></svg>
                                                            </a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><a href="order-detail.html">#1659337530</a></td>
                                                            <td>Aug 1, 2022</td>
                                                            <td>$ 198</td>
                                                            <td>COD</td>
                                                            <td>
                                                                <div class="actions-wrapper">
                                                                    <span class="badge rounded-pill"> <i class="fas fa-check soft-success"></i>
                                                                        Pending: Aug 1, 2022 </span>
                                                                </div>
                                                            </td>
                                                            <td><a href="order-detail.html" class="view-btn">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" contentscripttype="text/ecmascript" contentstyletype="text/css" enable-background="new 0 0 2048 2048" height="2048px" id="Layer_1" preserveAspectRatio="xMidYMid meet" version="1.1" viewBox="0.0 0 1792.0 2048" width="1792.0px" xml:space="preserve" zoomAndPan="magnify"><path d="M1664,1088c-101.333-157.333-228.333-275-381-353c40.667,69.333,61,144.333,61,225c0,123.333-43.833,228.833-131.5,316.5  S1019.333,1408,896,1408s-228.833-43.833-316.5-131.5S448,1083.333,448,960c0-80.667,20.333-155.667,61-225  c-152.667,78-279.667,195.667-381,353c88.667,136.667,199.833,245.5,333.5,326.5S740,1536,896,1536s300.833-40.5,434.5-121.5  S1575.333,1224.667,1664,1088z M944,704c0-13.333-4.667-24.667-14-34s-20.667-14-34-14c-83.333,0-154.833,29.833-214.5,89.5  S592,876.667,592,960c0,13.333,4.667,24.667,14,34s20.667,14,34,14s24.667-4.667,34-14s14-20.667,14-34  c0-57.333,20.333-106.333,61-147s89.667-61,147-61c13.333,0,24.667-4.667,34-14S944,717.333,944,704z M1792,1088  c0,22.667-6.667,45.667-20,69c-93.333,153.333-218.833,276.167-376.5,368.5S1071.333,1664,896,1664s-341.833-46.333-499.5-139  S113.333,1309.667,20,1157c-13.333-23.333-20-46.333-20-69s6.667-45.667,20-69c93.333-152.667,218.833-275.333,376.5-368  S720.667,512,896,512s341.833,46.333,499.5,139s283.167,215.333,376.5,368C1785.333,1042.333,1792,1065.333,1792,1088z"></path></svg>
                                                            </a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><a href="order-detail.html">#1659337530</a></td>
                                                            <td>Aug 1, 2022</td>
                                                            <td>$ 198</td>
                                                            <td>COD</td>
                                                            <td>
                                                                <div class="actions-wrapper">
                                                                    <span class="badge rounded-pill"> <i class="fas fa-check soft-success"></i>
                                                                        Pending: Aug 1, 2022 </span>
                                                                </div>
                                                            </td>
                                                            <td><a href="order-detail.html" class="view-btn">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" contentscripttype="text/ecmascript" contentstyletype="text/css" enable-background="new 0 0 2048 2048" height="2048px" id="Layer_1" preserveAspectRatio="xMidYMid meet" version="1.1" viewBox="0.0 0 1792.0 2048" width="1792.0px" xml:space="preserve" zoomAndPan="magnify"><path d="M1664,1088c-101.333-157.333-228.333-275-381-353c40.667,69.333,61,144.333,61,225c0,123.333-43.833,228.833-131.5,316.5  S1019.333,1408,896,1408s-228.833-43.833-316.5-131.5S448,1083.333,448,960c0-80.667,20.333-155.667,61-225  c-152.667,78-279.667,195.667-381,353c88.667,136.667,199.833,245.5,333.5,326.5S740,1536,896,1536s300.833-40.5,434.5-121.5  S1575.333,1224.667,1664,1088z M944,704c0-13.333-4.667-24.667-14-34s-20.667-14-34-14c-83.333,0-154.833,29.833-214.5,89.5  S592,876.667,592,960c0,13.333,4.667,24.667,14,34s20.667,14,34,14s24.667-4.667,34-14s14-20.667,14-34  c0-57.333,20.333-106.333,61-147s89.667-61,147-61c13.333,0,24.667-4.667,34-14S944,717.333,944,704z M1792,1088  c0,22.667-6.667,45.667-20,69c-93.333,153.333-218.833,276.167-376.5,368.5S1071.333,1664,896,1664s-341.833-46.333-499.5-139  S113.333,1309.667,20,1157c-13.333-23.333-20-46.333-20-69s6.667-45.667,20-69c93.333-152.667,218.833-275.333,376.5-368  S720.667,512,896,512s341.833,46.333,499.5,139s283.167,215.333,376.5,368C1785.333,1042.333,1792,1065.333,1792,1088z"></path></svg>
                                                            </a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><a href="order-detail.html">#1659337530</a></td>
                                                            <td>Aug 1, 2022</td>
                                                            <td>$ 198</td>
                                                            <td>COD</td>
                                                            <td>
                                                                <div class="actions-wrapper">
                                                                    <span class="badge rounded-pill"> <i class="fas fa-check soft-success"></i>
                                                                        Pending: Aug 1, 2022 </span>
                                                                </div>
                                                            </td>
                                                            <td><a href="order-detail.html" class="view-btn">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" contentscripttype="text/ecmascript" contentstyletype="text/css" enable-background="new 0 0 2048 2048" height="2048px" id="Layer_1" preserveAspectRatio="xMidYMid meet" version="1.1" viewBox="0.0 0 1792.0 2048" width="1792.0px" xml:space="preserve" zoomAndPan="magnify"><path d="M1664,1088c-101.333-157.333-228.333-275-381-353c40.667,69.333,61,144.333,61,225c0,123.333-43.833,228.833-131.5,316.5  S1019.333,1408,896,1408s-228.833-43.833-316.5-131.5S448,1083.333,448,960c0-80.667,20.333-155.667,61-225  c-152.667,78-279.667,195.667-381,353c88.667,136.667,199.833,245.5,333.5,326.5S740,1536,896,1536s300.833-40.5,434.5-121.5  S1575.333,1224.667,1664,1088z M944,704c0-13.333-4.667-24.667-14-34s-20.667-14-34-14c-83.333,0-154.833,29.833-214.5,89.5  S592,876.667,592,960c0,13.333,4.667,24.667,14,34s20.667,14,34,14s24.667-4.667,34-14s14-20.667,14-34  c0-57.333,20.333-106.333,61-147s89.667-61,147-61c13.333,0,24.667-4.667,34-14S944,717.333,944,704z M1792,1088  c0,22.667-6.667,45.667-20,69c-93.333,153.333-218.833,276.167-376.5,368.5S1071.333,1664,896,1664s-341.833-46.333-499.5-139  S113.333,1309.667,20,1157c-13.333-23.333-20-46.333-20-69s6.667-45.667,20-69c93.333-152.667,218.833-275.333,376.5-368  S720.667,512,896,512s341.833,46.333,499.5,139s283.167,215.333,376.5,368C1785.333,1042.333,1792,1065.333,1792,1088z"></path></svg>
                                                            </a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="copyright d-flex align-items-center justify-content-between">
                                                <div class="text-store">
                                                    <p class="text-left">© 2020 Whatspay. All rights reserved</p>
                                                </div>
                                                <div class="icone-store">
                                                    <ul class="nav">
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="mailto:owner@example.com" target="_blank">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 409.592 409.592" style="enable-background:new 0 0 409.592 409.592;" xml:space="preserve">
                                                                    <g>
                                                                        <g>
                                                                            <path d="M403.882,107.206c-2.15-17.935-19.052-35.133-36.736-37.437c-107.837-13.399-216.883-13.399-324.685,0    C24.762,72.068,7.86,89.271,5.71,107.206c-7.613,65.731-7.613,129.464,0,195.18c2.15,17.935,19.052,35.149,36.751,37.437    c107.802,13.399,216.852,13.399,324.685,0c17.684-2.284,34.586-19.502,36.736-37.437    C411.496,236.676,411.496,172.937,403.882,107.206z M170.661,273.074V136.539l102.4,68.27L170.661,273.074z"/>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="https://wa.me/https://wa.me/1XXXXXXXXXX" target="”_blank”">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 433.664 433.664" style="enable-background:new 0 0 433.664 433.664;" xml:space="preserve">
                                                                    <g>
                                                                        <g>
                                                                            <path d="M229.376,271.616c-4.096,2.56-8.704,3.584-12.8,3.584s-8.704-1.024-12.8-3.584L0,147.2v165.376c0,35.328,28.672,64,64,64    h305.664c35.328,0,64-28.672,64-64V147.2L229.376,271.616z"/>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                        <g>
                                                                            <path d="M369.664,57.088H64c-30.208,0-55.808,21.504-61.952,50.176l215.04,131.072l214.528-131.072    C425.472,78.592,399.872,57.088,369.664,57.088z"/>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="https://www.facebook.com/" target="_blank">
                                                                <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" height="512" viewBox="0 0 512 512" width="512" data-name="Layer 1"><path d="m420 36h-328a56 56 0 0 0 -56 56v328a56 56 0 0 0 56 56h160.67v-183.076h-36.615v-73.23h36.312v-33.094c0-29.952 14.268-76.746 77.059-76.746l56.565.227v62.741h-41.078c-6.679 0-16.183 3.326-16.183 17.592v29.285h58.195l-6.68 73.23h-54.345v183.071h94.1a56 56 0 0 0 56-56v-328a56 56 0 0 0 -56-56z"/></svg>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="https://www.instagram.com/" target="_blank">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                                    <g>
                                                                        <g>
                                                                            <path d="M371.643,0H140.357C62.964,0,0,62.964,0,140.358v231.285C0,449.037,62.964,512,140.357,512h231.286    C449.037,512,512,449.037,512,371.643V140.358C512,62.964,449.037,0,371.643,0z M481.764,371.643    c0,60.721-49.399,110.121-110.121,110.121H140.357c-60.721,0-110.121-49.399-110.121-110.121V140.358    c0-60.722,49.4-110.122,110.121-110.122h231.286c60.722,0,110.121,49.4,110.121,110.122V371.643z"/>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                        <g>
                                                                            <path d="M256,115.57c-77.434,0-140.431,62.997-140.431,140.431S178.565,396.432,256,396.432    c77.434,0,140.432-62.998,140.432-140.432S333.434,115.57,256,115.57z M256,366.197c-60.762,0-110.196-49.435-110.196-110.197    c0-60.762,49.434-110.196,110.196-110.196c60.763,0,110.197,49.435,110.197,110.197C366.197,316.763,316.763,366.197,256,366.197z    "/>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                        <g>
                                                                            <path d="M404.831,64.503c-23.526,0-42.666,19.141-42.666,42.667c0,23.526,19.14,42.666,42.666,42.666    c23.526,0,42.666-19.141,42.666-42.667S428.357,64.503,404.831,64.503z M404.831,119.599c-6.853,0-12.43-5.576-12.43-12.43    s5.577-12.43,12.43-12.43c6.854,0,12.43,5.577,12.43,12.43S411.685,119.599,404.831,119.599z"/>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="https://twitter.com/" target="_blank">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                                                    <g>
                                                                        <g>
                                                                            <path d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016    c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992    c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056    c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152    c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792    c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44    C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568    C480.224,136.96,497.728,118.496,512,97.248z"/>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="https://www.youtube.com/" target="_blank">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;" xml:space="preserve">
                                                                    <g>
                                                                        <g>
                                                                            <path d="M26,0C11.663,0,0,11.663,0,26c0,4.891,1.359,9.639,3.937,13.762C2.91,43.36,1.055,50.166,1.035,50.237    c-0.096,0.352,0.007,0.728,0.27,0.981c0.263,0.253,0.643,0.343,0.989,0.237L12.6,48.285C16.637,50.717,21.26,52,26,52    c14.337,0,26-11.663,26-26S40.337,0,26,0z M26,50c-4.519,0-8.921-1.263-12.731-3.651c-0.161-0.101-0.346-0.152-0.531-0.152    c-0.099,0-0.198,0.015-0.294,0.044l-8.999,2.77c0.661-2.413,1.849-6.729,2.538-9.13c0.08-0.278,0.035-0.578-0.122-0.821    C3.335,35.173,2,30.657,2,26C2,12.767,12.767,2,26,2s24,10.767,24,24S39.233,50,26,50z"/>
                                                                            <path d="M42.985,32.126c-1.846-1.025-3.418-2.053-4.565-2.803c-0.876-0.572-1.509-0.985-1.973-1.218    c-1.297-0.647-2.28-0.19-2.654,0.188c-0.047,0.047-0.089,0.098-0.125,0.152c-1.347,2.021-3.106,3.954-3.621,4.058    c-0.595-0.093-3.38-1.676-6.148-3.981c-2.826-2.355-4.604-4.61-4.865-6.146C20.847,20.51,21.5,19.336,21.5,18    c0-1.377-3.212-7.126-3.793-7.707c-0.583-0.582-1.896-0.673-3.903-0.273c-0.193,0.039-0.371,0.134-0.511,0.273    c-0.243,0.243-5.929,6.04-3.227,13.066c2.966,7.711,10.579,16.674,20.285,18.13c1.103,0.165,2.137,0.247,3.105,0.247    c5.71,0,9.08-2.873,10.029-8.572C43.556,32.747,43.355,32.331,42.985,32.126z M30.648,39.511    c-10.264-1.539-16.729-11.708-18.715-16.87c-1.97-5.12,1.663-9.685,2.575-10.717c0.742-0.126,1.523-0.179,1.849-0.128    c0.681,0.947,3.039,5.402,3.143,6.204c0,0.525-0.171,1.256-2.207,3.293C17.105,21.48,17,21.734,17,22c0,5.236,11.044,12.5,13,12.5    c1.701,0,3.919-2.859,5.182-4.722c0.073,0.003,0.196,0.028,0.371,0.116c0.36,0.181,0.984,0.588,1.773,1.104    c1.042,0.681,2.426,1.585,4.06,2.522C40.644,37.09,38.57,40.701,30.648,39.511z"/>
                                                                        </g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                    <g>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <a href="#" data-size="lg" data-url="{{ route('location.edit',$location->id) }}" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-ajax-popup="true" data-title="{{__('Edit type')}}" class="action-item">                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$location->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['location.destroy', $location->id],'id'=>'delete-form-'.$location->id]) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-page')
    <script>
        $(document).ready(function () {
            $(document).on('keyup', '.search-user', function () {
                var value = $(this).val();
                $('.employee_tableese tbody>tr').each(function (index) {
                    var name = $(this).attr('data-name');
                    if (name.includes(value)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endpush
