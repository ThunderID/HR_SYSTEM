@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))
@if (!$widget_error_count)
	@section('widget_body')
		@if(isset($WorkleaveComposer['widget_data']['workleavelist']['workleave']))
			@if(Session::get('user.menuid') <= 3)
				<div class="alert alert-callout alert-danger no-margin">
					<select name="workleave_id" class="form-control select2 person_workleave_widget">
						@if($wleave=='0')
							<option value="0" selected>{{$wleave['quota']}}</option>
						@else
							@foreach($WorkleaveComposer['widget_data']['workleavelist']['workleave'] as $key => $value)
								<option value="{{$value['id']}}" @if($wleave==$value['id']) selected @endif>{{$value['quota']}}</option>
							@endforeach
						@endif
					</select>
					{!!Form::hidden('work_id', $wleave['work_id'])!!}
					<span class="opacity-50 mt-10">{!! $widget_title  or 'Kuota Cuti "'.$data['name'].'" Tahun Ini' !!} </span>					
				</div>
			@else
				<div class="alert alert-callout alert-danger no-margin">
					<strong class="pull-right text-info text-lg"><i class="fa fa-bed fa-2x"></i></strong>
					<strong class="text-xl">{{$WorkleaveComposer['widget_data']['wleave']['quota']}}
					</strong><br>
					<span class="opacity-50">{!! $widget_title  or 'Kuota Cuti "'.$data['name'].'" Tahun Ini' !!} </span>					
				</div>
			@endif
		@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif