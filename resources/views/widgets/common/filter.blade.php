@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_body')
		{!! Form::open(['url' => $widget_options['form_url'], 'class' => 'form-horizontal pt-15 form_filter hide', 'method' => 'get']) !!}
			<div class="form-group">
				<div class="col-xs-11 col-sm-11 col-md-11">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
						<input type="text" class="form-control" name="q">
					</div>
				</div>
				<div class="col-xs-1 col-sm-1 col-md-1">
					<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
					{{-- <a href="javascript:;" class="btn btn-filter-add"><i class="fa fa-search"></i></a> --}}
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
					<span class="filter-add">
						<div class="btn-group">
							<select name="key[]" id="" class="form-control filter-key">
								@foreach($filter as $key => $value)
									<option value="{{ $value['prefix'].'_'.$value['key'] }}">{{ $value['value'] }}</option>
								@endforeach							
							</select>						
						</div>
						<div class="btn-group ml-10">
							<select name="value[]" id="" class="form-control filter-value">
								@foreach($filter as $key => $value)
									@foreach($value['values'] as $value2)
										<option value="{{ $value2['key'] }}">{{ $value2['value'] }}</option>
									@endforeach
								@endforeach
							</select>
						</div>
					</span>
					{{-- <a href="javascript:;" class="btn btn-add-filter ml-10"><i class="fa fa-plus"></i></a> --}}
				</div>
			</div>
		{!! Form::close() !!}
		<script>
			var x = {!! json_encode($filter)!!};
		</script>
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif