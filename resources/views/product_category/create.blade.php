@php
    $plan = Utility::user_plan();
@endphp
{{ Form::open(['url' => 'product_categorie', 'method' => 'post']) }}
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            @if ($plan['enable_chatgpt'] == 'on')
            <div class="col-6"></div>
                <div class="col-6 text-end">
                    <a class="btn btn-sm btn-primary mb-4" href="#" data-size="lg" data-ajax-popup-over="true"
                        data-url="{{ route('generate', ['products_category']) }}" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="{{ __('Generate') }}"
                        data-title="{{ __('Generate Category Name') }}"> <i class="fas fa-robot"></i>
                        {{ __('Generate With AI') }}
                    </a>
                </div>
            @endif
            <div class="col-12">
                <div class="form-group">
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Product Category'), 'required' => 'required']) }}
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
