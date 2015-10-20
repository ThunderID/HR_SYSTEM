@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title') 
		<h1> {{ is_null($id) ? 'Tambah Otentikasi ' : 'Ubah Otentikasi '}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
			<?php 
				if(Session::get('user.menuid') > 1)
				{
					$status 		= ['permanent', 'contract', 'probation', 'internship', 'permanent', 'others'];
				}
				else
				{
					$status 		= ['permanent', 'contract', 'probation', 'internship', 'permanent', 'others', 'admin'];
				}
			?>
			{!! Form::open(['url' => $WorkAuthenticationComposer['widget_data']['workauthlist']['form_url'], 'class' => 'form no_enter']) !!}	
				<div class="form-group">
					<label class="control-label">Karyawan</label>
					@include('widgets.organisation.person.work.select', [
					'widget_options'		=> 	[
													'worklist'			=>
													[
														'search'			=> ['status' => $status, 'active' => true, 'organisationid' => Session::get('user.organisationids'),'withattributes' => ['person', 'person.organisation'], 'groupperson' => true],
														'sort'				=> ['end' => 'asc'],
														'page'				=> 1,
														'per_page'			=> 100,
														'work_id'			=> $WorkAuthenticationComposer['widget_data']['workauthlist']['workauth']['work_id'],
														'tabindex'			=> 1,
													]
												]
					])
				</div>
				<div class="form-group">
					<label class="control-label">Otentikasi</label>
					@include('widgets.authgroup.select', [
					'widget_options'		=> 	[
													'authgrouplist'			=>
													[
														'search'			=> ['level' => Session::get('user.menuid')],
														'sort'				=> ['id' => 'asc'],
														'page'				=> 1,
														'per_page'			=> 100,
														'tmp_auth_group_id'	=> $WorkAuthenticationComposer['widget_data']['workauthlist']['workauth']['tmp_auth_group_id'],
														'tabindex'			=> 2,
													]
												]
					])
				</div>
				<div class="form-group">
					<div class="col-md-12 text-right">
						<a href="{{ $WorkAuthenticationComposer['widget_data']['workauthlist']['route_back'] }}" class="btn btn-default mr-5" tabindex="4">Batal</a>
						<input type="submit" class="btn btn-primary" value="Simpan" tabindex="3">
					</div>
				</div>
			{!! Form::close() !!}
		</div>
	@overwrite
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif