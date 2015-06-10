@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
	HR System Login
@overwrite

@section('widget_body')
	<form action="" class="form-horizontal">
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Email</label>
			</div>					
			<div class="col-md-10">
				<input type="text" class="form-control" placeholder="email">
			</div>	
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Password</label>
			</div>
			<div class="col-md-10">
				<input type="text" class="form-control" placeholder="password">
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group text-right">
				<input type="submit" class="btn btn-primary" value="Login">
			</div>
		</div>
	</form>
@overwrite	