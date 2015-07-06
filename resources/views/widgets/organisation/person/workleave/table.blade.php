@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))
@if (!$widget_error_count)
	@section('widget_title')
	<h1> {{ $widget_title or 'Data Karyawan'}} </h1>
	<small>Total data {{$PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-pagination']->total()}}</small>

	@if(isset($PersonWorkleaveComposer['widget_data']['workleavelist']['active_filter']) && !is_null($PersonWorkleaveComposer['widget_data']['workleavelist']['active_filter']))
		 <div class="clearfix">&nbsp;</div>
		@foreach($PersonWorkleaveComposer['widget_data']['workleavelist']['active_filter'] as $key => $value)
			<span class="active-filter">{{$value}}</span>
		@endforeach
	@endif
	@overwrite

	@section('widget_body')
		<a href="{{ $PersonWorkleaveComposer['widget_data']['workleavelist']['route_create'] }}" class="btn btn-primary">Tambah</a>
		@if(isset($PersonWorkleaveComposer['widget_data']['workleavelist']['workleave']))
			<div class="clearfix">&nbsp;</div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr class="row">
							<th class="col-sm-1">No</th>
							<th class="col-sm-2">Cuti</th>
							<th class="col-sm-1">Quota</th>
							<th class="col-sm-2">Status</th>
							<th class="col-sm-2">Diberikan Oleh</th>
							<th class="col-sm-2">Masa Berlaku</th>
							<th class="col-sm-2">&nbsp;</th>
						</tr>
					</thead>
					<?php $i = $PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-display']['from'];?>
					@foreach($PersonWorkleaveComposer['widget_data']['workleavelist']['workleave'] as $key => $value)
						<tbody>
							<tr class="row">
								<td class="col-sm-1">
									{{$i}}
								</td>
								<td class="col-sm-2">
									{{$value['name']}}
								</td>
								<td class="col-sm-1">
									{{$value['quota']}}
								</td>
								<td class="col-sm-2">
									{{$value['status']}}
								</td>
								<td class="col-sm-2">
									{{$value['createdby']['name']}}
								</td>
								<td class="col-sm-2">
									@date_indo($value['start']) - 
									@date_indo($value['end'])
								</td>
								<td class="text-right col-sm-2">
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.person.workleaves.delete', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']]) }}"><i class="fa fa-trash"></i></a>
									@if(!isset($value['parent']['id']) && strtolower($value['status'])=='offer')
										<a href="{{route('hr.person.workleaves.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id'], 'confirmed_id' => $value['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
									@else
										<a href="{{route('hr.person.workleaves.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
									@endif
									<!-- <a href="{{route('hr.person.workleaves.show', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a> -->
								</td>
							</tr>
						</tbody>
						<?php $i++;?>
					@endforeach
				</table>
			</div>
			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-display']['from']!!} - {!!$PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-display']['to']!!}</p>
					{!!$PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-pagination']->appends(Input::all())->render()!!}
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
