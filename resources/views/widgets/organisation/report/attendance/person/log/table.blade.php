@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Laporan Akivitas' !!} </h1>
	<small>Total data {{ count($PersonComposer['widget_data']['personlist']['person']['logs']) }}</small>
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