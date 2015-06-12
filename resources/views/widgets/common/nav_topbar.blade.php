@extends('widget_templates.'. (isset($widget_template) ? $widget_template : 'plain_no_title'))

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
		<a class="btn btn-default btn-back">Kembali</a>
		<span class="breadcrumb">
			<a class="" href="">halo</a> <i class="fa fa-angle-double-right"></i>
			<a href="">sekarang</a>	<i class="fa fa-angle-double-right"></i>
			<a href="">lalu</a> <i class="fa fa-angle-double-right"></i>
			nanti
		</span>
	</div>
	 
	<ul class="nav navbar-top-links navbar-right">
		<li>
			<a href=""><i class="fa fa-search"></i></a>
		</li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="fa fa-user fa-fw"></i> Admin &nbsp;&nbsp; <i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu dropdown-user">
				<li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
				<li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a></li>
				<li class="divider"></li>
				<li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
			</ul>	            
		</li>	        
	</ul>		 
@overwrite