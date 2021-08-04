@extends('admin.master')
@section('pageTitle','Edit University')
@section('content')

@section('pageCss')
<style></style>
@stop

<div class="row">
	<div class="col-lg-12">
		<div class="card card-outline-info">
			<div class="card-header">
				<h4 class="m-b-0 text-white">Edit University : {{ (isset($universityData) && !empty($universityData->name)) ? $universityData->name : '' }} </h4>
			</div>
			<div class="card-body">
				@if(Session::has('status'))
				<div class="alert alert-{{ Session::get('status') }}">
					<i class="ti-user"></i> {{ Session::get('message') }}
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
				</div>
				@endif
				<form class="edit-form" method="POST" action="{{ url('/admin/university-management/'.Crypt::encrypt($universityData->id).'/update') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="form-body">
						<div class="row p-t-20">
							<div class="col-md-6">
								<div class="form-group @error('name') has-danger @enderror ">
									<label class="control-label">University Name</label>
									<input 
                                    type="text" 
                                    class="form-control @error('name') form-control-danger @enderror " 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name',(isset($universityData) && !empty($universityData->name)) ? $universityData->name : '' ) }}" 
                                    />
									@error('name')
									<small class="form-control-feedback">{{ $errors->first('name') }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group  @error('email') has-danger @enderror ">
									<label class="control-label">Company Email </label>
									<input 
                                    type="text" 
                                    class="form-control @error('email') form-control-danger @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email',(isset($universityData) && !empty($universityData->email)) ? $universityData->email : '' ) }}"
                                     />
									@error('email')
									<small class="form-control-feedback">{{ $errors->first('email') }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group  @error('phone') has-danger @enderror ">
									<label class="control-label">University website</label>
									<input 
                                    type="text"
                                    class="form-control @error('website') form-control-danger @enderror " 
                                    id="website" 
                                    name="website" 
                                    value="{{ old('website',(isset($universityData) && !empty($universityData->website)) ? $universityData->website : '' ) }}" 
                                    />
									@error('website')
									<small class="form-control-feedback">{{ $errors->first('website') }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
                                <div class="form-group  @error('logo') has-danger @enderror ">
                                    <label class="control-label">Logo</label>
                                    <input type="file" class="form-control @error('logo') form-control-danger @enderror " id="logo" name="logo" />
									<br>
									<span><img src="{{url('/public/uploads/university_logo/'.$universityData->logo) }}" style="width:80px;hight:80px;"></span>
                                    @error('logo')
                                    <small class="form-control-feedback">{{ $errors->first('logo') }}</small>
                                    @enderror
                                </div>
                            </div>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-info waves-effect waves-light  cus-submit save-btn"><i class="fa fa-upload" aria-hidden="true"></i> Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
@stop