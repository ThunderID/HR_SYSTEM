@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	{{ $widget_options['login']['widget_title'] }}
@overwrite

@section('widget_body')
	<br/>
	{!! Form::open(['url' => $widget_options['login']['form_url'], 'method' => 'post', 'class' => 'form']) !!}	
		<div class="form-group">
			<label class="control-label">Password Lama</label>
			<input name="old_password" type="password" class="form-control" placeholder="Password Lama" tabindex="1">			
		</div>
		<div class="form-group">
			<label class="control-label">Password Baru</label>
			<input name="password" type="password" class="form-control" placeholder="Password" tabindex="2">			
		</div>
		<div class="form-group">
			<label class="control-label">Konfirmasi Password Baru</label>
			<input name="password_confirmation" type="password" class="form-control" placeholder="Konfirmasi Password Baru" tabindex="3">			
		</div>
		<div class="form-group text-right">
			<input type="submit" class="btn btn-primary" value="Simpan" tabindex="4">
		</div>
		
	{!! Form::close() !!}
@overwrite	