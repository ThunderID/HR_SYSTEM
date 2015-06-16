@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	{{ $widget_title or 'Cabang' }}
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $widget_options['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Nama</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'name', $widget_data['branch-'.$widget_data['identifier']]['name'], ['class' => 'form-control', 'placeholder' => 'Nama'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<input type="button" class="btn btn-default mr-5" value="Batal">
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	