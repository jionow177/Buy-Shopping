{{ Form::open(array('route' => array('store.language'))) }}
<div class="modal-body">
	<div class="form-group">
	    {{ Form::label('code', __('Language Code'),array('class' => 'col-form-label')) }}
	    {{ Form::text('code', '', array('class' => 'form-control','required'=>'required')) }}
	    @error('code')
	    <span class="invalid-code" role="alert">
	            <strong class="text-danger">{{ $message }}</strong>
	        </span>
	    @enderror
	</div>
    <div class="form-group">
	    {{ Form::label('full_name', __('Language Full Name'),array('class' => 'col-form-label')) }}
	    {{ Form::text('full_name', '', array('class' => 'form-control','required'=>'required')) }}
	    @error('full_name')
	    <span class="invalid-full_name" role="alert">
	            <strong class="text-danger">{{ $message }}</strong>
	        </span>
	    @enderror
	</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Create')}}</button>
</div>
{{ Form::close() }}

