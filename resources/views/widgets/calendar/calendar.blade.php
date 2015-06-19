@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
	<h1>{{ $CalendarComposer['widget_data']['calendarlist']['calendar']['name'] or '' }} </h1>
@overwrite

@section('widget_body')
	<div class="row">
		<div class="col-md-12">

			<div id="calendar"></div>	
		</div>
	</div>
@overwrite	
