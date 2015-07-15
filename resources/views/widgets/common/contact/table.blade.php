@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title or 'Kontak' !!} </h1>
	<small>Total data {{ $ContactComposer['widget_data']['contactlist']['contact-pagination']->total() }}</small>
	<?php
		$ContactComposer['widget_data']['contactlist']['contact-pagination']->setPath($ContactComposer['widget_data']['contactlist']['route']);
	 ?>

	@if(isset($ContactComposer['widget_data']['contactlist']['active_filter']) && !is_null($ContactComposer['widget_data']['contactlist']['active_filter']))
		 <div class="clearfix">&nbsp;</div>
		@foreach($ContactComposer['widget_data']['contactlist']['active_filter'] as $key => $value)
			<span class="active-filter">{{$value}}</span>
		@endforeach
	@endif
	@overwrite

	@section('widget_body')
			@if((int)Session::get('user.menuid')<5)
				<a href="{{ $ContactComposer['widget_data']['contactlist']['route_create'] }}" class="btn btn-primary">Tambah</a>
			@endif
			@if(isset($ContactComposer['widget_data']['contactlist']['contact']))
				<div class="clearfix">&nbsp;</div>
				<table class="table table-hover table-affix">
					<thead>
						<tr>
							<th>No</th>
							<th colspan="2">Kontak</th>
							<th>Aktif</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = $ContactComposer['widget_data']['contactlist']['contact-display']['from'];?>
						@forelse($ContactComposer['widget_data']['contactlist']['contact'] as $key => $value)
							<tr>
								<td>
									{{$i}}
								</td>
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
									@if((int)Session::get('user.menuid')<=2)
										<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route($ContactComposer['widget_data']['contactlist']['route_delete'], ['id' => $value['id'], 'org_id' => $data['id'], $ContactComposer['widget_data']['contactlist']['next'] => $ContactComposer['widget_data']['contactlist']['nextid'] ]) }}"><i class="fa fa-trash"></i></a>
									@endif
									@if((int)Session::get('user.menuid')<=3)
										<a href="{{route($ContactComposer['widget_data']['contactlist']['route_edit'], ['id' => $value['id'], 'org_id' => $data['id'],  $ContactComposer['widget_data']['contactlist']['next'] => $ContactComposer['widget_data']['contactlist']['nextid'] ])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
									@endif
								</td>
							</tr>
							<?php $i++;?>
						@empty
							<tr>
								<td class="text-center" colspan="4">Tidak ada data</td>
							</tr>
						@endforelse
					</tbody>
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