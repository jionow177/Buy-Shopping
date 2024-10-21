{{ Form::open(array('route' => array('coupon.import'),'method'=>'post', 'enctype' => "multipart/form-data")) }}
<div class="modal-body">
<div class="row">
    <div class="col-md-12 mb-4">
        {{Form::label('file',__('Download Sample Coupon CSV File'))}}
        <a href="{{asset(Storage::url('uploads/sample')).'/coupen_.csv'}}" class="btn btn-sm btn-primary btn-icon-only">
            <i class="fa fa-download"></i>
        </a>
    </div>
    <div class="col-md-12 mt-1">
        {{Form::label('file',__('Select CSV File'),['class'=>'col-form-label'])}}
        <div class="choose-file form-group">
            <label for="file" class="col-form-label">
                <input type="file" class="form-control" name="file" id="file" data-filename="upload_file" required>
            </label>
            <p class="upload_file"></p>
        </div>
    </div>
</div>
    <div class="form-group col-12 d-flex justify-content-end col-form-label">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Upload')}}" class="btn btn-primary ms-2">
    </div>
</div>
{{ Form::close() }}
