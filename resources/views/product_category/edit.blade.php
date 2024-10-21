@php
    $plan = Utility::user_plan();
@endphp
{{ Form::model($productCategorie, ['route' => ['product_categorie.update', $productCategorie->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        @if ($plan['enable_chatgpt'] == 'on')
            <div class="col-6"></div>
            <div class="col-6 text-end">
                <a class="btn btn-sm btn-primary" href="#" data-size="lg" data-ajax-popup-over="true"
                    data-url="{{ route('generate', ['products_category']) }}" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="{{ __('Generate') }}"
                    data-title="{{ __('Generate Category Name') }}"> <i
                        class="fas fa-robot"></i>{{ __('Generate With AI') }}
                </a>
        @endif
    </div>
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Product Category'), 'required' => 'required']) }}
            @error('name')
                <span class="invalid-name" role="alert">
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
{{ Form::close() }}
