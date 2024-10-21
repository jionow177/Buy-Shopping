{{ Form::open(['route' => ['expresscheckout.update', $expresscheckout->id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        @if ($product->enable_product_variant == 'on')
            @foreach ($product_variant_names as $key => $variant)
                <div class="col-sm-6 mb-4 mb-sm-0">
                    <span class="d-block h6 mb-0">
                        <th>
                            <label for="" class="form-label"> {{ ucfirst($variant->variant_name) }}</label>
                        </th>

                        <select name="variant_name[]" id='choices-multiple-{{ $key }}' class="form-control"
                            required>
                            <option value="">{{ __('Select') }}</option>

                            @foreach ($variant->variant_options as $key => $values)
                                @php
                                    $selected = array_intersect(explode(' : ', $expresscheckout->variant_name), $variant->variant_options);
                                @endphp
                                <option value="{{ $values }}" @if (implode('', $selected) == $values) selected @endif>
                                    {{ $values }}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
            @endforeach
        @endif
        <div class="col-sm-6 mb-4 mb-sm-0">
            <div class="form-group">
                {{ Form::label('', __('Quantity'), ['class' => 'form-label']) }}
                {{ Form::number('quantity', $expresscheckout->quantity, ['class' => 'form-control', 'min' => 1, 'placeholder' => __('Enter product Quantity'), 'required']) }}
            </div>
            <input type="hidden" name="product_id" value="{{ $product->id }}">
        </div>
    </div>
    <div class="row">
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary">{{ __('Generate URL') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
