@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> Tambah Data Karyawan</h1> 
			
		<div class="text-right">
		<a href="{{ route('hr.persons.create', ['org_id' => $data['id'], 'import' => 'yes']) }}" class="btn btn-primary text-right">Import CSV</a>	</div>
	@overwrite

	@section('widget_body')
		{!! Form::open(['url' => $PersonComposer['widget_data']['personlist']['form_url'], 'class' => 'form no_enter', 'files' => true]) !!}				
			<div class="clearfix">&nbsp;</div>
			<ul class="nav nav-tabs setup-panel" role="tablist">
				<li class="wizard active"><a href="#step-1" aria-controls="step-1" role="tab" data-toggle="tab" type="button">1. Profil</a></li>
			    <li class="wizard disabled"><a href="#step-2" aria-controls="step-2" disabled="disabled" role="tab" data-toggle="tab" type="button">2. Kontak</a></li>
			    <li class="wizard disabled"><a href="#step-3" aria-controls="step-3" disabled="disabled" role="tab" data-toggle="tab" type="button">3. Dokumen</a></li>
			  </ul>

			  <!-- Tab panes -->
			<div class="tab-content">
			    <div role="tabpanel" class="tab-pane active setup-content" id="step-1">
			    	@include('widgets.organisation.person.form_no_title', [			    		
			    		'widget_options'		=> 	[
			    										'personlist'			=>
			    										[
			    											'form_url'			=> null,
			    											'organisation_id'	=> $data['id'],
			    											'search'			=> ['id' => $id],
			    											'sort'				=> [],
			    											'new'				=> (is_null($id) ? true : false),
			    											'page'				=> 1,
			    											'per_page'			=> 1,
			    											'route_back'		=> $PersonComposer['widget_data']['personlist']['route_back']
			    										]
			    									]
			    	])
			    </div>
			    <div role="tabpanel" class="tab-pane setup-content" id="step-2">
			    	@include('widgets.common.contact.form_no_title', [			    		
			    		'widget_options'	=> 	[
			    									'contactlist'			=>
			    									[
			    										'form_url'			=> null,
			    										'organisation_id'	=> $data['id'],
			    										'search'			=> ['id' => $id ,'withattributes' => ['person']],
			    										'sort'				=> [],
			    										'new'				=> (is_null($id) ? true : false),
			    										'page'				=> 1,
			    										'per_page'			=> 1,
			    										'value'				=> ['alamat', 'email', 'mobile']
			    									]
			    								]
			    	])
			    </div>

			    <div role="tabpanel" class="tab-pane setup-content" id="step-3">
			    	@include('widgets.common.persondocument.form_no_title', [			    		
			    		'widget_options'	=> 	[
			    									'documentlist'			=>
													[
														'form_url'			=> route('hr.person.documents.store', ['id' => $id, 'doc_id' => Input::get('doc_id'), 'org_id' => $data['id']]),
														'search'			=> ['id' => ['1', '3', '4', '5'] , 'withattributes' => ['templates']],
														'organisation_id'	=> $data['id'],
														'sort'				=> [],
														'new'				=> true,
														'page'				=> 1,
														'per_page'			=> 10
													]
			    								]
			    	])
			    </div>
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif