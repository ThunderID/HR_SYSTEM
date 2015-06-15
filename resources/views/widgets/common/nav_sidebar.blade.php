@extends('widget_templates.'. (isset($widget_template) ? $widget_template : 'plain_no_title'))

@section('widget_title')
    {{ $widget_title or 'Structure' }}
@overwrite

@section('widget_info')
@overwrite

@section('widget_body')
    <ul class="nav" id="side-menu">
        <li>
            <a href="{{route('hr.organisations.create')}}"><i class="fa fa-plus-circle fa-fw"></i> Tambah Organisasi</a>
        </li>
        @if(isset($widget_data['data']))
            @foreach($widget_data['data'] as $key => $value)
                <li>
                    <a href=""><i class="fa fa-bank fa-fw"></i> {{ $value['name'] }} <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{route('hr.organisations.edit', $value['id'])}}"><i class="fa fa-pencil fa-fw"></i> Ubah</a></li>
                        <li><a href="{{route('hr.organisations.delete', $value['id'])}}"><i class="fa fa-trash fa-fw"></i> Hapus</a></li>
                        <li><a href="{{route('hr.organisations.show', $value['id'])}}"><i class="fa fa-tachometer fa-fw"></i> Dashboard</a></li>
                        <li><a href="{{route('hr.branches.index', ['org_id' => $value['id']])}}"><i class="fa fa-building fa-fw"></i> Cabang</a></li>
                        <li><a href="{{route('hr.calendars.index', ['org_id' => $value['id']])}}"><i class="fa fa-calendar fa-fw"></i> Kalender</a></li>
                        <li><a href="{{route('hr.workleaves.index', ['org_id' => $value['id']])}}"><i class="fa fa-calendar-o fa-fw"></i> Cuti</a></li>
                        <li><a href="{{route('hr.persons.index', ['org_id' => $value['id']])}}"><i class="fa fa-users fa-fw"></i> Karyawan</a></li>
                        <li><a href="{{route('hr.reports.index', ['org_id' => $value['id']])}}"><i class="fa fa-file-text-o fa-fw"></i> Laporan</a></li>
                    </ul>
                </li>                
            @endforeach
        @endif
    </ul>
@overwrite