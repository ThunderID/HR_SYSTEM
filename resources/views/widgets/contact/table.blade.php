<?php
	$ContactComposer['widget_data']['contactlist']['contact-pagination']->setPath(route('hr.person.contacts.index'));
 ?>

@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {{ $widget_title or 'Kontak' }} </h1>
	<small>Total data {{ $ContactComposer['widget_data']['contactlist']['contact-pagination']->total() }}</small>

	@overwrite

	@section('widget_body')
			@if(isset($ContactComposer['widget_data']['contactlist']['contact']))
				<div class="clearfix">&nbsp;</div>
				<table class="table">
					<thead>
						<tr>
							<th colspan="2">Kontak</th>
							<th>Aktif</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					@foreach($ContactComposer['widget_data']['contactlist']['contact'] as $key => $value)
						<tbody>
							<tr>
								<td>
									{{$value['item']}}
								</td>
								<td>
									{{$value['value']}}
								</td>
								<td>
									@if($value['is_default'])
										<i class="fa fa-check"></i>
									@else
										<i class="fa fa-minus"></i>
									@endif
								</td>
								<td class="text-right">
									<a href="" class="btn btn-default"><i class="fa fa-trash"></i></a>
									<a href="{{route('hr.person.contacts.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
								</td>
							</tr>
						</tbody>
					@endforeach
				</table>

				<div class="row">
					<div class="col-sm-12 text-center">
						<p>Menampilkan {!! $ContactComposer['widget_data']['contactlist']['contact-display']['from']!!} - {!! $ContactComposer['widget_data']['contactlist']['contact-display']['to']!!}</p>
						{!! $ContactComposer['widget_data']['contactlist']['contact-pagination']->appends(Input::all())->render()!!}
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