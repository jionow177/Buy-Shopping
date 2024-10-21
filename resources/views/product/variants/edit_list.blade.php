
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr class="text-center">

            @foreach($variantArray as $variant)
                <th><span>{{ ucwords($variant) }}</span></th>
            @endforeach
            <th><span>{{ __('Price') }}</span></th>
            <th><span>{{ __('Quantity') }}</span></th>

        </tr>
        </thead>
        <tbody>
            @php
                $io=0;
            @endphp
            @foreach($possibilities as $counter => $possibility)
            @php
                $name = App\Models\ProductVariantOption::variant_name($possibility, $io, $product_id);
                if ($name['has_variant'] == 0) {
                    $io++;
                }
                @endphp
            <tr>
                @foreach(explode(' : ', $possibility) as $key => $values)
                    <td>
                        <input type="text" autocomplete="off" spellcheck="false" class="form-control" value="{{ $values }}" name="{{ !empty($name['has_name'][$key]) ? $name['has_name'][$key] : $name['has_name'][0] }}" readonly>
                    </td>
                @endforeach

                {!! Form::hidden($name['has_name'][0], $possibility) !!}

                <td>
                    <input type="number" id="vprice_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ __('Enter Price') }}" class="form-control"
                    name="{{ $name['price'] }}" value="{{ $name['price_val'] }}">
                </td>
                <td>
                    <input type="number" id="vquantity_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ __('Enter Quantity') }}" class="form-control"
                    name="{{ $name['qty'] }}" value="{{ $name['qty_val'] }}">
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
