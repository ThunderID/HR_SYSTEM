@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Status Log ' : 'Ubah Status Log '}} "{{$person['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $ProcessLogComposer['widget_data']['processlogslist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Modifikasi Status</label>
				</div>	
				<div class="col-md-10">
					<select name="modified_status" class="form-control select2">
						<option value="DN">DN</option>
						<option value="SS">SS</option>
						<option value="SL">SL</option>
						<option value="CN">CN</option>
						<option value="CB">CB</option>
						<option value="CI">CI</option>
						<option value="UL">UL</option>
						<option value="AS">AS</option>
						<option value="HT">HT</option>
						<option value="HP">HP</option>
						<option value="HD">HD</option>
						<option value="HC">HC</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $ProcessLogComposer['widget_data']['processlogslist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
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