@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
	<h1>{{ $CalendarComposer['widget_data']['calendarlist']['calendar']['name'] or '' }} </h1>
@overwrite

@section('widget_body')
	<div class="row">
		<div class="col-md-12">
			<h4 class="title-calendar font-30 mb-30"></h4>
		<a href="javascript" class="btn btn-primary" data-toggle="modal" data-target="#modal_schedule_branch" data-id="0" data-add-action="{{ route('hr.calendar.schedules.create', ['org_id' => $data['id'], 'cal_id' => $CalendarComposer['widget_data']['calendarlist']['calendar']['id']]) }}">Tambah Schedule</a>
			<div class="sk-spinner sk-spinner-fading-circle spinner-loading-schedule">
				<div class="sk-circle1 sk-circle"></div>
				<div class="sk-circle2 sk-circle"></div>
				<div class="sk-circle3 sk-circle"></div>
				<div class="sk-circle4 sk-circle"></div>
				<div class="sk-circle5 sk-circle"></div>
				<div class="sk-circle6 sk-circle"></div>
				<div class="sk-circle7 sk-circle"></div>
				<div class="sk-circle8 sk-circle"></div>
				<div class="sk-circle9 sk-circle"></div>
				<div class="sk-circle10 sk-circle"></div>
				<div class="sk-circle11 sk-circle"></div>
				<div class="sk-circle12 sk-circle"></div>
			</div>
			<div id="calendar"></div>	
		</div>
	</div>
@overwrite	
