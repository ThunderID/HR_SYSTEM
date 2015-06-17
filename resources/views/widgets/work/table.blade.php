@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
	<h1> {{ $widget_title or 'Karir' }} </h1>
	<small>Total data {{$WorkComposer['widget_data']['worklist']['work-pagination']->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($WorkComposer['widget_data']['worklist']['work']))
		<div class="clearfix">&nbsp;</div>
		<table class="table">
			<thead>
				<tr>
					<th>Perusahaan</th>
					<th>Jabatan (Status)</th>
					<th>Mulai Bekerja</th>
					<th>Berhenti Bekerja</th>
					<th>Alasan Berhenti</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			@foreach($WorkComposer['widget_data']['worklist']['work'] as $key => $value)
				<tbody>
					<tr>
						@if($value['chart'])
							<td>
								{{$value['chart']['branch']['organisation']['name']}} Cabang {{$value['chart']['branch']['name']}}
							</td>
							<td>
								{{$value['chart']['name']}} Departemen {{$value['chart']['tag']}} ({{$value['status']}})
							</td>
						@else
							<td>
								{{$value['organisation']}}
							</td>
							<td>
								{{$value['position']}} ({{$value['status']}})
							</td>
						@endif
						<td>
							@date_indo($value['start'])
						</td>
						<td>
							@if($value['end']=='0000-00-00')
								Sekarang
							@else
								@date_indo($value['end'])
							@endif
						</td>
						<td>
							{{$value['reason_end_job']}}
						</td>
						<td class="text-right">
							<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.person.works.delete', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']]) }}"><i class="fa fa-trash"></i></a>
							<a href="{{route('hr.person.works.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							<a href="{{route('hr.person.works.show', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a>
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$WorkComposer['widget_data']['worklist']['work-display']['from']!!} - {!!$WorkComposer['widget_data']['worklist']['work-display']['to']!!}</p>
				{!!$WorkComposer['widget_data']['worklist']['work-pagination']->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite
