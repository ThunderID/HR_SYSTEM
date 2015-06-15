@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Cabang </h1>
<small>Total data {{$widget_data['branch-pagination-'.$widget_data['identifier']]->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($widget_data['branch-'.$widget_data['identifier']]))
		<div class="clearfix">&nbsp;</div>
		<table class="table">
			<thead>
				<tr>
					<th>Nama</th>
					<th>Nomor Telepon</th>
					<th>Alamat</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			@foreach($widget_data['branch-'.$widget_data['identifier']] as $key => $value)
				<tbody>
					<tr>
						<td>
							{{$value['name']}}
						</td>
						<td>
							@foreach($value['contacts'] as $key2 => $value2)
								@if(strtolower($value2['item'])=='phone')
									{{$value2['value']}}
								@endif
							@endforeach
						</td>
						<td>
							@foreach($value['contacts'] as $key2 => $value2)
								@if(strtolower($value2['item'])=='address')
									{{$value2['value']}}
								@endif
							@endforeach
						</td>
						<td class="text-right">
							<a href="" class="btn btn-default"><i class="fa fa-trash"></i></a>
							<a href="{{route('hr.branches.edit', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							<a href="" class="btn btn-default"><i class="fa fa-eye"></i></a>
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$widget_data['branch-display-'.$widget_data['identifier']]['from']!!} - {!!$widget_data['branch-display-'.$widget_data['identifier']]['to']!!}</p>
				{!!$widget_data['branch-pagination-'.$widget_data['identifier']]->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite	
