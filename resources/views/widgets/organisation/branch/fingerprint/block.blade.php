@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {!! $widget_title or 'Sidik Jari Hari Ini' !!} </h1>
	@overwrite

	@section('widget_body')
		@if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_thumb'])
			<h4><i class="fa fa-unlock"></i> &nbsp; Jari Jempol Kiri</h4>
		@endif

		@if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_index_finger'])
			<h4><i class="fa fa-unlock"></i> &nbsp; Jari Telunjuk Kiri</h4>
		@endif

		@if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_middle_finger'])
			<h4><i class="fa fa-unlock"></i> &nbsp; Jari Tengah Kiri</h4>
		@endif

		@if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_ring_finger'])
			<h4><i class="fa fa-unlock"></i> &nbsp; Jari Manis Kiri</h4>
		@endif

		@if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_little_finger'])
			<h4><i class="fa fa-unlock"></i> &nbsp; Jari Kelingking Kiri</h4>
		@endif

		@if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_thumb'])
			<h4><i class="fa fa-unlock"></i> &nbsp; Jari Jempol Kanan</h4>
		@endif

		@if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_index_finger'])
			<h4><i class="fa fa-unlock"></i> &nbsp; Jari Telunjuk Kanan</h4>
		@endif

		@if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_middle_finger'])
			<h4><i class="fa fa-unlock"></i> &nbsp; Jari Tengah Kanan</h4>
		@endif

		@if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_ring_finger'])
			<h4><i class="fa fa-unlock"></i> &nbsp; Jari Manis Kanan</h4>
		@endif

		@if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_little_finger'])
			<h4><i class="fa fa-unlock"></i> &nbsp; Jari Kelingking Kanan</h4>
		@endif
		<div class="clearfix">&nbsp;</div>
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif