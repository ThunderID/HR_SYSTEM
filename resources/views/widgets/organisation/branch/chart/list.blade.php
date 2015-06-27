@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ $widget_title or 'Jabatan' }} </h1>
		<small>Total data {{$ChartComposer['widget_data']['chartlist']['chart-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		<a href="{{ $ChartComposer['widget_data']['chartlist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@if(isset($ChartComposer['widget_data']['chartlist']['chart']))
			<div class="clearfix">&nbsp;</div>
			@foreach($ChartComposer['widget_data']['chartlist']['chart'] as $key => $value)
				<div class="row mb-10">
					<div class="col-xs-6 col-sm-6">
						@for($i=1;$i<count(explode(',',$value['path']));$i++)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@endfor 
						<i class="fa fa-chevron-circle-right"></i>&nbsp;&nbsp;<a href="javascript:;" >{{$value['name']}} - {{$value['tag']}}</a>
					</div>
					<div class="text-right col-xs-6 col-sm-6">
						<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.branch.charts.delete', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']]) }}"><i class="fa fa-trash"></i></a>
						<a href="{{route('hr.branch.charts.edit', [$value['id'], 'path' => $value['path'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])}}" class="btn btn-default" title="ubah"><i class="fa fa-pencil"></i></a>
						<a href="{{route('hr.branch.charts.show', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])}}" class="btn btn-default" title="lihat data"><i class="fa fa-eye"></i></a>
						<a href="{{route('hr.chart.authentications.index', ['chart_id' => $value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])}}" class="btn btn-default" title="lihat otentikasi"><i class="fa fa-lock"></i></a>
						<a href="{{ route('hr.chart.calendars.index', ['chart_id' => $value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']]) }}" class="btn btn-default" title="lihat kalender"><i class="fa fa-calendar"></i></a>
					</div>
				</div>
			@endforeach

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$ChartComposer['widget_data']['chartlist']['chart-display']['from']!!} - {!!$ChartComposer['widget_data']['chartlist']['chart-display']['to']!!}</p>
					{!!$ChartComposer['widget_data']['chartlist']['chart-pagination']->appends(Input::all())->render()!!}
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