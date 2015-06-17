<?php
	$ApiComposer['widget_data']['apilist']['api-pagination']->setPath(route('hr.branch.apis.index'));
 ?>

@if (!$widget_error_count)
	@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

	@section('widget_title')
		<h1> {{ $widget_title or 'Api' }} </h1>
		<small>Total data {{$ApiComposer['widget_data']['apilist']['api-pagination']->total()}}</small>
		<div class="row">
			<div class="col-md-2 mt-20">
				@include('widgets.branch.sidemenu')
			</div>
			<div class="col-md-10">
	@overwrite

	@section('widget_body')
				@if(isset($ApiComposer['widget_data']['apilist']['api']))
					<div class="clearfix">&nbsp;</div>
					<table class="table">
						<thead>
							<tr>
								<th>CLIENT</th>
								<th>SECRET</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						@foreach($ApiComposer['widget_data']['apilist']['api'] as $key => $value)
							<tbody>
								<tr>
									<td>
										{{$value['client']}}
									</td>
									<td>
										{{$value['secret']}}
									</td>
									<td class="text-right">
										<a href="" class="btn btn-default"><i class="fa fa-trash"></i></a>
										<a href="{{route('hr.branch.apis.edit', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
										<a href="" class="btn btn-default"><i class="fa fa-eye"></i></a>
									</td>
								</tr>
							</tbody>
						@endforeach
					</table>

					<div class="row">
						<div class="col-sm-12 text-center">
							<p>Menampilkan {!!$ApiComposer['widget_data']['apilist']['api-display']['from']!!} - {!!$ApiComposer['widget_data']['apilist']['api-display']['to']!!}</p>
							{!!$ApiComposer['widget_data']['apilist']['api-pagination']->appends(Input::all())->render()!!}
						</div>
					</div>

					<div class="clearfix">&nbsp;</div>
				@endif
			</div>
		</div>
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif