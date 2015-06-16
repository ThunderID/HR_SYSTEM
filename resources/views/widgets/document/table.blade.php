@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Dokumen </h1>
<small>Total data {{$widget_data['document-pagination-'.$widget_data['identifier']]->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($widget_data['document-'.$widget_data['identifier']]))
		<div class="clearfix">&nbsp;</div>
		<table class="table">
			<thead>
				<tr>
					<th>Nama Dokumen</th>
					<th>Kategori</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			@foreach($widget_data['document-'.$widget_data['identifier']] as $key => $value)
				<tbody>
					<tr>
						<td>
							{{$value['name']}}
						</td>
						<td>
							{{$value['tag']}}
						</td>
						<td class="text-right">
							<a href="" class="btn btn-default"><i class="fa fa-trash"></i></a>
							<a href="{{route('hr.documents.edit', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							<a href="" class="btn btn-default"><i class="fa fa-eye"></i></a>
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$widget_data['document-display-'.$widget_data['identifier']]['from']!!} - {!!$widget_data['document-display-'.$widget_data['identifier']]['to']!!}</p>
				{!!$widget_data['document-pagination-'.$widget_data['identifier']]->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite	
