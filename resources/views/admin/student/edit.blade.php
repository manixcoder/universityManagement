@extends('admin.master')
@section('pageTitle','Edit Student')
@section('content')
@section('pageCss')

<style></style>
@stop
<div class="row">
	<div class="col-lg-12">
		<div class="card card-outline-info">
			<div class="card-header">
				<h4 class="m-b-0 text-white">Edit Student : {{ (isset($studentData) && !empty($studentData->name)) ? $studentData->name : '' }} </h4>
			</div>
			<div class="card-body">
				@if(Session::has('status'))
				<div class="alert alert-{{ Session::get('status') }}">
					<i class="ti-user"></i> {{ Session::get('message') }}
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
				</div>
				@endif
				<form class="edit-form" method="POST" action="{{ url('/admin/student-management/'.Crypt::encrypt($studentData->id).'/update') }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="form-body">
						<div class="row p-t-20">
							<div class="col-md-6">
								<div class="form-group  @error('firstName') has-danger @enderror">
									<label class="control-label">First Name</label>
									<input type="text" 
									class="form-control @error('firstName') form-control-danger @enderror " 
									id="firstName" 
									name="firstName" 
									placeholder="First Name" 
									value="{{ old('firstName',(isset($studentData) && !empty($studentData->firstName)) ? $studentData->firstName : '' ) }}" />
									@error('firstName')
									<small class="form-control-feedback">{{ $errors->first('firstName') }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group  @error('lastName') has-danger @enderror ">
									<label class="control-label">Last Name</label>
									<input 
									type="text" 
									class="form-control @error('lastName') form-control-danger @enderror" 
									id="lastName" 
									name="lastName" 
									placeholder="Last Name" 
									value="{{ old('lastName',(isset($studentData) && !empty($studentData->lastName)) ? $studentData->lastName : '' ) }}" 
									/>
									@error('lastName')
									<small class="form-control-feedback">{{ $errors->first('lastName') }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group  @error('name') has-danger @enderror ">
									<label class="control-label">User Name</label>
									<input 
									type="text" 
									class="form-control @error('name') form-control-danger @enderror" 
									id="name" 
									name="name" 
									value="{{ old('name',(isset($studentData) && !empty($studentData->name)) ? $studentData->name : '' ) }}" 
									
									/>
									@error('name')
									<small class="form-control-feedback">{{ $errors->first('name') }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group  @error('email') has-danger @enderror ">
									<label class="control-label">Email </label>
									<input 
									type="email" 
									class="form-control @error('email') form-control-danger @enderror" 
									id="email" 
									name="email" 
									value="{{ old('email',(isset($studentData) && !empty($studentData->email)) ? $studentData->email : '' ) }}" 
									
									/>
									@error('email')
									<small class="form-control-feedback">{{ $errors->first('email') }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group  @error('phone') has-danger @enderror ">
									<label class="control-label">Phone </label>
									<input 
									type="text" 
									class="form-control @error('phone') form-control-danger @enderror " 
									id="phone" 
									name="phone" 
									value="{{ old('phone',(isset($studentData) && !empty($studentData->phone)) ? $studentData->phone : '' ) }}" 
									/>
									@error('phone')
									<small class="form-control-feedback">{{ $errors->first('phone') }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
                                
                                <div class="form-group @error('university_id') has-danger @enderror">
                                    <label>University</label>
                                    <select class="form-control @error('university_id') form-control-danger @enderror" id="university_id"  name="university_id">
                                        <option value="">Select University</option>
                                        @foreach($university as $univ)
											<option value="{{$univ->id}}" {{ ( $univ->id == $studentData->university_id) ? 'selected' : '' }}>{{$univ->name}}</option>
                                        @endforeach
                                    </select>
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
