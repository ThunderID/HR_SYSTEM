@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title  or 'Data Kerabat' !!} </h1>
	<small>Total data {{$RelativeComposer['widget_data']['relativelist']['relative-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		<a href="{{ $RelativeComposer['widget_data']['relativelist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@if(isset($RelativeComposer['widget_data']['relativelist']['relative']))
			<div class="clearfix">&nbsp;</div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr class="row">
							<th class="col-sm-2">Nama</th>
							<th class="col-sm-2">Hubungan</th>
							<th class="col-sm-6">Kontak</th>
							<th class="col-sm-2">&nbsp;</th>
						</tr>
					</thead>
					@foreach($RelativeComposer['widget_data']['relativelist']['relative'] as $key => $value)
						<tbody>
							<tr class="row">
								<td class="col-sm-2">
									{{$value['relative']['name']}}
								</td>
								<td class="col-sm-2">
									{{$value['relationship']}}
								</td>
								<td class="col-sm-6">
									@foreach($value['relative']['contacts'] as $key2 => $value2)
										{{ucwords($value2['item'])}} : {{$value2['value']}} <br/>
									@endforeach
								</td>
								<td class="text-right col-sm-2">
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.person.relatives.delete', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']]) }}"><i class="fa fa-trash"></i></a>
									<a href="{{route('hr.person.relatives.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
								</td>
							</tr>
						</tbody>
					@endforeach
				</table>
			</div>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$RelativeComposer['widget_data']['relativelist']['relative-display']['from']!!} - {!!$RelativeComposer['widget_data']['relativelist']['relative-display']['to']!!}</p>
					{!!$RelativeComposer['widget_data']['relativelist']['relative-pagination']->appends(Input::all())->render()!!}
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