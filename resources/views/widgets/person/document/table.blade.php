@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	<?php
		$PersonDocumentComposer['widget_data']['documentlist']['document-pagination']->setPath(route('hr.person.documents.index'));
	?>
	@section('widget_title')
	<h1> {!! $widget_title or 'Dokumen Karyawan'!!} </h1>
	<small>Total data {{$PersonDocumentComposer['widget_data']['documentlist']['document-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		<a href="{{ $PersonDocumentComposer['widget_data']['documentlist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@if(isset($PersonDocumentComposer['widget_data']['documentlist']['document']))
			<div class="clearfix">&nbsp;</div>
			<table class="table">
				<thead>
					<tr class="row">
						<th class="col-sm-2">Dokumen</th>
						<th class="col-sm-4">Jenis</th>
						<th class="col-sm-4">Dikeluarkan Tanggal</th>
						<th class="col-sm-2">&nbsp;</th>
					</tr>
				</thead>
				@foreach($PersonDocumentComposer['widget_data']['documentlist']['document'] as $key => $value)
					<tbody>
						<tr class="row">
							<td class="col-sm-2">
								{{$value['document']['name']}}
							</td>
							<td class="col-sm-4">
								{{$value['document']['tag']}}
							</td>
							<td class="col-sm-2">
								@date_indo($value['created_at'])
							</td>
							<td class="text-right col-sm-4">
								<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.person.documents.delete', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']]) }}"><i class="fa fa-trash"></i></a>
								<a href="{{route('hr.person.documents.edit', [$value['id'], 'doc_id' => $value['document_id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
								<a href="{{route('hr.person.documents.show', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a>
							</td>
						</tr>
					</tbody>
				@endforeach
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