@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')		
		<div class="clearfix">&nbsp;</div>
			<div class="form-group">				
				<label class="control-label">Item</label>				
				{!!Form::input('text', 'item[]', $ContactComposer['widget_data']['contactlist']['contact']['item'], ['class' => 'form-control select2-tag-contact', 'style' => 'width:100%', 'tabindex' => '1'])!!}				
			</div>
			<div class="form-group">				
				<label class="control-label">Kontak</label>				
				{!!Form::input('text', 'value[]', $ContactComposer['widget_data']['contactlist']['contact']['value'], ['class' => 'form-control val-contact', 'tabindex' => '2'])!!}				
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						{!!Form::checkbox('is_default[]', '1', $ContactComposer['widget_data']['contactlist']['contact']['is_default'], ['class' => '', 'tabindex' => '3'])!!} Aktif
					</label>
				</div>				
			</div>
			@if (isset($ContactComposer['widget_data']['contactlist']['multiple']))
				<div id="duplicate_contact"></div>
				<div class="form-group">
					<a href="javascript:;" class="btn btn-default btn_duplicate_add_contact">Tambah Kontak</a>
				</div>
			@endif
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif