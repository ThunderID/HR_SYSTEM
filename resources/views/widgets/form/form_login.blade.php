@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	{{ $LoginFormComposer['widget_data']['login']['widget_title'] }}
@overwrite


@section('widget_body')
	@if($errors->count())
		@include('widgets.common.alert', [
			'widget_template'	=> 'plain_no_title',
			'errors' => $errors
		])
	@else
		<br/>
	@endif
	{!! Form::open(['url' => $LoginFormComposer['widget_data']['login']['form_url'], 'method' => 'post', 'class' => 'form']) !!}	
		<div class="form-group">
			<label class="control-label">{{ $LoginFormComposer['widget_data']['login']['user_id_label'] }}</label>
			<input name="{{ $LoginFormComposer['widget_data']['login']['user_id'] }}" type="text" class="form-control" placeholder={{ $LoginFormComposer['widget_data']['login']['user_id_label'] }} autofocus autocomplete="off" autocapitalize="off" autocorrect="off">
		</div>
		<div class="form-group">
			<label class="control-label">Password</label>
			<input name="password" type="password" class="form-control" placeholder="Password">			
		</div>
		<div class="form-group text-right">
			<input type="submit" class="btn btn-primary" value="Login">
		</div>
		
	{!! Form::close() !!}
@overwrite	