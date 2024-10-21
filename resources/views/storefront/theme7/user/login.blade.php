<div class="modal-body">
    <div class="product-view-body">
        <div class="title mb-2">
            <h6> {{__('Login Form')}}</h6>
        </div>
        <hr class="mb-3">
        {!! Form::open(
            [
                'route' => ['customer.login', $slug, !empty($is_cart) && $is_cart == true ? $is_cart : false],
                'class' => 'login-form-main',
            ],
            ['method' => 'POST'],
        ) !!}
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="">{{__('Email')}}</label>
                    {{Form::text('email',null,array('class'=>'form-control'))}}
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="">{{__('Password')}}</label>
                    {{Form::password('password',array('class'=>'form-control','id'=>'exampleInputPassword1'))}}
                </div>
            </div>
            <div class="float-left col-6">
                {{ __('Don\'t have account ?') }}
                <a data-url="{{ route('store.usercreate', $slug) }}" data-ajax-popup="true" data-title="Register"
                    data-toggle="modal" class="login-form-main-a text-primary">
                    <p>{{ __('Register') }}</p>
                </a>
            </div>
            <div class="form-group align-items-center text-right col-6">
                <button type="submit" class="btn  btn-secondary">{{ __('Login') }}</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
