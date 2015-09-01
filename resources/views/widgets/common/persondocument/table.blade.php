@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	<?php
		if(isset($PersonDocumentComposer['widget_data']['documentlist']['route_read']))
		{
			$PersonDocumentComposer['widget_data']['documentlist']['document-pagination']->setPath($PersonDocumentComposer['widget_data']['documentlist']['route_read']);
		}
		else
		{
			$PersonDocumentComposer['widget_data']['documentlist']['document-pagination']->setPath(route('hr.person.documents.index'));
		}
	?>
	@section('widget_title')
		<h1> {!! $widget_title or 'Dokumen Karyawan'!!} </h1>
		<small>Total data {{$PersonDocumentComposer['widget_data']['documentlist']['document-pagination']->total()}}</small>

		@if(isset($PersonDocumentComposer['widget_data']['documentlist']['active_filter']) && !is_null($PersonDocumentComposer['widget_data']['documentlist']['active_filter']))
			 <div class="clearfix">&nbsp;</div>
			@foreach($PersonDocumentComposer['widget_data']['documentlist']['active_filter'] as $key => $value)
				<span class="active-filter">{{$value}}</span>
			@endforeach
		@endif
	@overwrite

	@section('widget_body')
		<a href="{{ $PersonDocumentComposer['widget_data']['documentlist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		<a href="javasript:;" class="btn btn-primary" data-toggle="modal" data-target="#import_csv_doc_org" data-action="{{ route('hr.documents.store') }}" data-doc_name="{{ $document['name'] }}" data-doc_id="{{ $document['id'] }}" data-org_id="{{ $data['id'] }}">Import CSV</a>
		@if(isset($PersonDocumentComposer['widget_data']['documentlist']['document']))
			<div class="clearfix">&nbsp;</div>
			<table class="table table-hover table-affix">
				<thead>
					<tr class="row">
						<th class="">No</th>
						<th class="">{{(isset($PersonDocumentComposer['widget_data']['documentlist']['document'][0]['document']) ? 'Dokumen' : 'Karyawan')}}</th>
						<th class="">{{(isset($PersonDocumentComposer['widget_data']['documentlist']['document'][0]['document']) ? 'Jenis' : '')}}</th>
						<th class="">Dikeluarkan Tanggal</th>
						<th class="">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = $PersonDocumentComposer['widget_data']['documentlist']['document-display']['from'];?>
					@forelse($PersonDocumentComposer['widget_data']['documentlist']['document'] as $key => $value)
						<tr class="row">
							<td class="">
								{{$i}}
							</td>
							<td class="">
								{{(isset($value['document']['name']) ? $value['document']['name'] : $value['person']['name'])}}
							</td>
							<td class="">
								{{(isset($value['document']['tag']) ? $value['document']['tag'] : '')}}
							</td>
							<td class="">
								{{ date('d-m-Y', strtotime($value['created_at'])) }}
							</td>
							<td class="text-right ">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pengaturan <span class="caret"></span></button>
									<ul class="dropdown-menu">
										@if((int)Session::get('user.menuid') <= 2)
											<li>
												<a href="javascript:;" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.person.documents.delete', [$value['id'], 'org_id' => $data['id'], 'person_id' => $value['person_id']]) }}"><i class="fa fa-trash fa-fw"></i> Hapus</a>
											</li>
										@endif
										@if((int)Session::get('user.menuid') <= 3)
											<li>
												<a href="{{route('hr.person.documents.edit', [$value['id'], 'doc_id' => $value['document_id'], 'org_id' => $data['id'], 'person_id' => $value['person_id']])}}"><i class="fa fa-pencil fa-fw"></i> Ubah</a>
											</li>
										@endif
										<li>
											<a href="{{route('hr.person.documents.show', [$value['id'], 'org_id' => $data['id'], 'person_id' => $value['person_id']])}}"><i class="fa fa-eye fa-fw"></i> Detail</a>
										</li>
										<li>
											<a href="{{route('hr.person.documents.show', [$value['id'], 'org_id' => $data['id'], 'person_id' => $value['person_id'], 'pdf' =>'yes'])}}"><i class="fa fa-file-pdf-o fa-fw"></i> Export to PDF</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						<?php $i++;?>
					@empty 
						<tr>
							<td class="text-center" colspan="5">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
			</table>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$PersonDocumentComposer['widget_data']['documentlist']['document-display']['from']!!} - {!!$PersonDocumentComposer['widget_data']['documentlist']['document-display']['to']!!}</p>
					{!!$PersonDocumentComposer['widget_data']['documentlist']['document-pagination']->appends(Input::all())->render()!!}
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