@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Akivitas' !!} </h1>
	<small>Total data {{ count($PersonComposer['widget_data']['personlist']['person']['logs']) }}</small>

	<div class="btn-group pull-right">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="fa fa-file"></i> Export to <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="{{route('hr.activity.logs.index', array_merge(['person_id' => $PersonComposer['widget_data']['personlist']['person']['id'], 'print' => 'yes', 'mode' => 'csv'], Input::all()))}}">CSV</a></li>
			<li><a href="{{route('hr.activity.logs.index', array_merge(['person_id' => $PersonComposer['widget_data']['personlist']['person']['id'], 'print' => 'yes', 'mode' => 'xls'], Input::all()))}}">XLS</a></li>
		</ul>
	</div>
	@overwrite

	@section('widget_body')
		@if(isset($PersonComposer['widget_data']['personlist']['person']['logs']))
			<table class="table table-hover report table-affix">
				<thead>
					<tr>
						<th class="text-center font-11" style="width:6%">No</th>
						<th class="text-center font-11" style="width:28%">Waktu</th>
						<th class="text-center font-11" style="width:28%">Aktivitas Terakhir</th>
						<th class="text-center font-11" style="width:32%">Aktivitas</th>
						<th class="text-center font-11" style="width:40%">PC</th>
					</tr>
				</thead>
				@foreach($PersonComposer['widget_data']['personlist']['person']['logs'] as $key => $value)
					<tbody>
						<tr>
							<td class="text-center font-11">
								{{$key+1}}
							</td>
							<td class="text-center font-11">
								{{ date('d-m-Y H:i:s', strtotime($value['on'])) }}
							</td>
							<td class="text-center font-11">
								{{ date('d-m-Y H:i:s', strtotime($value['last_input_time'])) }}
							</td>
							<td class="text-center font-11">
								{{$value['name']}}
							</td>

							<td class="hidden-xs text-center font-11">
								{{$value['pc']}}
							</td>
						</tr>
					</tbody>
				@endforeach
			</table>

			<div class="clearfix">&nbsp;</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif