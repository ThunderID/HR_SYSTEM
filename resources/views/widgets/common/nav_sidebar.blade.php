@extends('widget_templates.'. (isset($widget_template) ? $widget_template : 'plain_no_title'))

@section('widget_title')
@overwrite

@section('widget_info')
@overwrite

@section('widget_body')
    <ul class="nav in sidemenu" id="side-menu" style="margin-top:37px">
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
                        case 3:
                            ?> @include('widgets.common.nav_bar_3') <?php
                        break;
                    	default :
                    		?> @include('widgets.common.nav_bar_4') <?php
                    	break;
                    }
                ;?>
                
            @endforeach
        @endif
    </ul>
@overwrite