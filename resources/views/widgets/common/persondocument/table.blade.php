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
		@if(isset($PersonDocumentComposer['widget_data']['documentlist']['document']))
			<div class="clearfix">&nbsp;</div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr class="row">
							<th class="col-sm-1">No</th>
							<th class="col-sm-2">{{(isset($PersonDocumentComposer['widget_data']['documentlist']['document'][0]['document']) ? 'Dokumen' : 'Karyawan')}}</th>
							<th class="col-sm-3">{{(isset($PersonDocumentComposer['widget_data']['documentlist']['document'][0]['document']) ? 'Jenis' : '')}}</th>
							<th class="col-sm-2">Dikeluarkan Tanggal</th>
							<th class="col-sm-4">&nbsp;</th>
						</tr>
					</thead>
					<?php $i = $PersonDocumentComposer['widget_data']['documentlist']['document-display']['from'];?>
					@foreach($PersonDocumentComposer['widget_data']['documentlist']['document'] as $key => $value)
						<tbody>
							<tr class="row">
								<td class="col-sm-1">
									{{$i}}
								</td>
								<td class="col-sm-2">
									{{(isset($value['document']['name']) ? $value['document']['name'] : $value['person']['name'])}}
								</td>
								<td class="col-sm-3">
									{{(isset($value['document']['tag']) ? $value['document']['tag'] : '')}}
								</td>
								<td class="col-sm-2">
									@date_indo($value['created_at'])
								</td>
								<td class="text-right col-sm-4">
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.person.documents.delete', [$value['id'], 'org_id' => $data['id'], 'person_id' => $value['person_id']]) }}"><i class="fa fa-trash"></i></a>
									<a href="{{route('hr.person.documents.edit', [$value['id'], 'doc_id' => $value['document_id'], 'org_id' => $data['id'], 'person_id' => $value['person_id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
									<a href="{{route('hr.person.documents.show', [$value['id'], 'org_id' => $data['id'], 'person_id' => $value['person_id']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a>
								</td>
							</tr>
						</tbody>
						<?php $i++;?>
					@endforeach
				</table>
			</div>

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