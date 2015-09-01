@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title  or 'Template Dokumen' !!} </h1>
	<small>Total data {{$DocumentComposer['widget_data']['documentlist']['document-pagination']->total()}}</small>
	
	<div class="clearfix">&nbsp;</div>
	@if(!is_null($DocumentComposer['widget_data']['documentlist']['active_filter']))
		@foreach($DocumentComposer['widget_data']['documentlist']['active_filter'] as $key => $value)
			<span class="active-filter">{{$value}}</span>
		@endforeach
	@endif

	@overwrite

	@section('widget_body')
		<a href="{{ $DocumentComposer['widget_data']['documentlist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@if(isset($DocumentComposer['widget_data']['documentlist']['document']))
			<div class="clearfix">&nbsp;</div>
			<table class="table table-hover table-affix">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama Dokumen</th>
						<th>Kategori</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = $DocumentComposer['widget_data']['documentlist']['document-display']['from'];?>
					@forelse($DocumentComposer['widget_data']['documentlist']['document'] as $key => $value)
						<tr>
							<td>
								{{$i}}
							</td>
							<td>
								{{$value['name']}}
							</td>
							<td>
								{{$value['tag']}}
							</td>
							<td class="text-right">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pengaturan <span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-right">
										@if((int)Session::get('user.menuid') <= 2)
											<li>
												<a href="javascript:;" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.documents.delete', [$value['id'], 'org_id' => $data['id']]) }}"><i class="fa fa-trash fa-fw"></i> Hapus</a>
											</li>
										@endif
										@if((int)Session::get('user.menuid') <= 3)
											<li>
												<a href="{{route('hr.documents.edit', [$value['id'], 'org_id' => $data['id']])}}"><i class="fa fa-pencil fa-fw"></i> Ubah</a>
											</li>
										@endif
										<li>
											<a href="{{route('hr.documents.show', [$value['id'], 'org_id' => $data['id']])}}"><i class="fa fa-eye fa-fw"></i> Details</a>
										</li>
										<li>
											<a href="javasript:;" data-toggle="modal" data-target="#import_csv_doc_org" data-action="{{ route('hr.documents.store') }}" data-doc_name="{{ $value['name'] }}" data-doc_id="{{ $value['id'] }}" data-org_id="{{ $data['id'] }}"><i class="fa fa-cloud-download fa-fw"></i> Import CSV</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						<?php $i++;?>
					@empty 
						<tr>
							<td class="text-center" colspan="4">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
			</table>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$DocumentComposer['widget_data']['documentlist']['document-display']['from']!!} - {!!$DocumentComposer['widget_data']['documentlist']['document-display']['to']!!}</p>
					{!!$DocumentComposer['widget_data']['documentlist']['document-pagination']->appends(Input::all())->render()!!}
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