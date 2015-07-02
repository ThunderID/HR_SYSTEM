@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {{ is_null($id) ? 'Tambah Data Karyawan' : 'Ubah Data Karyawan "'. $PersonComposer['widget_data']['personlist']['person']['name'].'"'}} </h1> 
	@overwrite

	@section('widget_body')
		{!! Form::open(['url' => $PersonComposer['widget_data']['personlist']['form_url'], 'class' => 'form no_enter', 'files' => true]) !!}	
			<div class="clearfix">&nbsp;</div>
			<div class="row">
				<div class="col-sm-3">
					<div class="box-profile-picture p-15">
						<input name="link_profile_picture" type="file" class="thumbnail_image_upload" data-img="{{ isset($PersonComposer['widget_data']['personlist']['person']['avatar']) ? $PersonComposer['widget_data']['personlist']['person']['avatar'] : '' }}">
					</div>
				</div>
				<div class="col-sm-9">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="control-label">ID</label>
								{!!Form::input('text', 'uniqid', $PersonComposer['widget_data']['personlist']['person']['uniqid'], ['class' => 'form-control'])!!}							
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="control-label">Username</label>
								{!!Form::input('text', 'username', $PersonComposer['widget_data']['personlist']['person']['username'], ['class' => 'form-control'])!!}							
							</div>
						</div>
					</div>				
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label">Gelar Depan</label>				
								{!!Form::input('text', 'prefix_title', $PersonComposer['widget_data']['personlist']['person']['prefix_title'], ['class' => 'form-control'])!!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Nama</label>
								{!!Form::input('text', 'name', $PersonComposer['widget_data']['personlist']['person']['name'], ['class' => 'form-control'])!!}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label">Gelar Akhir</label>
								{!!Form::input('text', 'suffix_title', $PersonComposer['widget_data']['personlist']['person']['suffix_title'], ['class' => 'form-control'])!!}
							</div>
						</div>
					</div>				
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">					
								<label class="control-label">Tempat Lahir</label>					
								{!!Form::input('text', 'place_of_birth', $PersonComposer['widget_data']['personlist']['person']['place_of_birth'], ['class' => 'form-control'])!!}					
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tanggal Lahir</label>
								{!!Form::input('text', 'date_of_birth', isset($PersonComposer['widget_data']['personlist']['person']['date_of_birth']) ? date('d-m-Y', strtotime($PersonComposer['widget_data']['personlist']['person']['date_of_birth'])) : '', ['class' => 'form-control date-mask']) !!}
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
								<label class="mt-10">Jenis Kelamin</label>
							</div>
						</div>
						<div class="col-md-2 @if ($errors->first('gender')) has-error @endif">
							<div class="radio">
								<label>
									<input name="gender" type="radio" value="male"
									@if (isset($PersonComposer['widget_data']['personlist']['person']['gender']))
										@if ($PersonComposer['widget_data']['personlist']['person']['gender'] == "male")
											 checked="checked"
										@Endif
									@endif
									>Laki-laki
								</label>
							</div>
						</div>
						<div class="col-md-2  @if ($errors->first('gender')) has-error @endif">
							<div class="radio">
								<label>
									<input name="gender" type="radio" value="female"
									@if (isset($PersonComposer['widget_data']['personlist']['person']['gender']))
										@if ($PersonComposer['widget_data']['personlist']['person']['gender'] == "female")
											 checked="checked"
										@Endif
									@endif
									>Perempuan
								</label>
							</div>
						</div>
					</div>	
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="control-label">Password</label>
								{!!Form::input('password', 'password', null, ['class' => 'form-control date-mask']) !!}
							</div>	
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12 text-right">
							<a href="{{ $PersonComposer['widget_data']['personlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
							<input type="submit" class="btn btn-primary" value="Simpan">
						</div>
					</div>
				</div>
			</div>
			
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif