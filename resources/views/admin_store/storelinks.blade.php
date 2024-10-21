<div class="modal-body">
    <div class="row">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <td><b>{{ __('Store ') }}</b></td>
                        <td><b>{{ __('Store Link ') }}</b></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($storesNames as $key => $storesName)
                        <tr>
                            <td>{{ $storesName }}</td>
                            @foreach ($stores as $store)
                                @if ($store->name == $storesName)
                                    <td>
                                        <a href="{{ $store['store_url'] }}" target="_blank" class="text-danger">
                                            {{ $store['store_url'] }}</a>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
