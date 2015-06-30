@extends('page_templates.page_template')

@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' 	=> 	[
							['name' => '404', 'route' => '']
						]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',		
		'widget_options'		=> []
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')
	@include('widgets.common.error', [
		'error_code'		=> '403',
		'error_msg'			=> 'Forbidden access',
	])
@overwrite	

@section('content_footer')
@overwrite