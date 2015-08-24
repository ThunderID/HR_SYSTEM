@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	<?php
		$PolicyComposer['widget_data']['policylist']['policy-pagination']->setPath(route('hr.policies.index'));
	?>

	@section('widget_title')
		<h1> {!! $widget_title or 'Kebijakan' !!} </h1>
		<small>Total data {{$PolicyComposer['widget_data']['policylist']['policy-pagination']->total()}}</small>
		@if(isset($PolicyComposer['widget_data']['policylist']['active_filter']) && !is_null($PolicyComposer['widget_data']['policylist']['active_filter']))
			 <div class="clearfix">&nbsp;</div>
			@foreach($PolicyComposer['widget_data']['policylist']['active_filter'] as $key => $value)
				<span class="active-filter">{{$value}}</span>
			@endforeach
		@endif
	@overwrite

	@section('widget_body')
		@if((int)Session::get('user.menuid')<=3)
			<a href="{{ $PolicyComposer['widget_data']['policylist']['route_create'] }}" class="btn btn-primary">Tambah</a>
		@endif
		@if(isset($PolicyComposer['widget_data']['policylist']['policy']))
			<div class="clearfix">&nbsp;</div>			
			<table class="table table-hover table-affix">
				<thead>
					<tr>
						<th>No</th>
						<th>Created By</th>
						<th>Tipe</th>
						<th class="text-center">Value</th>
						<th>Started at</th>
						<!-- <th>&nbsp;</th> -->
					</tr>
				</thead>
				<tbody>
					<?php $i = $PolicyComposer['widget_data']['policylist']['policy-display']['from'];?>
					@forelse($PolicyComposer['widget_data']['policylist']['policy'] as $key => $value)
						<tr>
							<td>
								{{$i}}
							</td>
							<td>
								{{ $value['createdby']['prefix_title'] }} {{ $value['createdby']['name'] }} {{ $value['createdby']['suffix_title'] }}
							</td>
							<td>
								{{ $value['type']}}
							</td>
							<td class="text-center">
								{{ $value['value'] }}
							</td>
							<td>
								{{ date('d-m-Y', strtotime($value['started_at'])) }}
							</td>
							<!-- <td class="text-right">
								@if((int)Session::get('user.menuid')<=2)
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.policies.delete', [$value['id'], 'org_id' => $data['id'] ]) }}"><i class="fa fa-trash"></i></a>
								@endif
								@if((int)Session::get('user.menuid')<=3)
									<a href="{{route('hr.policies.edit', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
								@endif
							</td> -->
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
					<p>Menampilkan {!!$PolicyComposer['widget_data']['policylist']['policy-display']['from']!!} - {!!$PolicyComposer['widget_data']['policylist']['policy-display']['to']!!}</p>
					{!!$PolicyComposer['widget_data']['policylist']['policy-pagination']->appends(Input::all())->render()!!}
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