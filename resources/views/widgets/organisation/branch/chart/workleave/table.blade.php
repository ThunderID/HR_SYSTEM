@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	<?php
		$ChartWorkleaveComposer['widget_data']['workleavelist']['workleave-pagination']->setPath(route('hr.chart.workleaves.index'));
	?>

	@section('widget_title')
	<h1> {!! $widget_title or 'Workleave Kerja' !!} </h1>
	<small>Total data {{$ChartWorkleaveComposer['widget_data']['workleavelist']['workleave-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		<a href="{{route('hr.chart.workleaves.create', ['chart_id' => $chart['id'], 'branch_id' => $branch['id'], 'org_id' => $data['id']])}}" class="btn btn-primary">Tambah</a>
		@if(isset($ChartWorkleaveComposer['widget_data']['workleavelist']['workleave']))
			<div class="clearfix">&nbsp;</div>
			<table class="table table-hover table-affix">
				<thead>
					<tr>
						<th>No</th>
						<th>Workleave</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = $ChartWorkleaveComposer['widget_data']['workleavelist']['workleave-display']['from'];?>
					@forelse($ChartWorkleaveComposer['widget_data']['workleavelist']['workleave'] as $key => $value)
						<tr>
							<td>{{$i}}</td>
							<td>
								{{$value['workleave']['name']}}
							</td>
							<td class="text-right">
								<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.chart.workleaves.delete', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id'], 'chart_id' => $chart['id']]) }}"><i class="fa fa-trash"></i></a>
							</td>
						</tr>
						<?php $i++;?>
					@empty 
						<tr>
							<td class="text-center" colspan="2">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
			</table>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$ChartWorkleaveComposer['widget_data']['workleavelist']['workleave-display']['from']!!} - {!!$ChartWorkleaveComposer['widget_data']['workleavelist']['workleave-display']['to']!!}</p>
					{!!$ChartWorkleaveComposer['widget_data']['workleavelist']['workleave-pagination']->appends(Input::all())->render()!!}
				</div>
			</div>

			<div class="clearfix">&nbsp;</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif