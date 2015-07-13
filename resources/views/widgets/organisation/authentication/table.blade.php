@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {!! $widget_title  or 'Otentikasi' !!} </h1>
		<small>Total data {{$WorkAuthenticationComposer['widget_data']['workauthlist']['workauth-pagination']->total()}}</small>
		
	@overwrite

	@section('widget_body')
		@if((int)Session::get('user.menuid')<=4)
			<a href="{{ $WorkAuthenticationComposer['widget_data']['workauthlist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@endif
		<div class="clearfix">&nbsp;</div>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama</th>
						<th>Level</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = $WorkAuthenticationComposer['widget_data']['workauthlist']['workauth-display']['from'];?>
					@foreach($WorkAuthenticationComposer['widget_data']['workauthlist']['workauth'] as $key => $value)
						<tr>
							<td>{{$i}}</td>
							<td>{{$value['work']['person']['name']}}</td>
							<td>{{$value['authgroup']['name']}}</td>
							<td class="text-right">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pengaturan <span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-right">
										<li>
											<a href="javascript:;" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.authentications.delete', [$value['id'], 'org_id' => $data['id']]) }}" title="hapus"><i class="fa fa-trash fa-fw"></i> Hapus</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						<?php $i++;?>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$WorkAuthenticationComposer['widget_data']['workauthlist']['workauth-display']['from']!!} - {!!$WorkAuthenticationComposer['widget_data']['workauthlist']['workauth-display']['to']!!}</p>
				{!!$WorkAuthenticationComposer['widget_data']['workauthlist']['workauth-pagination']->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@overwrite
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif