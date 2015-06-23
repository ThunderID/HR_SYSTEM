@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
	<h1>{{ $CalendarComposer['widget_data']['calendarlist']['calendar']['name'] or '' }} </h1>
@overwrite

@section('widget_body')
	<div class="row">
		<div class="col-md-12">
			<h4 class="title-calendar font-30 mb-30"></h4>
		<a href="javascript" class="btn btn-primary" data-toggle="modal" data-target="#modal_schedule_branch" data-id="0" data-add-action="{{ route('hr.calendar.schedules.create', ['org_id' => $data['id'], 'cal_id' => $CalendarComposer['widget_data']['calendarlist']['calendar']['id']]) }}">Tambah Schedule</a>
			<div id="calendar"></div>	
		</div>
	</div>
@overwrite	
