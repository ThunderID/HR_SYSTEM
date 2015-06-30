@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {!! $widget_title  or 'Data Karyawan' !!} </h1>
		<small>Total data {{$PersonComposer['widget_data']['personlist']['person-pagination']->total()}}</small>

		@if(isset($PersonComposer['widget_data']['personlist']['active_filter']) && !is_null($PersonComposer['widget_data']['personlist']['active_filter']))
			<div class="clearfix">&nbsp;</div>
			@foreach($PersonComposer['widget_data']['personlist']['active_filter'] as $key => $value)
				<span class="active-filter">{{$value}}</span>
			@endforeach
		@endif
	@overwrite

	@section('widget_body')
		<a href="{{ $PersonComposer['widget_data']['personlist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@if(isset($PersonComposer['widget_data']['personlist']['person']))
			<div class="clearfix">&nbsp;</div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr class="row">
							<th class="col-sm-1">No</th>
							<th class="col-sm-3" colspan="2">Nama</th>
							<th class="col-sm-3">Posisi</th>
							<th class="col-sm-2">Email</th>
							<th class="col-sm-3">&nbsp;</th>
						</tr>
					</thead>
					<?php $i = $PersonComposer['widget_data']['personlist']['person-display']['from'];?>
					@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
						<tbody>
							<tr class="row">
								<td>
									{{$i}}
								</td>
								<td class="col-sm-1">
									{!! HTML::image($value['avatar'], '', array( 'width' => 64, 'height' => 64, 'class' => 'img-rounded' )) !!} 
								</td>
								<td class="col-sm-3">
									{{$value['name']}}
								</td>
								<td class="col-sm-3">
									@if(isset($value['works'][0]))
										{{$value['works'][0]['name']}} departemen {{$value['works'][0]['tag']}} cabang {{$value['works'][0]['branch']['name']}}
									@endif
								</td>
								<td class="col-sm-2">
									@if(isset($value['contacts'][0]))
										{{$value['contacts'][0]['value']}}
									@endif
								</td>
								<td class="text-right col-sm-3">
									<a href="javascript:;" class="btn btn-default crowd-buttons text-right" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.persons.delete', [$value['id'], 'org_id' => $data['id']]) }}" title="hapus"><i class="fa fa-trash"></i></a>
									<a href="{{route('hr.persons.edit', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default crowd-buttons text-right" title="ubah"><i class="fa fa-pencil"></i></a>
									<a href="{{route('hr.persons.show', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default crowd-buttons text-right" title="lihat"><i class="fa fa-eye"></i></a>
									
									<a href="{{route('hr.person.contacts.index', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default crowd-buttons text-right" title="kontak"><i class="fa fa-phone"></i></a>
									<a href="{{route('hr.person.works.index', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default crowd-buttons text-right" title="karir"><i class="fa fa-building"></i></a>
									<a href="{{route('hr.person.schedules.index', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default crowd-buttons text-right" title="jadwal"><i class="fa fa-calendar"></i></a>
									
									<a href="{{route('hr.person.documents.index', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default crowd-buttons text-right" title="dokumen"><i class="fa fa-file"></i></a>
									<a href="{{route('hr.person.workleaves.index', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default crowd-buttons text-right" title="jatah cuti"><i class="fa fa-bed"></i></a>
									<a href="{{route('hr.person.relatives.index', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default crowd-buttons text-right" title="kerabat"><i class="fa fa-child"></i></a>
								</td>
							</tr>
						</tbody>
						<?php $i++;?>
					@endforeach
				</table>
			</div>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$PersonComposer['widget_data']['personlist']['person-display']['from']!!} - {!!$PersonComposer['widget_data']['personlist']['person-display']['to']!!}</p>
					{!!$PersonComposer['widget_data']['personlist']['person-pagination']->appends(Input::all())->render()!!}
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