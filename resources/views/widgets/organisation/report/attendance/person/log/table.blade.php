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
			<li><a href="{{route('hr.attendance.persons.show', array_merge(['id' => $PersonComposer['widget_data']['personlist']['person']['id'], 'print' => 'yes', 'mode' => 'csv'], Input::all()))}}">CSV</a></li>
			<li><a href="{{route('hr.attendance.persons.show', array_merge(['id' => $PersonComposer['widget_data']['personlist']['person']['id'], 'print' => 'yes', 'mode' => 'xls'], Input::all()))}}">XLS</a></li>
		</ul>
	</div>
	@overwrite

	@section('widget_body')
			@if(isset($PersonComposer['widget_data']['personlist']['person']['logs']))
				<table class="table">
					<thead>
						<tr>
							<th>No</th>
							<th>Waktu</th>
							<th>Aktivitas</th>
							<th>PC</th>
						</tr>
					</thead>
					@foreach($PersonComposer['widget_data']['personlist']['person']['logs'] as $key => $value)
						<tbody>
							<tr>
								<td>
									{{$key+1}}
								</td>
								<td>
									@date_indo($value['on'])
									@time_indo($value['on'])
								</td>
								<td>
									{{$value['name']}}
								</td>

								<td>
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