@php
    $plan = Utility::user_plan();
@endphp
{{ Form::model($shipping, ['route' => ['shipping.update', $shipping->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-6"></div>
        <div class="col-6 text-end">
            @if ($plan['enable_chatgpt'] == 'on')
                <a class="btn btn-sm btn-primary" href="#" data-size="lg" data-ajax-popup-over="true"
                    data-url="{{ route('generate', ['products_shipping']) }}" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="{{ __('Generate') }}"
                    data-title="{{ __('Generate Shipping Name') }}"> <i
                        class="fas fa-robot"></i>{{ __('Generate With AI') }}
                </a>
            @endif
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('name', __('Name')) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('price', __('Price')) }}
                {{ Form::text('price', null, ['class' => 'form-control', 'placeholder' => __('Enter Price'), 'required' => 'required']) }}
                @error('price')
                    <span class="invalid-price" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group ">
                {{ Form::label('location', __('Location'), ['class' => 'form-control-label']) }}
                {!! Form::select('location[]', $locations, explode(',', $shipping->location_id), [
                    'class' => 'form-control multi-select',
                    'id' => 'note2',
                    'data-toggle' => 'select',
                    'multiple',
                ]) !!}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
</div>
{{ Form::close() }}
