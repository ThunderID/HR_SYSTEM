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
				<table class="table table-condensed">
					<thead>
						<tr class="row">
							<th class="col-sm-1">No</th>
							<th class="col-sm-1"></th>
							<th class="col-sm-2">Nama</th>
							<th class="col-sm-3">Posisi</th>
							<th class="col-sm-2">Email</th>
							<th class="col-sm-3">&nbsp;</th>
						</tr>
					</thead>
				</table>
			</div>
			<div class="table-responsive div-table-content">
				<table class="table table-condensed table-hover">
					<?php $i = $PersonComposer['widget_data']['personlist']['person-display']['from'];?>
					@foreach($PersonComposer['widget_data']['personlist']['person'] as $key => $value)
						<tbody>
							<tr class="row">
								<td class="col-sm-1">
									{{$i}}
								</td>
								<td class="col-sm-1">
									{!! HTML::image($value['avatar'], '', array( 'width' => 64, 'height' => 64, 'class' => 'img-rounded' )) !!} 
								</td>
								<td class="col-sm-2">
									{{$value['name']}}
								</td>
								<td class="col-sm-3">
									@if(isset($value['works'][0]))
										{{$value['works'][0]['name']}} {{$value['works'][0]['tag']}} {{$value['works'][0]['branch']['name']}}
									@endif
								</td>
								<td class="col-sm-2">
									@if(isset($value['contacts'][0]))
										{{$value['contacts'][0]['value']}}
									@endif
								</td>
								<td class="text-right col-sm-2">
									<div class="btn-group">
										<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pengaturan <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li>
												<a href="javascript:;" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.persons.delete', [$value['id'], 'org_id' => $data['id']]) }}" title="hapus"><i class="fa fa-trash fa-fw"></i> Hapus</a>
											</li>
											<li>
												<a href="{{route('hr.persons.edit', [$value['id'], 'org_id' => $data['id']])}}" title="ubah"><i class="fa fa-pencil fa-fw"></i> Ubah</a>
											</li>
											<li>
												<a href="{{route('hr.persons.show', [$value['id'], 'org_id' => $data['id']])}}"  title="lihat"><i class="fa fa-eye fa-fw"></i> Detail</a>
											</li>
											<li>
												<a href="{{route('hr.person.works.index', ['person_id' => $value['id'], 'org_id' => $data['id']])}}" title="karir"><i class="fa fa-building fa-fw"></i> Karir</a>
											</li>
											<li>
												<a href="{{route('hr.person.schedules.index', ['person_id' => $value['id'], 'org_id' => $data['id']])}}" title="jadwal"><i class="fa fa-calendar"></i> Jadwal</a>
											</li>
											<li>
												<a href="{{route('hr.person.workleaves.index', ['person_id' => $value['id'], 'org_id' => $data['id']])}}" title="jatah cuti"><i class="fa fa-bed fa-fw"></i> Jatah Cuti</a>
											</li>
											<li>
												<a href="{{route('hr.person.contacts.index', ['person_id' => $value['id'], 'org_id' => $data['id']])}}" title="kontak"><i class="fa fa-phone"></i> Kontak</a>
											</li>
											<li>
												<a href="{{route('hr.person.documents.index', ['person_id' => $value['id'], 'org_id' => $data['id']])}}" title="dokumen"><i class="fa fa-file"></i> Dokumen</a>
											</li>
											<li>
												<a href="{{route('hr.person.relatives.index', ['person_id' => $value['id'], 'org_id' => $data['id']])}}" title="kerabat"><i class="fa fa-child"></i> Kerabat</a>
											</li>
										</ul>
									</div>
								</td>
								<td class="col-sm-1">&nbsp;</td>
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