@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	<?php
		$WorkComposer['widget_data']['worklist']['work-pagination']->setPath(route('hr.person.works.index'));
	?>

	@section('widget_title')
		<h1> {!! $widget_title or 'Karir' !!} </h1>
		<small>Total data {{$WorkComposer['widget_data']['worklist']['work-pagination']->total()}}</small>

		@if(isset($WorkComposer['widget_data']['worklist']['active_filter']) && !is_null($WorkComposer['widget_data']['worklist']['active_filter']))
			 <div class="clearfix">&nbsp;</div>
			@foreach($WorkComposer['widget_data']['worklist']['active_filter'] as $key => $value)
				<span class="active-filter">{{$value}}</span>
			@endforeach
		@endif
	@overwrite

	@section('widget_body')
		<a href="{{ $WorkComposer['widget_data']['worklist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@if(isset($WorkComposer['widget_data']['worklist']['work']))
			<div class="clearfix">&nbsp;</div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr class="row">
							<th class="col-sm-1">No</th>
							<th class="col-sm-3">Perusahaan</th>
							<th class="col-sm-3">Jabatan (Status)</th>
							<th class="col-sm-3" colspan="2">Lama Bekerja</th>
							<th class="col-sm-2">&nbsp;</th>
						</tr>
					</thead>
					<?php $i = $WorkComposer['widget_data']['worklist']['work-display']['from'];?>
					<tbody>
						@foreach($WorkComposer['widget_data']['worklist']['work'] as $key => $value)
							<tr class="row">
								<td class="col-sm-1">
									{{$i}}
								</td>
								@if($value['chart'])
									<td class="col-sm-3">
										{{$value['chart']['branch']['organisation']['name']}} Cabang {{$value['chart']['branch']['name']}}
									</td>
									<td class="col-sm-3">
										{{$value['chart']['name']}} Departemen {{$value['chart']['tag']}} ({{$value['status']}})
									</td>
								@else
									<td class="col-sm-3">
										{{$value['organisation']}}
									</td>
									<td class="col-sm-3">
										{{$value['position']}} ({{$value['status']}})
									</td>
								@endif
								<td class="col-sm-3" colspan="2">
									@date_indo($value['start']) - 
									@if(is_null($value['end']))
										Sekarang
									@else
										@date_indo($value['end'])
									@endif
								</td>
								<td class="text-right col-sm-2">
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.person.works.delete', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']]) }}"><i class="fa fa-trash"></i></a>
									<a @if($value['chart']) href="{{route('hr.person.works.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id'], 'prev' => false])}}" @else href="{{route('hr.person.works.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id'], 'prev' => true])}}" @endif class="btn btn-default"><i class="fa fa-pencil"></i></a>
									<!-- <a href="{{route('hr.person.works.show', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a> -->
								</td>
							</tr>
							<?php $i++;?>
						@endforeach
					</tbody>
				</table>
			</div>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$WorkComposer['widget_data']['worklist']['work-display']['from']!!} - {!!$WorkComposer['widget_data']['worklist']['work-display']['to']!!}</p>
					{!!$WorkComposer['widget_data']['worklist']['work-pagination']->appends(Input::all())->render()!!}
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
