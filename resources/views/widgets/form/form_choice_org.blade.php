@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	HR System Login
@overwrite

@section('widget_body')
	{!! Form::open(['url' => $widget_data['form_url'], 'method' => 'post']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Organisasi</label>
			</div>	
			<div class="col-md-10">
				<select name="c_org" id="org" class="select2-skin" style="width:100%">
					<option value="">Khiels A</option>
					<option value="">Khiels B</option>
					<option value="">Khiels C</option>
					<option value="">Khiels 1</option>
					<option value="">Khiels 2</option>
					<option value="">Khiels 3</option>
					<option value="">Khiels 4</option>
					<option value="">Khiels 5</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<input type="submit" class="btn btn-primary" value="Pilih">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	