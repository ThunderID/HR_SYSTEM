@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_body')
		{!! Form::open(['url' => '', 'class' => 'form-horizontal pt-15 form_filter hide']) !!}
			<div class="form-group">
				<div class="col-xs-11 colsm-11 col-md-11">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
						<input type="text" class="form-control">
					</div>
				</div>
				<div class="col-xs-1 col-sm-1 colcol-md-1">
					<a href="" class="btn btn-default"><i class="fa fa-plus"></i></a>
				</div>
			</div>
			<div class="form-group">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="btn-group">
						<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							Filter &nbsp;<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="">filter</a></li>
							<li><a href="">filter</a></li>
							<li><a href="">filter</a></li>
						</ul>
					</div>
					<div class="btn-group ml-10">
						<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							Value &nbsp;<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="">value</a></li>
							<li><a href="">value</a></li>
							<li><a href="">value</a></li>
						</ul>
					</div>
				</div>
			</div>
		{!! Form::close() !!}
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif