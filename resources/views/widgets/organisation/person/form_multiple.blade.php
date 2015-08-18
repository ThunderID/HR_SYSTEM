@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {{ is_null($id) ? 'Tambah Data Karyawan' : 'Ubah Data Karyawan "'. $PersonComposer['widget_data']['personlist']['person']['name'].'"'}} </h1> 
		
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
			    		'widget_template'		=> 'plain_no_title',
			    		'widget_options'		=> 	[
			    										'personlist'			=>
			    										[
			    											'form_url'			=> null,
			    											'organisation_id'	=> $data['id'],
			    											'search'			=> ['id' => $id],
			    											'sort'				=> [],
			    											'new'				=> (is_null($id) ? true : false),
			    											'page'				=> 1,
			    											'per_page'			=> 1
			    										]
			    									]
			    	])
			    	<button class="btn btn-primary nextBtn pull-right" type="button" >Lanjut</button>
			    	<a href="{{ $PersonComposer['widget_data']['personlist']['route_back'] }}" class="btn btn-default pull-right mr-10">Batal</a>
			    </div>
			    <div role="tabpanel" class="tab-pane setup-content" id="step-2">
			    	@include('widgets.common.contact.form_no_title', [
			    		'widget_template'	=> 'plain_no_title',
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
			    	<button class="btn btn-primary nextBtn pull-right" type="button" >Lanjut</button>
			    	<button class="btn btn-primary prevBtn pull-right mr-10" type="button" >Kembali</button>
			    </div>

			    <div role="tabpanel" class="tab-pane setup-content" id="step-3">
			    	@include('widgets.common.persondocument.form_no_title', [
			    		'widget_template'	=> 'plain_no_title',
			    		'widget_options'	=> 	[
			    									'documentlist'			=>
													[
														'form_url'			=> route('hr.person.documents.store', ['id' => $id, 'doc_id' => Input::get('doc_id'), 'org_id' => $data['id']]),
														'search'			=> ['id' => ['6', '7'] , 'withattributes' => ['templates']],
														'organisation_id'	=> $data['id'],
														'sort'				=> [],
														'new'				=> true,
														'page'				=> 1,
														'per_page'			=> 2
													]
			    								]
			    	])
					<input type="submit" class="btn btn-primary pull-right" value="Simpan">
					<button class="btn btn-primary prevBtn pull-right mr-10" type="button">Kembali</button>
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