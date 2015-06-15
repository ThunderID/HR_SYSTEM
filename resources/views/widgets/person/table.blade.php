@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Data Karyawan </h1>
<small>Total data {{$widget_data['person-pagination-'.$widget_data['identifier']]->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($widget_data['person-'.$widget_data['identifier']]))
		<div class="clearfix">&nbsp;</div>
		<table class="table">
			<thead>
				<tr>
					<th>Nama</th>
					<th>Posisi</th>
					<th>Email</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			@foreach($widget_data['person-'.$widget_data['identifier']] as $key => $value)
				<tbody>
					<tr>
						<td>
							{{$value['name']}}
						</td>
						<td>
							{{$value['works'][0]['name']}} departemen {{$value['works'][0]['tag']}} cabang {{$value['works'][0]['branch']['name']}}
						</td>
						<td>
							{{$value['contacts'][0]['value']}}
						</td>
						<td class="text-right">
							<a href="" class="btn btn-default"><i class="fa fa-trash"></i></a>
							<a href="{{route('hr.persons.edit', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							<a href="" class="btn btn-default"><i class="fa fa-eye"></i></a>
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$widget_data['person-display-'.$widget_data['identifier']]['from']!!} - {!!$widget_data['person-display-'.$widget_data['identifier']]['to']!!}</p>
				{!!$widget_data['person-pagination-'.$widget_data['identifier']]->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite	
