@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {!! $widget_title or 'Jabatan' !!} </h1>
		<small>Total data {{$ChartComposer['widget_data']['chartlist']['chart-pagination']->total()}}</small>
		@if(isset($ChartComposer['widget_data']['chartlist']['active_filter']) && !is_null($ChartComposer['widget_data']['chartlist']['active_filter']))
			 <div class="clearfix">&nbsp;</div>
			@foreach($ChartComposer['widget_data']['chartlist']['active_filter'] as $key => $value)
				<span class="active-filter">{{$value}}</span>
			@endforeach
		@endif
	@overwrite

	@section('widget_body')
		<a href="{{ $ChartComposer['widget_data']['chartlist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@if(isset($ChartComposer['widget_data']['chartlist']['chart']))
			<div class="clearfix">&nbsp;</div>
			@foreach($ChartComposer['widget_data']['chartlist']['chart'] as $key => $value)
				<div class="row mb-10 pt-5 pb-5 chartlist">
					<div class="col-xs-6 col-sm-6 mt-10">
						@for($i=1;$i<count(explode(',',$value['path']));$i++)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@endfor 
						<i class="fa fa-chevron-circle-right"></i>&nbsp;&nbsp;<a href="javascript:;" >{{$value['name']}} - {{$value['tag']}}</a>
					</div>
					<div class="text-right col-xs-6 col-sm-6">
						<div class="btn-group">
							<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pengaturan <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								@if((int)Session::get('user.menuid') <= 2)
									<li>
										<a href="javascript:;" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.branch.charts.delete', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']]) }}"><i class="fa fa-trash fa-fw"></i> Hapus</a>
									</li>
								@endif
								@if((int)Session::get('user.menuid') <= 3)
									<li>
										<a href="{{route('hr.branch.charts.edit', [$value['id'], 'path' => $value['path'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])}}" title="ubah"><i class="fa fa-pencil fa-fw"></i> Ubah</a>
									</li>
								@endif
								<li>
									<a href="{{route('hr.branch.charts.show', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])}}" title="lihat data"><i class="fa fa-eye fa-fw"></i> Detail</a>
								</li>
								<li>
									<a href="{{ route('hr.chart.calendars.index', ['chart_id' => $value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']]) }}" title="lihat kalender"><i class="fa fa-calendar fa-fw"></i> Kalender</a>
								</li>
							</ul>
						</div>
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
		<script>
			
		</script>
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif