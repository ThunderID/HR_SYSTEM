@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	{{ $widget_options['login']['widget_title'] }}
@overwrite

@section('widget_body')
	<br/>
	{!! Form::open(['url' => $widget_options['login']['form_url'], 'method' => 'post', 'class' => 'form']) !!}	
		<div class="form-group">
			<label class="control-label">Password Lama</label>
			<input name="old_password" type="password" class="form-control" placeholder="Password Lama">			
		</div>
		<div class="form-group">
			<label class="control-label">Password Baru</label>
			<input name="password" type="password" class="form-control" placeholder="Password">			
		</div>
		<div class="form-group">
			<label class="control-label">Konfirmasi Password Baru</label>
			<input name="password_confirmation" type="password" class="form-control" placeholder="Konfirmasi Password Baru">			
		</div>
		<div class="form-group text-right">
			<input type="submit" class="btn btn-primary" value="Login">
		</div>
		
	{!! Form::close() !!}
@overwrite	