@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {!! $widget_title  or 'Cabang' !!} </h1>
	<small>Total data {{$BranchComposer['widget_data']['branchlist']['branch-pagination']->total()}}</small>
	<?php $BranchComposer['widget_data']['branchlist']['branch-pagination']->setPath(route('hr.branches.index')); ;?>

	<div class="clearfix">&nbsp;</div>
	@if(!is_null($BranchComposer['widget_data']['branchlist']['active_filter']))
		@foreach($BranchComposer['widget_data']['branchlist']['active_filter'] as $key => $value)
			<span class="active-filter">{{$value}}</span>
		@endforeach
	@endif
	@overwrite

	@section('widget_body')
		<a href="{{ $BranchComposer['widget_data']['branchlist']['route_create'] }}" class="btn btn-primary">Tambah Data</a>
		@if(isset($BranchComposer['widget_data']['branchlist']['branch']))
			<div class="clearfix">&nbsp;</div>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama</th>
							<th>Nomor Telepon</th>
							<th>Alamat</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<?php $i = $BranchComposer['widget_data']['branchlist']['branch-display']['from'];?>
					@foreach($BranchComposer['widget_data']['branchlist']['branch'] as $key => $value)
						<tbody>
							<tr>
								<td>
									{{$i}}
								</td>
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
									<a href="javascript:;" class="btn btn-default" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.branches.delete', [$value['id'], 'org_id' => $data['id']]) }}" title="hapus"><i class="fa fa-trash"></i></a>
									<a href="{{route('hr.branches.edit', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $value['id']])}}" class="btn btn-default" title="ubah"><i class="fa fa-pencil"></i></a>
									<a href="{{route('hr.branches.show', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $value['id']])}}" class="btn btn-default" title="lihat"><i class="fa fa-eye"></i></a>
									<br/>
									<a href="{{route('hr.branch.charts.index', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $value['id']])}}" class="btn btn-default" title="struktur organisasi"><i class="fa fa-sitemap"></i></a>
									<a href="{{route('hr.branch.contacts.index', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $value['id']])}}" class="btn btn-default" title="kontak"><i class="fa fa-phone"></i></a>
									<a href="{{route('hr.branch.apis.index', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $value['id']])}}" class="btn btn-default" title="pengaturan api key"><i class="fa fa-key"></i></a>
									<!-- <a href="{{route('hr.branch.fingers.index', [$value['id'], 'org_id' => $data['id'], 'branch_id' => $value['id']])}}" class="btn btn-default"><i class="fa fa-eye"></i></a> -->
								</td>
							</tr>
						</tbody>
						<?php $i++;?>
					@endforeach
				</table>
			</div>

			<div class="row">
				<div class="col-sm-12 text-center">
					@if($BranchComposer['widget_data']['branchlist']['branch-pagination']->total()>0)
						<p>Menampilkan {!!$BranchComposer['widget_data']['branchlist']['branch-display']['from']!!} - {!!$BranchComposer['widget_data']['branchlist']['branch-display']['to']!!}</p>
						{!!$BranchComposer['widget_data']['branchlist']['branch-pagination']->appends(Input::all())->render()!!}
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