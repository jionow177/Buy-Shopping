<div class="modal-body">
    <div class="product-view-body">
        {{-- <form method="POST" action="https://demo.workdo.io/whatsstore-saas/my-whatsstore/customer-login" accept-charset="UTF-8" class="login-form-main"><input name="_token" type="hidden" value="CTe9lqYafL8ynU4ctpHU3BXOQPqLx4bIFOvADMFt"> --}}
        {!! Form::open(['route' => ['store.userstore', $slug], 'class' => 'login-form-main'], ['method' => 'post']) !!}
        <div class="form-group">
            <label for="exampleInputEmail1" class="form-label">{{ __('Full Name') }}</label>
            <input class="form-control" name="name" type="text" required="required" placeholder="Enter Name">
        </div>
        @error('name')
            <span class="error invalid-email text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <div class="form-group">
            <label for="exampleInputEmail1" class="form-label">{{ __('Email') }}</label>
            <input class="form-control" name="email" type="text" required="required" placeholder="Enter Email">
        </div>
        @error('email')
            <span class="error invalid-email text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <div class="form-group">
            <label for="exampleInputEmail1" class="form-label">{{ __('Number') }}</label>
            <input class="form-control" name="phone_number" type="text" required="required"
                placeholder="Enter Number">
        </div>
        @error('number')
            <span class="error invalid-email text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <div class="form-group">
            <label for="exampleInputEmail1" class="form-label">{{ __('Password') }}</label>
            <input class="form-control" name="password" type="password" required="required"
                placeholder="Enter Password">
        </div>
        @error('password')
            <span class="error invalid-email text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <div class="form-group">
            <label for="exampleInputEmail1" class="form-label">{{ __('Confirm Password') }}</label>
            <input class="form-control" name="password_confirmation" type="password" required="required"
                placeholder="Enter Confirm Password">
        </div>
        @error('password_confirmation')
            <span class="error invalid-email text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <div class="row">
            <div class="float-left col-6">
                {{ __('Already registered ?') }}
                <a data-url="{{ route('customer.loginform', $slug) }}" data-ajax-popup="true"
                    data-title="{{ __('Login') }}" data-toggle="modal"
                    class="text-primary pb-4">{{ __('Login') }}</a>
            </div>
            <div class="form-group align-items-center text-right col-6">
                <button type="submit" class="btn btn-secondary">{{ __('Register') }}</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
