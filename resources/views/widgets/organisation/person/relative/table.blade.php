@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {!! $widget_title  or 'Data Kerabat' !!} </h1>
	<small>Total data {{$RelativeComposer['widget_data']['relativelist']['relative-pagination']->total()}}</small>

	<?php $relationship = ['child' => 'Anak', 'parent' => 'Orang Tua', 'spouse' => 'Pasangan (Menikah)', 'partner' => 'Pasangan (Tidak Menikah)'];?>

	<div class="clearfix">&nbsp;</div>
	@if(isset($RelativeComposer['widget_data']['relativelist']['active_filter']) && !is_null($RelativeComposer['widget_data']['relativelist']['active_filter']))
		@foreach($RelativeComposer['widget_data']['relativelist']['active_filter'] as $key => $value)
			<span class="active-filter">{{$value}}</span>
		@endforeach
	@endif
	@overwrite

	@section('widget_body')
		@if((int)Session::get('user.menuid')<5)
			<a href="{{ $RelativeComposer['widget_data']['relativelist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@endif
		@if(isset($RelativeComposer['widget_data']['relativelist']['relative']))
			<div class="clearfix">&nbsp;</div>
			<table class="table table-hover table-affix">
				<thead>
					<tr class="row">
						<th class="col-sm-1">No</th>
						<th class="col-sm-2">Nama</th>
						<th class="col-sm-2">Hubungan</th>
						<th class="col-sm-5">Kontak</th>
						<th class="col-sm-2">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = $RelativeComposer['widget_data']['relativelist']['relative-display']['from'];?>
					@forelse($RelativeComposer['widget_data']['relativelist']['relative'] as $key => $value)
						<tr class="row">
							<td class="col-sm-1">
								{{$i}}
							</td>
							<td class="col-sm-2">
								{{$value['relative']['name']}}
							</td>
							<td class="col-sm-2">
								{{$relationship[strtolower($value['relationship'])]}}
							</td>
							<td class="col-sm-5">
								@foreach($value['relative']['contacts'] as $key2 => $value2)
									{{ucwords($value2['item'])}} : {{$value2['value']}} <br/>
								@endforeach
							</td>
							<td class="text-right col-sm-2">
								@if((int)Session::get('user.menuid')<=2)
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.person.relatives.delete', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id']]) }}"><i class="fa fa-trash"></i></a>
								@endif
								@if((int)Session::get('user.menuid')<=3)
									<a href="{{route('hr.person.relatives.edit', [$value['id'], 'org_id' => $data['id'], 'person_id' => $person['id'], 'employee' => false])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
								@endif
							</td>
						</tr>
						<?php $i++;?>
					@empty 
						<tr>
							<td class="text-center" colspan="5">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
			</table>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$RelativeComposer['widget_data']['relativelist']['relative-display']['from']!!} - {!!$RelativeComposer['widget_data']['relativelist']['relative-display']['to']!!}</p>
					{!!$RelativeComposer['widget_data']['relativelist']['relative-pagination']->appends(Input::all())->render()!!}
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