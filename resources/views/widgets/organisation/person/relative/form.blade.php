@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Data Kerabat ' : 'Ubah Data Kerabat '}} "{{$person['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		{!! Form::open(['url' => $RelativeComposer['widget_data']['relativelist']['form_url'], 'class' => 'form no-enter', 'files' => true]) !!}	
			<div class="clearfix">&nbsp;</div>
			<div class="row">
				<!-- <div class="col-sm-3"> -->
					<!-- <div class="box-profile-picture p-15">
						<input name="link_profile_picture" type="file" class="thumbnail_image_upload" data-img="{{ isset($RelativeComposer['widget_data']['relativelist']['relative']['relative']['avatar']) ? $RelativeComposer['widget_data']['relativelist']['relative']['relative']['avatar'] : '' }}" tabindex="1">
					</div> -->
				<!-- </div> -->
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="control-label">Hubungan</label>
								<select name="relationship" class="form-control" tabindex="1">
									<option value="parent" @if(strtolower($RelativeComposer['widget_data']['relativelist']['relative']['relationship'])=='parent') selected @endif>Orang Tua</option>
									<option value="spouse" @if(strtolower($RelativeComposer['widget_data']['relativelist']['relative']['relationship'])=='spouse') selected @endif>Pasangan (Menikah)</option>
									<option value="partner" @if(strtolower($RelativeComposer['widget_data']['relativelist']['relative']['relationship'])=='partner') selected @endif>Pasangan (Tidak Menikah)</option>
									<option value="child" @if(strtolower($RelativeComposer['widget_data']['relativelist']['relative']['relationship'])=='child') selected @endif>Anak</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="control-label">ID</label>
								{!!Form::input('text', 'uniqid', $RelativeComposer['widget_data']['relativelist']['relative']['relative']['uniqid'], ['class' => 'form-control', 'tabindex' => '2'])!!}							
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">Gelar Depan</label>				
								{!!Form::input('text', 'prefix_title', $RelativeComposer['widget_data']['relativelist']['relative']['relative']['prefix_title'], ['class' => 'form-control', 'tabindex' => '3'])!!}
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">Nama</label>
								{!!Form::input('text', 'name', $RelativeComposer['widget_data']['relativelist']['relative']['relative']['name'], ['class' => 'form-control', 'tabindex' => '4'])!!}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Gelar Akhir</label>
								{!!Form::input('text', 'suffix_title', $RelativeComposer['widget_data']['relativelist']['relative']['relative']['suffix_title'], ['class' => 'form-control', 'tabindex' => '5'])!!}
							</div>
						</div>
					</div>				
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">					
								<label class="control-label">Tempat Lahir</label>					
								{!!Form::input('text', 'place_of_birth', $RelativeComposer['widget_data']['relativelist']['relative']['relative']['place_of_birth'], ['class' => 'form-control', 'tabindex' => '6'])!!}					
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Tanggal Lahir</label>
								{!!Form::input('text', 'date_of_birth', $RelativeComposer['widget_data']['relativelist']['relative']['relative']['date_of_birth'], ['class' => 'form-control date-mask', 'tabindex' => '7'])!!}
							</div>	
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="mt-10">Jenis Kelamin</label>
							</div>
						</div>
						<div class="col-md-2 @if ($errors->first('gender')) has-error @endif">
							<div class="radio">
								<label>
									<input name="gender" type="radio" value="male" tabindex="8"
									@if (isset($RelativeComposer['widget_data']['relativelist']['relative']['relative']['gender']))
										@if ($RelativeComposer['widget_data']['relativelist']['relative']['relative']['gender'] == "male")
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
									<input name="gender" type="radio" value="female" tabindex="9"
									@if (isset($RelativeComposer['widget_data']['relativelist']['relative']['relative']['gender']))
										@if ($RelativeComposer['widget_data']['relativelist']['relative']['relative']['gender'] == "female")
											 checked="checked"
										@Endif
									@endif
									>Perempuan
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="duplicate_relative"></div>
			<div class="row">
				<div class="col-sm-12">
					<input type="hidden" name="relative_id" value="{{$RelativeComposer['widget_data']['relativelist']['relative']['relative']['id']}}">
					<div class="form-group">
						<div class="row mt-20">
							<div class="col-sm-12 col-md-12">
								<a href="javascript:;" class="btn btn-default btn_duplicate_add_relative" tabindex="10">Tambah Kerabat</a>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-12 text-right">
								<a href="{{ $RelativeComposer['widget_data']['relativelist']['route_back'] }}" class="btn btn-default mr-5" tabindex="16">Batal</a>
								<input type="submit" class="btn btn-primary" value="Simpan" tabindex="15">
							</div>
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