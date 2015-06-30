@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($PersonComposer['widget_data']['lossratelist']['person']))
			<div class="alert alert-callout alert-danger no-margin">
				<strong class="pull-right text-danger text-lg"><i class="fa fa-users fa-2x"></i></strong>
				<?php 
					$lossrate = 0;
					$totalemployee = 0;
				?>
				@foreach($PersonComposer['widget_data']['lossratelist']['person'] as $key => $value)
					<?php 
						$tlr = ($value['total_absence']!=0 ? $value['total_absence'] : 1) / ($value['possible_total_effective']!=0 ? $value['possible_total_effective'] : 1);
						$lossrate = $lossrate + $tlr;
						$totalemployee = $totalemployee + 1;
					?>
				@endforeach
				<strong class="text-xl">{{ abs(round($lossrate / ($totalemployee != 0 ? $totalemployee : 1) * 100, 2)) }} % </strong><br>
				<span class="opacity-50">{!! $widget_title  or 'Rerata Time Loss "'.$data['name'].'" Bulan Ini' !!} </span>					
			</div>
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif