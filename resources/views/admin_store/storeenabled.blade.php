
{{Form::model($users, array('route' => array('store-resource.display', $users->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
@csrf
@method('put')
<div class="p-4">
	<p>This action can not be undone. Do you want to continue?</p>
	</div>
 <div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
    <button type="submit" class="btn  btn-primary" value="{{$users->store_display}}">{{__('Yes')}}</button>
</div>
{{Form::close()}}
