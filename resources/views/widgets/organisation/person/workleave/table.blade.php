@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))
@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Data Karyawan'!!} </h1>
	<small>Total data {{$PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-pagination']->total()}}</small>

	@if(isset($PersonWorkleaveComposer['widget_data']['workleavelist']['active_filter']) && !is_null($PersonWorkleaveComposer['widget_data']['workleavelist']['active_filter']))
		 <div class="clearfix">&nbsp;</div>
		@foreach($PersonWorkleaveComposer['widget_data']['workleavelist']['active_filter'] as $key => $value)
			<span class="active-filter">{{$value}}</span>
		@endforeach
	@endif
	@overwrite

	@section('widget_body')
		@if((int)Session::get('user.menuid')<5)
			@include('widgets.common.selectblock', [
					'widget_title'			=> 'Pilih Data',
					'widget_options'		=> 	[
													'url_old'		=> route('hr.person.workleaves.create', ['org_id' => $data['id'], 'person_id' => $person['id'], 'type' => 'given']),
													'url_new'		=> route('hr.person.workleaves.create', ['org_id' => $data['id'], 'person_id' => $person['id'], 'type' => 'taken']),
													'caption_old'	=> 'Pemberian Cuti',
													'caption_new'	=> 'Pengambilan Cuti',
												],
					])
		@endif
		@if(isset($PersonWorkleaveComposer['widget_data']['workleavelist']['workleave']))
			<div class="clearfix">&nbsp;</div>
			<table class="table table-hover table-affix">
				<thead>
					<tr class="row">
						<th class="">No</th>
						<th class="">Keterangan</th>
						<th class="text-center">Quota</th>
						<th class="">Status</th>
						<th class="">Diberikan Oleh</th>
						<th class="">Masa Berlaku</th>
						<th class="">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = $PersonWorkleaveComposer['widget_data']['workleavelist']['workleave-display']['from'];?>
					@forelse($PersonWorkleaveComposer['widget_data']['workleavelist']['workleave'] as $key => $value)
						<tr class="row">
							<td class="">
								{{$i}}
							</td>
							<td class="">
								{{$value['name']}}
							</td>
							<td class="text-center">
								{{abs($value['quota'])}}
							</td>
							<td class="">
								{{$value['status']}}
							</td>
							<td class="">
								{{$value['createdby']['name']}}
							</td>
							<td class="">
								{{ date('d-m-Y', strtotime($value['start'])) }} - 
								{{ date('d-m-Y', strtotime($value['end'])) }}
							</td>
							<td class="text-right ">
								@if((int)Session::get('user.menuid') <= 2)
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.person.workleaves.delete', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']]) }}"><i class="fa fa-trash"></i></a>
								@endif
								@if((int)Session::get('user.menuid')<=3)
									@if($value['workleave_id']!=0)
										<a href="{{route('hr.person.workleaves.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id'], 'type' => 'given'])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
									@else
										<a href="{{route('hr.person.workleaves.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id'], 'type' => 'taken'])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
									@endif
								@endif
							</td>
						</tr>
						<?php $i++;?>
					@empty 
						<tr>
							<td class="text-center" colspan="7">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
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
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif
