@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
		<h1> {!! $widget_title  or 'Otentikasi Group' !!} </h1>
		<small>Total data {{$MenuComposer['widget_data']['menu']['menu-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')	
		@if((int)Session::get('user.menuid')<=3)
			<a href="{{ $MenuComposer['widget_data']['menu']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@endif
		@if(isset($MenuComposer['widget_data']['menu']['menu']))
			<div class="clearfix">&nbsp;</div>
			<table class="table table-hover table-affix">
				<thead>
					<tr>
						<th class="">No</th>
						<th class="">Menu</th>			
						<th class="">Tag</th>
						<th class="">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@forelse($MenuComposer['widget_data']['menu']['menu'] as $key => $value)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ $value['name'] }}</td>
							<td>{{ $value['tag'] }}</td>
							<td class="text-right">
								<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.application.menus.delete', ['id' => $value['id'], 'app_id' => $id]) }}"><i class="fa fa-trash"></i></a>

								<a href="{{ route('hr.application.menus.edit', ['id' => $value['id'], 'app_id' => $id]) }}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
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
					@if($MenuComposer['widget_data']['menu']['menu-pagination']->total()>0)
						<p>Menampilkan {!!$MenuComposer['widget_data']['menu']['menu-display']['from']!!} - {!!$MenuComposer['widget_data']['menu']['menu-display']['to']!!}</p>
						{!!$MenuComposer['widget_data']['menu']['menu-pagination']->appends(Input::all())->render()!!}
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