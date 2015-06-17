@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Dokumen </h1>
<small>Total data {{$DocumentComposer['widget_data']['documentlist']['document-pagination']->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($DocumentComposer['widget_data']['documentlist']['document']))
		<div class="clearfix">&nbsp;</div>
		<table class="table">
			<thead>
				<tr>
					<th>Nama Dokumen</th>
					<th>Kategori</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			@foreach($DocumentComposer['widget_data']['documentlist']['document'] as $key => $value)
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
				<p>Menampilkan {!!$DocumentComposer['widget_data']['documentlist']['document-display']['from']!!} - {!!$DocumentComposer['widget_data']['documentlist']['document-display']['to']!!}</p>
				{!!$DocumentComposer['widget_data']['documentlist']['document-pagination']->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite	
