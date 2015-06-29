@extends('widget_templates.'. (isset($widget_template) ? $widget_template : 'plain_no_title'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))

	@section('widget_title')
	@overwrite

	@section('widget_body')
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<span class="breadcrumb">
				<a class="" href="{{route('hr.organisations.index')}}">HR SYSTEM</a>
				@forelse($breadcrumb as $key => $value)
					<i class="fa fa-angle-double-right"></i><a class="" href="{{$value['route']}}">{{$value['name']}}</a> 
				@empty 
				@endforelse 
			</span>
		</div>
		 
		<ul class="nav navbar-top-links navbar-right">
			@if(Session::has('allow.filter') && Session::get('allow.filter')==true)
				<li>
					<a href="javascript:;" class="open-filter"><i class="fa fa-search"></i></a>
				</li>
			@endif
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					{!! HTML::image(Session::get('user.avatar'), '', array( 'width' => 32, 'height' => 32, 'class' => 'img-rounded' )) !!} {{Session::get('user.name')}} &nbsp;&nbsp; <i class="fa fa-caret-down"></i>
				</a>
				<ul class="dropdown-menu dropdown-user">					
					<li><a href="{{route('hr.password.get')}}"><i class="fa fa-gear fa-fw"></i> Ganti Password</a></li>					
					<li><a href="{{route('hr.logout.get')}}"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
				</ul>	            
			</li>	        
		</ul>		 
	@overwrite
@else
	@section('widget_title')
	@overwrite
	@section('widget_body')
	@overwrite
@endif