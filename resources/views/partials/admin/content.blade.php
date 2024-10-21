<div class="dash-container">
    <div class="dash-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="d-block d-sm-flex align-items-center justify-content-between">
                            <div style="flex:none; margin-right:50px;">
                                <div class="page-header-title">
                                    <h4 class="m-b-10">@yield('page-title')</h4>
                                </div>
                                <ul class="breadcrumb">
                                    @yield('breadcrumb')
                                </ul>
                            </div>
                            <div>
                                @yield('action-btn')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            @yield('content')
    </div>
</div>
