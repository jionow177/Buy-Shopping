    <div class="modal-body">
    {{-- <form method="POST" action="https://demo.workdo.io/whatsstore-saas/my-whatsstore/customer-login" accept-charset="UTF-8" class="login-form-main"><input name="_token" type="hidden" value="CTe9lqYafL8ynU4ctpHU3BXOQPqLx4bIFOvADMFt"> --}}
        {!! Form::open(array('route' => array('customer.login', $slug,(!empty($is_cart) && $is_cart==true)?$is_cart:false),'class'=>'login-form-main'),['method'=>'POST']) !!}
        <div class="form-group">
            <label for="exampleInputEmail1" class="form-label">{{__('Email')}}</label>
            {{Form::text('email',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1" class="form-label">{{__('Password')}}</label>
            {{Form::password('password',array('class'=>'form-control','id'=>'exampleInputPassword1'))}}
        </div>
        <div class="form-group d-flex align-items-center text-left">
            <button type="submit" class="btn btn-secondary">{{__('Login')}}</button>
        </div>
        <div class="float-left">
            {{__('Don\'t have account ?')}}
            <a data-url="{{route('store.usercreate',$slug)}}" data-ajax-popup="true" data-title="Register" data-toggle="modal" class="login-form-main-a text-primary"><p>{{__('Register')}}</p></a>
        </div>
    {{-- </form> --}}
    {!! Form::close() !!}
</div>
