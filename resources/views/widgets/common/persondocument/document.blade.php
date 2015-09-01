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
						@if(!is_null($value['on']) && $value['on']!='0000-00-00 00:00:00')
							{{date('d-m-Y', strtotime($value['on']))}}
						@elseif($value['numeric']!=0)
							{{$value['numeric']}}
						@elseif(!is_null($value['string']) && $value['string']!='')
							{{$value['string']}}
						@else
							{{ucwords($value['text'])}}
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