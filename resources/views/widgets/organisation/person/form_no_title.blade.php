<div class="clearfix">&nbsp;</div>
<div class="row">
	<div class="col-sm-3">
		<div class="box-profile-picture p-15">
			<div class="fileinput fileinput-new" data-provides="fileinput">
				<div class="fileinput-preview thumbnail" data-trigger="fileinput">
					@if (!$PersonComposer['widget_data']['personlist']['person']['avatar'])
						{!! HTML::image('/upload_foto.png') !!}
					@else
						{!! HTML::image($PersonComposer['widget_data']['personlist']['person']['avatar']) !!}
					@endif
				</div>
				<div>
					<input type="file" name="link_profile_picture" class="hide">
				</div>
			</div>
			<!-- <input name="link_profile_picture" type="file" class="thumbnail_image_upload" data-img="{{ isset($PersonComposer['widget_data']['personlist']['person']['avatar']) ? $PersonComposer['widget_data']['personlist']['person']['avatar'] : '' }}"> -->
		</div>
	</div>
	<div class="col-sm-9">
		<div class="row">
			<div class="col-sm-9">
				<div class="form-group">
					<label class="control-label">N I K</label>
					{!! Form::input('text', 'uniqid', $PersonComposer['widget_data']['personlist']['person']['uniqid'], ['class' => 'form-control form_person_nik', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<div class="checkbox">
						<label class="mt-20">
							{!! Form::checkbox('is_automatic_nik', '1', '', ['class' => 'checkbox_person_automatic_nik checkbox-generate']) !!} <span class="label-automatic-generate">Otomatis Generate</span>
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">Username</label>
					{!!Form::input('text', 'username', $PersonComposer['widget_data']['personlist']['person']['username'], ['class' => 'form-control', 'required' => 'required'])!!}							
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
					{!!Form::input('text', 'date_of_birth', null, ['class' => 'form-control date-mask']) !!}
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
					<label class="control-label">Status Kawin</label>
					@if($id && isset($PersonComposer['widget_data']['personlist']['person']['maritalstatuses'][0]))
						@include('widgets.organisation.person.select_married', ['currentstatus' => $PersonComposer['widget_data']['personlist']['person']['maritalstatuses'][0]['status']])
						{!!Form::hidden('maritalold', $PersonComposer['widget_data']['personlist']['person']['maritalstatuses'][0]['status']) !!}
					@else
						@include('widgets.organisation.person.select_married', ['currentstatus' => null])
					@endif
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">Password</label>
					{!!Form::input('password', 'password', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
					<span id="helpBlock" class="help-block font-12">* Biarkan kosong jika tidak ingin mengubah password </span>
				</div>	
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">Konfirmasi Password</label>
					{!!Form::input('password', 'confirmed_password', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
				</div>	
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12 text-right">
		<button class="btn btn-primary nextBtn pull-right" type="button" >Lanjut</button>
    	<a href="{{ $PersonComposer['widget_data']['personlist']['route_back'] }}" class="btn btn-default pull-right mr-10">Batal</a>
	</div>
</div>