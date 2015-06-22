@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah Struktur Organisasi Cabang ' : 'Ubah Struktur Organisasi Cabang '). $branch['name']}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $ChartComposer['widget_data']['chartlist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Nama</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'name', $ChartComposer['widget_data']['chartlist']['chart']['name'], ['class' => 'form-control'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Bawahan Dari</label>
				</div>
				<div class="col-md-4">
					<select name="path">
							<option></option>
						@foreach($ChartComposer['widget_data']['chartpath']['chart'] as $key => $value)
							<option value="{{$value['path']}}" @if($value['id']==$ChartComposer['widget_data']['chartlist']['chart']['chart_id']) selected @endif>{{$value['name']}} Departemen {{$value['tag']}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label class="control-label">Departemen</label>
				</div>	
				<div class="col-md-4">
					{!!Form::input('text', 'tag', $ChartComposer['widget_data']['chartlist']['chart']['tag'], ['class' => 'form-control'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="text-left">Grade</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'grade', $ChartComposer['widget_data']['chartlist']['chart']['grade'], ['class' => 'form-control'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="text-left">Jumlah Pegawai Minimum</label>
				</div>	
				<div class="col-md-2">
					{!!Form::input('text', 'min_employee', $ChartComposer['widget_data']['chartlist']['chart']['min_employee'], ['class' => 'form-control'])!!}
				</div>
				<div class="col-md-2">
					<label class="text-left">Jumlah Pegawai Ideal</label>
				</div>	
				<div class="col-md-2">
					{!!Form::input('text', 'ideal_employee', $ChartComposer['widget_data']['chartlist']['chart']['ideal_employee'], ['class' => 'form-control'])!!}
				</div>
				<div class="col-md-2">
					<label class="text-left">Jumlah Pegawai Maximum</label>
				</div>	
				<div class="col-md-2">
					{!!Form::input('text', 'max_employee', $ChartComposer['widget_data']['chartlist']['chart']['max_employee'], ['class' => 'form-control'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $ChartComposer['widget_data']['chartlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
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