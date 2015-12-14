@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_title')
		<h1> {!! 'Dokumen '. $document['name'] .' "'.$person['name'].'"' !!} </h1>
	@overwrite

	@section('widget_body')
		@if(isset($template) && !is_null($template) && $template!='')
			{!!$template!!}
		@else
			<div class="clearfix">&nbsp;</div>
			@foreach($persondocument['details'] as $key => $value)
				<div class="row">
					<div class="col-sm-3">
						{!!ucwords($value['template']['field'])!!}
					</div>
					<div class="col-sm-9">
						@if($value['template']['type'] == 'date')
							{{date('d-m-Y', strtotime($value['on']))}}
						@else
							{{$value[$value['template']['type']]}}
						@endif
					</div>
				</div>
				<div class="clearfix">&nbsp;</div>
			@endforeach
			<div class="clearfix">&nbsp;</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif