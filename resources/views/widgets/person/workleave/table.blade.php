@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> {{ $widget_title or 'Data Karyawan'}} </h1>
<small>Total data {{$PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-pagination']->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($PersonWorkleaveComposer['widget_data']['workleavelist']['workleave']))
		<div class="clearfix">&nbsp;</div>
		<table class="table">
			<thead>
				<tr class="row">
					<th class="col-sm-2">Cuti</th>
					<th class="col-sm-4">Quota</th>
					<th class="col-sm-2">Masa Berlaku</th>
					<th class="col-sm-4">&nbsp;</th>
				</tr>
			</thead>
			@foreach($PersonWorkleaveComposer['widget_data']['workleavelist']['workleave'] as $key => $value)
				<tbody>
					<tr class="row">
						<td class="col-sm-2">
							{{$value['workleave']['name']}}
						</td>
						<td class="col-sm-4">
							{{$value['workleave']['quota']}}
						</td>
						<td class="col-sm-2">
							@date_indo($value['start']) - 
							@date_indo($value['end'])
						</td>
						<td class="text-right col-sm-4">
							<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.person.workleaves.delete', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']]) }}"><i class="fa fa-trash"></i></a>
							<a href="{{route('hr.person.workleaves.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							<a href="{{route('hr.person.workleaves.show', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a>
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-display']['from']!!} - {!!$PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-display']['to']!!}</p>
				{!!$PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-pagination']->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite	
