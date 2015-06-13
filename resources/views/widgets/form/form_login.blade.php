@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	{{ $widget_data['widget_title'] }}
@overwrite

@section('widget_body')
	<br/>
	{{$widget_data['cemcem']}}
	{!! Form::open(['url' => $widget_data['form_url'], 'method' => 'post', 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">{{ $widget_data['user_id_label'] }}</label>
			</div>					
			<div class="col-md-10">
				<input name="{{ $widget_data['user_id'] }}" type="text" class="form-control" placeholder={{ $widget_data['user_id_label'] }} autofocus autocomplete="off">
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