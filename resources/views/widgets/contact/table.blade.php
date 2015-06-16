<?php
	$widget_data['contact-pagination-'.$widget_data['identifier']]->setPath(route('hr.branch.contacts.index'));
 ?>

@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Kontak </h1>
<small>Total data {{$widget_data['contact-pagination-'.$widget_data['identifier']]->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($widget_data['contact-'.$widget_data['identifier']]))
		<div class="clearfix">&nbsp;</div>
		<table class="table">
			<thead>
				<tr>
					<th colspan="2">Kontak</th>
					<th>Aktif</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			@foreach($widget_data['contact-'.$widget_data['identifier']] as $key => $value)
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
							<a href="{{route('hr.branch.contacts.edit', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$widget_data['contact-display-'.$widget_data['identifier']]['from']!!} - {!!$widget_data['contact-display-'.$widget_data['identifier']]['to']!!}</p>
				{!!$widget_data['contact-pagination-'.$widget_data['identifier']]->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite	
