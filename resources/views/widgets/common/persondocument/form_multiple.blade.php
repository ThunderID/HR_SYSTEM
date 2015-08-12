@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h4 class="pt-10"> {{ ucwords($DocumentComposer['widget_data']['documentlist']['document']['name']) }} </h4> 
	@overwrite

	@section('widget_body')	  
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $DocumentComposer['widget_data']['documentlist']['form_url'], 'class' => 'form no_enter']) !!}	
			@foreach($DocumentComposer['widget_data']['documentlist']['document']['templates'] as $key => $value)
				<div class="form-group">
					<label for="field" class="control-label">{{ucwords($value['field'])}}</label>					
					<?php 
						$content = null;
						$detailid = null;
						if($persondocument['details'])
						{
							foreach ($persondocument['details'] as $key2 => $value2) 
							{
								if($value['id']==$value2['template_id'] && $value2['numeric']!=0)
								{
									$detailid = $value2['id'];
									$content = $value2['numeric'];
								}
								elseif($value['id']==$value2['template_id'])
								{
									$detailid = $value2['id'];
									$content = $value2['text'];
								}
							}
						}

						switch (strtolower($value['type'])) 
						{
							case 'text':
								$form 		= '<textarea name="content[]" value="" class="form-control">'.$content.'</textarea>';
								break;
							case 'date':
								$form 		= '<input type="date" class="form-control date-mask" id="text" name="content[]" value="'.$content.'">';
								break;
							case 'numeric':
								$form 		= '<input type="numeric" class="form-control" id="text" name="content[]" value="'.$content.'">';
								break;
							default:
								$form 		= '<input type="text" class="form-control" id="text" name="content[]" value="'.$content.'">';
								break;
						}
					;?>					
					{!!$form!!}					
					<input type="hidden" class="form-control" id="text" name="template_id[]" value="{{$value['id']}}">
					<input type="hidden" class="form-control" id="text" name="detail_id[]" value="{{$detailid}}">
				</div>
			@endforeach
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif