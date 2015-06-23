@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Template Dokumen' : 'Ubah Template Dokumen '. $DocumentComposer['widget_data']['documentlist']['document']['name']}} </h1> 
	@overwrite

	@section('widget_body')	  
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $DocumentComposer['widget_data']['documentlist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Nama</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'name', $DocumentComposer['widget_data']['documentlist']['document']['name'], ['class' => 'form-control'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Kategori</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'tag', $DocumentComposer['widget_data']['documentlist']['document']['tag'], ['class' => 'form-control'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Wajib</label>
				</div>	
				<div class="col-md-10">
					<div class="checkbox">
						<label for="">
							{!!Form::checkbox('is_required', '1', $DocumentComposer['widget_data']['documentlist']['document']['is_required'], ['class' => ''])!!}
						</label>	
					</div>				
				</div>
			</div>

			@if ($DocumentComposer['widget_data']['documentlist']['document']['templates'])
				@foreach($DocumentComposer['widget_data']['documentlist']['document']['templates'] as $key => $value)
					<div class="form-group">
						<div class="col-md-2">&nbsp;</div>
						<div class="col-md-2">					
							<label for="field[]" class="control-label">Nama Input</label>
						</div>
						<div class="col-md-2">
							<input type="text" class="form-control" id="field[]" name="field[{{$key}}]" value="{{$value['field']}}">
						</div>
						<div class="col-md-2">
							<label for="" class="control-label">Tipe Input</label>
						</div>
						<div class="col-md-2">
							<select id="Type" class="form-control form-control input-md" name="type[{{$key}}]">
								<option @if($value['type']=='numeric') selected @endif value="numeric">Angka</option>
								<option @if($value['type']=='date') selected @endif value="date">Tanggal</option>
								<option @if($value['type']=='string') selected @endif value="string">Teks Singkat</option>
								<option @if($value['type']=='text') selected @endif value="text">Teks Panjang</option>
							</select>
							<input type="hidden" class="form-control" id="temp_id[]" name="temp_id[{{$key}}]" value="{{($value['id'] ? $value['id'] : null )}}">
						</div>
						<div class="col-md-2">
							<a href="javascript:;" class="btn-delete-doc" style="color:#666;"><i class="fa fa-minus-circle fa-lg mt-10"></i></a>
						</div>
					</div>
				@endforeach
			@else
				<ul class="list-unstyled" id="documentList"></ul>
				<div class="form-group">
					<div class="col-md-2">&nbsp;</div>
					<div class="col-md-10">
						<a class="btn btn-default btn-add-doc" document-duplicate="docTemplate" document-target="#documentList">Tambah Inputan</a>
					</div>
				</div>
			@endif
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Template</label>
				</div>	
				<div class="col-md-10">
					{!!Form::textarea('template', $DocumentComposer['widget_data']['documentlist']['document']['template'], ['class' => 'form-control summernote-document'])!!}
					<span id="helpBlock" class="help-block font-12">*untuk mengganti nama dengan nama karyawan gunanakan //name// , untuk posisi gunakan //position//, untuk informasi lain per dokument gunakan //(nama_field_huruf_kecil)// *</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $DocumentComposer['widget_data']['documentlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
					<input type="submit" class="btn btn-primary" value="Simpan">
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