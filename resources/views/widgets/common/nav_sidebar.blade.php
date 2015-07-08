@extends('widget_templates.'. (isset($widget_template) ? $widget_template : 'plain_no_title'))

@section('widget_title')
@overwrite

@section('widget_info')
@overwrite

@section('widget_body')
    <ul class="nav in" id="side-menu" style="margin-top:11px">
        <li @if(isset($widget_options['sidebar']['active_form'])&&($widget_options['sidebar']['active_form']=='active_create_org')) class="active-li" @endif>
            <a href="{{route('hr.organisations.create')}}"><i class="fa fa-plus-circle fa-fw"></i> Tambah Organisasi</a>
        </li>
        @if(Session::has('user.organisationids'))
            @foreach(Session::get('user.organisationids') as $key => $value)
                <?php switch(strtolower(Session::get('user.menuid')))
                    {
                    	case 1:
                    		?> @include('widgets.common.nav_bar_1') <?php
                    	break;
                    	case 2:
                    		?> @include('widgets.common.nav_bar_2') <?php
                    	break;

                    	default :
                    		?> @include('widgets.common.nav_bar_3') <?php
                    	break;
                    }
                ;?>
                
            @endforeach
        @endif
    </ul>
@overwrite