@php
    $plan = Utility::user_plan();
@endphp
<form method="post" action="{{ route('product-coupon.update', $productCoupon->id) }}" id="product-coupon-store">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            @if ($plan['enable_chatgpt'] == 'on')
                <div class="col-6"></div>
                <div class="col-6 text-end">
                    <a class="btn btn-sm btn-primary" href="#" data-size="lg" data-ajax-popup-over="true"
                        data-url="{{ route('generate', ['products_coupon']) }}" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="{{ __('Generate') }}"
                        data-title="{{ __('Generate Coupon Name') }}">
                        <i class="fas fa-robot"></i>{{ __('Generate With AI') }}
                    </a>
                </div>
            @endif
            <div class="form-group col-md-12">
                <label for="name">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control" required value="{{ $productCoupon->name }}">
            </div>
            <div class="form-group col-md-12">
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" name="enable_flat" id="enable_flat"
                        {{ $productCoupon['enable_flat'] == 'on' ? 'checked=checked' : '' }}>
                    {{ Form::label('enable_flat', __('Flat Discount'), ['class' => 'form-check-label mb-3']) }}
                </div>
            </div>
            <div class="form-group col-md-6 nonflat_discount">
                {{ Form::label('discount', __('Discount'), ['class' => 'col-form-label']) }}
                {{ Form::number('discount', $productCoupon->discount, ['class' => 'form-control pro_discount', 'step' => '0.01', 'placeholder' => __('Enter Discount')]) }}
                <span class="small">{{ __('Note: Discount in Percentage') }}</span>
            </div>
            <div class="form-group col-md-6 flat_discount" style="display: none;">
                {{ Form::label('pro_flat_discount', __('Flat Discount'), ['class' => 'col-form-label']) }}
                {{ Form::number('pro_flat_discount', $productCoupon->flat_discount, ['class' => 'form-control pro_flat_discount', 'step' => '0.01', 'placeholder' => __('Enter Flat Discount')]) }}
                <span class="small">{{ __('Note: Discount in Value') }}</span>
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('limit', __('Limit'), ['class' => 'col-form-label']) }}
                {{ Form::number('limit', $productCoupon->limit, ['class' => 'form-control', 'placeholder' => __('Enter Limit'), 'required' => 'required']) }}
            </div>
            <div class="form-group col-md-12" id="auto">
                <label for="code">{{ __('Code') }}</label>
                <div class="input-group">
                    {{ Form::text('code', $productCoupon->code, ['class' => 'form-control', 'id' => 'auto-code', 'required' => 'required']) }}
                    <button class="btn btn-outline-secondary" type="button" id="code-generate"><i
                            class="fa fa-history pr-1"></i>{{ __(' Generate') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
    </div>
</form>
