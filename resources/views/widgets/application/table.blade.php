@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
		<h1> {!! $widget_title  or 'Aplikasi' !!} </h1>
		<small>Total data {{$ApplicationComposer['widget_data']['application']['application-pagination']->total()}}</small>
	@overwrite

	@section('widget_body')
		@if((int)Session::get('user.menuid')<=3)
			<a href="{{ $ApplicationComposer['widget_data']['application']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@endif
		@if(isset($ApplicationComposer['widget_data']['application']['application']))
			<div class="clearfix">&nbsp;</div>
			<table class="table table-hover table-affix">
				<thead>
					<tr>
						<th class="">No</th>
						<th class="">Nama</th>			
						<th class="">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@forelse($ApplicationComposer['widget_data']['application']['application'] as $key => $value)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ $value['name'] }}</td>
							<td class="text-right">
								<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.applications.delete', [$value['id']]) }}"><i class="fa fa-trash"></i></a>

								<a href="{{ route('hr.applications.edit', [$value['id']]) }}" class="btn btn-default"><i class="fa fa-pencil"></i></a>

								<a href="{{ route('hr.applications.show', [$value['id']]) }}" class="btn btn-default"><i class="fa fa-eye"></i></a>
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
					@if($ApplicationComposer['widget_data']['application']['application-pagination']->total()>0)
						<p>Menampilkan {!!$ApplicationComposer['widget_data']['application']['application-display']['from']!!} - {!!$ApplicationComposer['widget_data']['application']['application-display']['to']!!}</p>
						{!!$ApplicationComposer['widget_data']['application']['application-pagination']->appends(Input::all())->render()!!}
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