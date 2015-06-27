@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Template Dokumen' : 'Ubah Template Dokumen "'. $DocumentComposer['widget_data']['documentlist']['document']['name'].'"'}} </h1> 
	@overwrite

	@section('widget_body')	  
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $DocumentComposer['widget_data']['documentlist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Nama</label>				
				{!!Form::input('text', 'name', $DocumentComposer['widget_data']['documentlist']['document']['name'], ['class' => 'form-control'])!!}				
			</div>
			<div class="form-group">				
				<label class="control-label">Kategori</label>				
				{!!Form::input('text', 'tag', $DocumentComposer['widget_data']['documentlist']['document']['tag'], ['class' => 'form-control select2-tag-document'])!!}
			</div>
			<div class="form-group mb-30">										
				<div class="checkbox">
					<label for="">
						{!!Form::checkbox('is_required', '1', $DocumentComposer['widget_data']['documentlist']['document']['is_required'])!!} Wajib
					</label>	
				</div>								
			</div>

			@if ($DocumentComposer['widget_data']['documentlist']['document']['templates'])
				@foreach($DocumentComposer['widget_data']['documentlist']['document']['templates'] as $key => $value)
						<div class="row">
							<div class="col-sm-5">
								<div class="form-group">
									<label for="field[]" class="control-label">Nama Input</label>
									<input type="text" class="form-control" id="field[]" name="field[{{$key}}]" value="{{$value['field']}}">
								</div>
							</div>
							<div class="col-sm-5">
								<div class="form-group">
									<label for="" class="control-label">Tipe Input</label>
									<select id="Type" class="form-control form-control input-md" name="type[{{$key}}]">
										<option @if($value['type']=='numeric') selected @endif value="numeric">Angka</option>
										<option @if($value['type']=='date') selected @endif value="date">Tanggal</option>
										<option @if($value['type']=='string') selected @endif value="string">Teks Singkat</option>
										<option @if($value['type']=='text') selected @endif value="text">Teks Panjang</option>
									</select>
								</div>
								<input type="hidden" class="form-control" id="temp_id[]" name="temp_id[{{$key}}]" value="{{($value['id'] ? $value['id'] : null )}}">
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<a href="javascript:;" class="btn-delete-doc" style="color:#666;"><i class="fa fa-minus-circle fa-lg mt-30"></i></a>
								</div>
							</div>
						</div>					
				@endforeach
				<div id="template" class="template"></div>
			@else
				<div id="template" class="template">
					<div class="row">
						<div class="col-sm-5">
							<div class="form-group">
								<label for="field[]" class="control-label">Nama Input</label>
								<input type="text" class="form-control field" id="field[]" name="field[]">
							</div>
						</div>
						<div class="col-sm-5">
							<div class="form-group">
								<label for="" class="control-label">Tipe Input</label>
								<select id="Type" class="form-control form-control input-md type" name="type[]">
									<option value="numeric">Angka</option>
									<option value="date">Tanggal</option>
									<option value="string">Teks Singkat</option>
									<option value="text">Teks Panjang</option>
								</select>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">								
								<a href="javascript:;" class="btn-delete-doc" style="color:#666;"><i class="fa fa-minus-circle fa-lg mt-30"></i></a>
							</div>
						</div>
					</div>
				</div>
			@endif
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<a class="btn btn-default btn-add-doc" document-duplicate="docTemplate" document-target="#documentList">Tambah Inputan</a>
					</div>
				</div>
			</div>
			<div class="form-group mt-20">				
				<label class="control-label">Template</label>				
				{!!Form::textarea('template', $DocumentComposer['widget_data']['documentlist']['document']['template'], ['class' => 'form-control summernote-document'])!!}
				<span id="helpBlock" class="help-block font-12">*untuk mengganti nama dengan nama karyawan gunanakan //name// , untuk posisi gunakan //position//, untuk informasi lain per dokument gunakan //(nama_field_huruf_kecil)// *</span>
			</div>
			<div class="form-group text-right">				
				<a href="{{ $DocumentComposer['widget_data']['documentlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif