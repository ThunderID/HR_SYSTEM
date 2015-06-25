@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah Struktur Organisasi Cabang ' : 'Ubah Struktur Organisasi Cabang '). $branch['name']}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $ChartComposer['widget_data']['chartlist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Nama</label>				
				{!!Form::input('text', 'name', $ChartComposer['widget_data']['chartlist']['chart']['name'], ['class' => 'form-control', 'tabindex' => '1'])!!}				
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">				
						<label class="control-label">Bawahan Dari</label>				
						<select name="path" class="form-control select2" tabindex="2">
							<option></option>
							@foreach($ChartComposer['widget_data']['chartpath']['chart'] as $key => $value)
								<option value="{{$value['path']}}" @if($value['id']==$ChartComposer['widget_data']['chartlist']['chart']['chart_id']) selected @endif>{{$value['name']}} Departemen {{$value['tag']}}</option>
							@endforeach
						</select>								
					</div>
				</div>				
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Departemen</label>				
						{!!Form::input('text', 'tag', $ChartComposer['widget_data']['chartlist']['chart']['tag'], ['class' => 'form-control', 'tabindex' => '3'])!!}
					</div>
				</div>
			</div>
			<div class="form-group">				
				<label class="text-left">Grade</label>				
				{!!Form::input('text', 'grade', $ChartComposer['widget_data']['chartlist']['chart']['grade'], ['class' => 'form-control', 'tabindex' => '4'])!!}
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<label class="text-left">Jumlah Pegawai Minimum</label>
						{!!Form::input('number', 'min_employee', $ChartComposer['widget_data']['chartlist']['chart']['min_employee'], ['class' => 'form-control', 'min' => '0', 'tabindex' => '5'])!!}
					</div>	
				</div>
				<div class="col-sm-4">					
					<div class="form-group">
						<label class="text-left">Jumlah Pegawai Ideal</label>
						{!!Form::input('number', 'ideal_employee', $ChartComposer['widget_data']['chartlist']['chart']['ideal_employee'], ['class' => 'form-control', 'min' => '0', 'tabindex' => '6'])!!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label class="text-left">Jumlah Pegawai Maximum</label>
						{!!Form::input('number', 'max_employee', $ChartComposer['widget_data']['chartlist']['chart']['max_employee'], ['class' => 'form-control', 'min' => '0', 'tabindex' => '7'])!!}
					</div>
				</div>
			</div>			
			<div class="form-group text-right">				
				<a href="{{ $ChartComposer['widget_data']['chartlist']['route_back'] }}" class="btn btn-default mr-5" tabindex="9">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="8">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif