<div class="modal-body">
    <form method="POST" action="{{ route('get.product.variants.possibilities') }}" id="editvariants">
        @csrf
        {!! Form::hidden('variant_edit', 'edit') !!}

        @foreach ($productVariantOption as $kry => $variantOpt)
            <div class="card-body">
                <div class="form-group">
                    <h4> {{ $variantOpt['variant_name'] }}: <small>{{ __('Variant Options') }}</small> </h4>
                </div>
                <div class="form-group">
                    <input class="form-control" name="variant_edt[{{ $kry }}][variant_name]" type="hidden"
                        id="variant_name" value="{{ $variantOpt['variant_name'] }}">
                    <input class="form-control" name="variant_edt[{{ $kry }}][variant_options]" type="text"
                        id="variant_options"
                        placeholder="{{ __('Variant Options separated by|pipe symbol, i.e Black|Blue|Red') }}">
                </div>
        @endforeach
        <div class="form-group col-12 d-flex justify-content-end col-form-label">
            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light"
                data-bs-dismiss="modal">
            <input type="button" value="{{ __('Add Variants') }}" class="btn btn-primary addOredit-variants ms-2">
        </div>
</div>
</form>
</div>
<script>
    $(document).on('click', '.addOredit-variants', function(e) {
        e.preventDefault();
        var forms = $(this).parents('form');

        var hiddenVariantOptions = $('#hiddenVariantOptions').val();
        let form = document.getElementById('editvariants');
        let fd = new FormData(form);

        fd.append('hiddenVariantOptions', hiddenVariantOptions);
        $.ajax({
            type: 'POST',
            url: '{{ route('product.variants.possibilities', ['product_id' => $product_id]) }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: fd,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {

                $('#hiddenVariantOptions').val(data.hiddenVariantOptions);
                $('.variant-table').html(data.varitantHTML);
                $("#commonModal").modal('hide');
            },
        });

    });
</script>
