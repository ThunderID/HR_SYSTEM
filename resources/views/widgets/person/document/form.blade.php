@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> {{$PersonDocumentComposer['widget_data']['documentlist']['document']['document']['name']}} </h1>
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $PersonDocumentComposer['widget_data']['documentlist']['form_url'], 'class' => 'form-horizontal']) !!}	
		@foreach($PersonDocumentComposer['widget_data']['documentlist']['document']['details'] as $key => $value)
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">{{$value['template']['field']}}</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', $value['template_id'], $value['text'], ['class' => 'form-control'])!!}
				</div>
			</div>
		@endforeach
		<div class="form-group">
			<div class="col-md-12 text-right">
				<a href="{{ $PersonDocumentComposer['widget_data']['documentlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	