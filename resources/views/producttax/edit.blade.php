{{ Form::model($productTax, ['route' => ['product_tax.update', $productTax->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {{ Form::label('tax_name', __('Tax Name'), ['class' => 'col-form-label']) }}
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Account Type'), 'required' => 'required']) }}
                    @error('tax_name')
                        <span class="invalid-tax_name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    {{ Form::label('rate', __('Rate'), ['class' => 'col-form-label']) }}
                    {{ Form::text('rate', null, ['class' => 'form-control', 'placeholder' => __('Enter Account Type'), 'required' => 'required']) }}
                    @error('rate')
                        <span class="invalid-rate" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
    </div>
</div>
{{ Form::close() }}
