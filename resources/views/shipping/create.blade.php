@php
    $plan = Utility::user_plan();
@endphp
{{ Form::open(['url' => 'shipping', 'method' => 'post']) }}
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
                {{ Form::label('name', __('Name'), ['class' => 'form-control-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('price', __('Price'), ['class' => 'form-control-label']) }}
                {{ Form::text('price', null, ['class' => 'form-control', 'placeholder' => __('Enter Price'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('Location', __('Location'), ['class' => 'form-control-label']) }}
                {!! Form::select('location[]', $locations, null, [
                    'class' => 'form-control multi-select',
                    'id' => 'note1',
                    'data-toggle' => 'select',
                    'multiple',
                ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Save') }}</button>
</div>

{{ Form::close() }}
