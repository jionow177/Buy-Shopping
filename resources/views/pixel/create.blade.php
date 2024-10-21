{{ Form::open(['route' => 'pixel.store', 'method' => 'post']) }}
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {{ Form::select('platform', $pixals_platforms, null, ['class' => 'form-control item form-control-solid  mb-7 mt-3', 'placeholder' => 'Please Select', 'required' => 'required']) }}
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    {{ Form::label('pixel_id', __('Pixel ID') . ' ' . '(%)', ['class' => 'col-form-label']) }}
                    {{ Form::text('pixel_id', null, ['class' => 'form-control', 'placeholder' => __('Enter Pixel ID'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="btn  btn-primary">{{ __('Save') }}</button>
    </div>
</div>

{{ Form::close() }}
