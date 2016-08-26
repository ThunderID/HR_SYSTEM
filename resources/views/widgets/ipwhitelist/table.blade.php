@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
		<h1> {!! $widget_title  or 'IP Whitelist' !!} </h1>
		<small>Total data {{$IPWhitelistComposer['widget_data']['ipwhitelist']['ipwhitelist-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		@if((int)Session::get('user.menuid')<=3)
			<a href="{{ $IPWhitelistComposer['widget_data']['ipwhitelist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@endif
		@if(isset($IPWhitelistComposer['widget_data']['ipwhitelist']['ipwhitelist']))
			<div class="clearfix">&nbsp;</div>
			<table class="table table-hover table-affix">
				<thead>
					<tr>
						<th class="">No</th>
						<th class="">IP</th>			
						<th class="">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@forelse($IPWhitelistComposer['widget_data']['ipwhitelist']['ipwhitelist'] as $key => $value)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ $value['ip'] }}</td>
							<td class="text-right">
								<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.ipwhitelists.delete', [$value['id']]) }}"><i class="fa fa-trash"></i></a>

								<a href="{{ route('hr.ipwhitelists.edit', [$value['id']]) }}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							</td>
						</tr>
					@empty 
						<tr>
							<td class="text-center" colspan="3">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		
			<div class="row">
				<div class="col-sm-12 text-center">
					@if($IPWhitelistComposer['widget_data']['ipwhitelist']['ipwhitelist-pagination']->total()>0)
						<p>Menampilkan {!!$IPWhitelistComposer['widget_data']['ipwhitelist']['ipwhitelist-display']['from']!!} - {!!$IPWhitelistComposer['widget_data']['ipwhitelist']['ipwhitelist-display']['to']!!}</p>
						{!!$IPWhitelistComposer['widget_data']['ipwhitelist']['ipwhitelist-pagination']->appends(Input::all())->render()!!}
					@else
						Tidak ada data
					@endif
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