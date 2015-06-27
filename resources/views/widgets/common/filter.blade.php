@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_body')
		{!! Form::open(['url' => $widget_options['form_url'], 'class' => 'form-horizontal pt-15 form_filter hide', 'method' => 'get']) !!}
			<div class="form-group">
				<div class="col-xs-11 colsm-11 col-md-11">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
						<input type="text" class="form-control" name="q">
					</div>
				</div>
				<div class="col-xs-1 col-sm-1 colcol-md-1">
					<a href="" class="btn btn-filter-add"><i class="fa fa-plus"></i></a>
				</div>
			</div>
			<input type="hidden" name="filter" value="apply">
			<?php $input = Input::all();?>
			<!-- @foreach($input as $key => $value)
				@if(!is_array($key) && !is_array($value))
					<input type="hidden" name="{{$key}}" value="{{$value}}">
				@endif
			@endforeach -->
			@foreach($default_filter as $key => $value)
				@if(!is_array($key) && !is_array($value))
					<input type="hidden" name="{{$key}}" value="{{$value}}">
				@endif
			@endforeach 
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-12">					
					<div class="btn-group">
						<select name="key[]" id="" class="form-control">
							@foreach($filter as $key => $value)
								<option value="{{ $value['prefix'].'_'.$value['key'] }}">{{ $value['value'] }}</option>
							@endforeach							
						</select>						
					</div>
					<div class="btn-group ml-10">
						<select name="value[]" id="" class="form-control">
							@foreach($filter as $key => $value)
								@foreach($value['values'] as $value2)
									<option value="{{ $value2['key'] }}">{{ $value2['value'] }}</option>
								@endforeach
							@endforeach
						</select>
					</div>
				</div>
			</div>
		{!! Form::close() !!}
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif