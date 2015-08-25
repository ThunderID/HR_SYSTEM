@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))
<?php 
	$type 				= ['passwordreminder' => 'Pengingat Password', 'assplimit' => 'Batas AS mendapat SP', 'ulsplimit' => 'Batas UL mendapat SP', 'htsplimit' => 'Batas HT mendapat SP', 'hpsplimit' => 'Batas HP mendapat SP', 'hcsplimit' => 'Batas HC mendapat SP', 'firststatussettlement' => 'Kunci Pertama', 'secondstatussettlement' => 'Kunci Kedua', 'firstidle' => 'Batas Idle pertama', 'secondidle' => 'Batas Idle kedua', 'thirdidle' => 'Batas Idle ketiga', 'extendsworkleave' => 'Perpanjangan Cuti', 'extendsmidworkleave' => 'Perpanjangan Cuti (tengah tahun)', 'firstacleditor' => 'Kunci interferensi level 1', 'secondacleditor' => 'Kunci interferensi level 2'];
	$patterns[] = '/years/';
	$patterns[] = '/months/';
	$patterns[] = '/days/';
	$patterns[] = '/year/';
	$patterns[] = '/month/';
	$patterns[] = '/day/';
	$patterns[] = '/-/';

	$replaces[] = 'tahun';
	$replaces[] = 'bulan';
	$replaces[] = 'hari';
	$replaces[] = 'tahun';
	$replaces[] = 'bulan';
	$replaces[] = 'hari';
	$replaces[] = '';
?>
@if (!$widget_error_count)
	<?php
		$PolicyComposer['widget_data']['policylist']['policy-pagination']->setPath(route('hr.policies.index'));
	?>

	@section('widget_title')
		<h1> {!! $widget_title or 'Kebijakan' !!} </h1>
		<small>Total data {{$PolicyComposer['widget_data']['policylist']['policy-pagination']->total()}}</small>
		@if(isset($PolicyComposer['widget_data']['policylist']['active_filter']) && !is_null($PolicyComposer['widget_data']['policylist']['active_filter']))
			 <div class="clearfix">&nbsp;</div>
			@foreach($PolicyComposer['widget_data']['policylist']['active_filter'] as $key => $value)
				<span class="active-filter">{{$value}}</span>
			@endforeach
		@endif
	@overwrite

	@section('widget_body')
		@if((int)Session::get('user.menuid')==1)
			<a href="{{ $PolicyComposer['widget_data']['policylist']['route_create'] }}" class="btn btn-primary">Ubah</a>
		@endif
		@if(isset($PolicyComposer['widget_data']['policylist']['policy']))
			<div class="clearfix">&nbsp;</div>			
			<table class="table table-hover table-affix">
				<thead>
					<tr>
						<th>No</th>
						<th colspan="2">Kebijakan</th>
						<th>Sejak</th>
						<th>Ditetapkan Oleh</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = $PolicyComposer['widget_data']['policylist']['policy-display']['from'];?>
					@forelse($PolicyComposer['widget_data']['policylist']['policy'] as $key => $value)
						<tr @if($i<=15) class="active" @endif>
							<td>
								{{$i}}
							</td>
							<td>
								{{ (isset($type[strtolower($value['type'])]) ? $type[strtolower($value['type'])] : $value['type']) }}
							</td>
							<td>
								<?php $str = preg_replace($patterns, $replaces, $value['value']);?>
								<?php $val = str_replace('+', '', $str);?>
								@if(in_array(strtolower($value['type']),['firstidle','secondidle','thirdidle']))
									{{ gmdate('H:i', $val) }}
								@elseif(in_array(strtolower($value['type']),['assplimit','ulsplimit','htsplimit','hpsplimit','hcsplimit']))
									{{ $val }} x Pelanggaran / SP
								@else
									{{ $val }} Sekali
								@endif
							</td>
							<td>
								{{ date('d-m-Y', strtotime($value['started_at'])) }}
							</td>
							<td>
								{{ $value['createdby']['name'] }} 
							</td>
						</tr>
						<?php $i++;?>
					@empty 
						<tr>
							<td class="text-center" colspan="7">Tidak ada data</td>
						</tr>
					@endforelse
				</tbody>
			</table>

			<div class="row">
				<div class="col-sm-12 text-center">
					<p>Menampilkan {!!$PolicyComposer['widget_data']['policylist']['policy-display']['from']!!} - {!!$PolicyComposer['widget_data']['policylist']['policy-display']['to']!!}</p>
					{!!$PolicyComposer['widget_data']['policylist']['policy-pagination']->appends(Input::all())->render()!!}
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