@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	{{ $widget_title or 'Tambah Cabang' }}
@overwrite

@section('widget_body')
	<div class="panel panel-default mt-15">
		<div class="panel-body">
			{!! Form::open(['url' => $widget_options['form_url'], 'class' => 'form-horizontal']) !!}	
				<div class="form-group">
					<div class="col-md-2">
						<label class="control-label">Nama Kantor Cabang</label>
					</div>	
					<div class="col-md-10">
						{!!Form::input('text', 'name', '', ['class' => 'form-control', 'placeholder' => 'Nama Kantor Cabang'])!!}
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-12 text-right">
						<input type="button" class="btn btn-default mr-5" value="Batal">
						<input type="submit" class="btn btn-primary" value="Simpan">
					</div>
				</div>
			{!! Form::close() !!}
		</div>
	</div>
@overwrite	