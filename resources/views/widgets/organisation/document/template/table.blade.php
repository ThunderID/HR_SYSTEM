@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title  or 'Template Dokumen' !!} </h1>
	<small>Total data {{$TemplateComposer['widget_data']['templatelist']['template-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		<a href="{{ $TemplateComposer['widget_data']['templatelist']['route_create'] }}" class="btn btn-primary">Tambah Template</a>
		@if(isset($TemplateComposer['widget_data']['templatelist']['template']))
			<div class="clearfix">&nbsp;</div>
			<table class="table table-affix">
				<thead>
					<tr>
						<th>Template</th>
						<th>Tipe</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@forelse($TemplateComposer['widget_data']['templatelist']['template'] as $key => $value)
						<tr>
							<td>
								{{$value['field']}}
							</td>
							<td>
								{{$value['type']}}
							</td>
							<td class="text-right">
								<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.document.templates.delete', [$value['id'], 'org_id' => $data['id'], 'doc_id' => $document['id']]) }}"><i class="fa fa-trash"></i></a>
								<a href="{{route('hr.document.templates.edit', [$value['id'], 'org_id' => $data['id'], 'doc_id' => $document['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							</td>
						</tr>
					@empty 
						<tr>
							<td class="text-center" colspan="3">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
			</table>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$TemplateComposer['widget_data']['templatelist']['template-display']['from']!!} - {!!$TemplateComposer['widget_data']['templatelist']['template-display']['to']!!}</p>
					{!!$TemplateComposer['widget_data']['templatelist']['template-pagination']->appends(Input::all())->render()!!}
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