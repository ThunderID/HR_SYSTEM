@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	{{ $LoginFormComposer['widget_data']['login']['widget_title'] }}
@overwrite

@section('widget_body')
	<br/>
	{!! Form::open(['url' => $LoginFormComposer['widget_data']['login']['form_url'], 'method' => 'post', 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">{{ $LoginFormComposer['widget_data']['login']['user_id_label'] }}</label>
			</div>					
			<div class="col-md-10">
				<input name="{{ $LoginFormComposer['widget_data']['login']['user_id'] }}" type="text" class="form-control" placeholder={{ $LoginFormComposer['widget_data']['login']['user_id_label'] }} autofocus autocomplete="off">
			</div>	
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Password</label>
			</div>
			<div class="col-md-10">
				<input name="password" type="password" class="form-control" placeholder="Password">
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group text-right">
				<input type="submit" class="btn btn-primary" value="Login">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	