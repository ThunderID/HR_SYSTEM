@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
	<h1> {{ $widget_title or 'Otentikasi' }} </h1>
@overwrite

@section('widget_body')

	<div class="clearfix">&nbsp;</div>	
	<table class="table table-bordered">
		<thead>
			<tr>
				<th  class="text-center" rowspan="2" width="15%" style="vertical-align:middle">Aplikasi</th>
				<th  class="text-center" rowspan="2" width="15%" style="vertical-align:middle">Menu</th>
				<th  class="text-center" colspan="4">Otentikasi</th>
			</tr>
			<tr>
				<th  class="text-center">Tambah</th>
				<th  class="text-center">Akses</th>
				<th  class="text-center">Ubah</th>
				<th  class="text-center">Hapus</th>
			</tr>
		</thead>
		<tbody>
			<?php $prev = null;?>
			<form class="check" action="" method="post">					
				@foreach($ApplicationComposer['widget_data']['applicationlist']['application'] as $key => $value)
					@foreach($value['menus'] as $key2 => $value2)
						<tr>
							@if($prev != $value2['application_id'])
							<td rowspan="{{count($value['menus'])}}">
								{{ucwords($value['name'])}}
							</td>
							@endif
							<td>
								{{$value2['name']}}
							</td>
							<td class="text-center">							
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" 
										data-checked-action="
											@if(isset($value2['authentications'][0]['is_create'])) {{route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => $value2['authentications'][0]['id'], 'wrong' => 'is_create', 'org_id' => $data['id']])}} 
											@else {{route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => null, 'wrong' => 'is_create', 'org_id' => $data['id']])}} 
											@endif" 
										data-unchecked-action="
											@if(isset($value2['authentications'][0]['is_create'])){{ route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => $value2['authentications'][0]['id'], 'right' => 'is_create', 'org_id' => $data['id']]) }}
											@else{{ route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'right' => 'is_create', 'org_id' => $data['id']]) }}@endif" 
										@if(isset($value2['authentications'][0]['is_create']) && $value2['authentications'][0]['is_create']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" 
										data-checked-action="
											@if(isset($value2['authentications'][0]['is_read'])) {{route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => $value2['authentications'][0]['id'], 'wrong' => 'is_read', 'org_id' => $data['id']])}} 
											@else {{route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => null, 'wrong' => 'is_read', 'org_id' => $data['id']])}} 
											@endif" 
										data-unchecked-action="
											@if(isset($value2['authentications'][0]['is_read'])){{ route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => $value2['authentications'][0]['id'], 'right' => 'is_read', 'org_id' => $data['id']]) }}
											@else{{ route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'right' => 'is_read', 'org_id' => $data['id']]) }}@endif" 
										@if(isset($value2['authentications'][0]['is_read']) && $value2['authentications'][0]['is_read']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" 
										data-checked-action="
											@if(isset($value2['authentications'][0]['is_update'])) {{route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => $value2['authentications'][0]['id'], 'wrong' => 'is_update', 'org_id' => $data['id']])}} 
											@else {{route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => null, 'wrong' => 'is_update', 'org_id' => $data['id']])}} 
											@endif" 
										data-unchecked-action="
											@if(isset($value2['authentications'][0]['is_update'])){{ route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => $value2['authentications'][0]['id'], 'right' => 'is_update', 'org_id' => $data['id']]) }}
											@else{{ route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'right' => 'is_update', 'org_id' => $data['id']]) }}@endif" 
										@if(isset($value2['authentications'][0]['is_update']) && $value2['authentications'][0]['is_update']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" 
										data-checked-action="
											@if(isset($value2['authentications'][0]['is_delete'])) {{route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => $value2['authentications'][0]['id'], 'wrong' => 'is_delete', 'org_id' => $data['id']])}} 
											@else {{route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => null, 'wrong' => 'is_delete', 'org_id' => $data['id']])}} 
											@endif" 
										data-unchecked-action="
											@if(isset($value2['authentications'][0]['is_delete'])){{ route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'auth_id' => $value2['authentications'][0]['id'], 'right' => 'is_delete', 'org_id' => $data['id']]) }}
											@else{{ route('hr.chart.authentications.store', ['branch_id' => $branch['id'],'chart_id' => $chart['id'], 'menu_id' => $value2['id'], 'right' => 'is_delete', 'org_id' => $data['id']]) }}@endif" 
										@if(isset($value2['authentications'][0]['is_delete']) && $value2['authentications'][0]['is_delete']) checked @endif>
									</label>
								</div>
							</td>
						</tr>
					<?php $prev = $value['id'];?>
					@endforeach
				@endforeach
			</form>
		</tbody>
	</table>
	<div class="clearfix">&nbsp;</div>
@overwrite	
