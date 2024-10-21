    {{ Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PUT']) }}
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="form-group col-md-12">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {{ Form::text('name', $user->name, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('Email', __('Email'), ['class' => 'form-label']) }}
                {{ Form::email('email', $user->email, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required' => 'required']) }}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('User Role', __('User Role'), ['class' => 'form-label']) }}
                {{ Form::select('role', $roles, $user->roles, ['class' => 'form-control', 'placeholder' => __('Select Role'), 'required' => 'required']) }}
            </div>
            <div class="form-group col-12 d-flex justify-content-end col-form-label">
                <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light"
                    data-bs-dismiss="modal">
                <input type="submit" value="{{ __('Save') }}" class="btn btn-primary ms-2">
            </div>
        </div>
    </div>
    {!! Form::close() !!}
