@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Cabang </h1>
<small>Total data {{$BranchComposer['widget_data']['branchlist']['branch-pagination']->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($BranchComposer['widget_data']['branchlist']['branch']))
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
			@foreach($BranchComposer['widget_data']['branchlist']['branch'] as $key => $value)
				<tbody>
					<tr>
						<td>
							{{$value['name']}}
						</td>
						<td>
							@foreach($value['contacts'] as $key2 => $value2)
								@if((strtolower($value2['item'])=='phone')||(strtolower($value2['item'])=='mobile'))
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
							<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.branches.delete', [$value['id'], 'org_id' => $data['id']]) }}"><i class="fa fa-trash"></i></a>
							<a href="{{route('hr.branches.edit', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
							<a href="{{route('hr.branches.show', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a>
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$BranchComposer['widget_data']['branchlist']['branch-display']['from']!!} - {!!$BranchComposer['widget_data']['branchlist']['branch-display']['to']!!}</p>
				{!!$BranchComposer['widget_data']['branchlist']['branch-pagination']->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite	
