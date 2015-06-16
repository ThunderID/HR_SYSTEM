@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Jabatan </h1>
<small>Total data {{$widget_data['chart-pagination-'.$widget_data['identifier']]->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($widget_data['chart-'.$widget_data['identifier']]))
		<div class="clearfix">&nbsp;</div>
		@foreach($widget_data['chart-'.$widget_data['identifier']] as $key => $value)
			<div class="row">
				<div class="col-sm-6">
					@for($i=1;$i<count(explode(',',$value['path']));$i++)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@endfor 
					<i class="fa fa-chevron-circle-right"></i>&nbsp;&nbsp;<a href="{{route('hr.branch.charts.show', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $value['id']])}}" >{{$value['name']}} - {{$value['tag']}}</a>
				</div>
				<div class="text-right col-sm-6">
					<a href="" class="btn btn-default"><i class="fa fa-trash"></i></a>
					<a href="{{route('hr.branch.charts.edit', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
					<a href="" class="btn btn-default"><i class="fa fa-eye"></i></a>
				</div>
			</div>
			<div class="clearfix">&nbsp;</div>
		@endforeach

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$widget_data['chart-display-'.$widget_data['identifier']]['from']!!} - {!!$widget_data['chart-display-'.$widget_data['identifier']]['to']!!}</p>
				{!!$widget_data['chart-pagination-'.$widget_data['identifier']]->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite	
