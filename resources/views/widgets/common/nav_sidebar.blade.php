
@extends('widget_templates.'. (isset($widget_template) ? $widget_template : 'plain_no_title'))

@section('widget_title')
@overwrite

@section('widget_info')
@overwrite

@section('widget_body')
    <ul class="nav" id="side-menu">
        <li>
            <a href="{{route('hr.organisations.create')}}"><i class="fa fa-plus-circle fa-fw"></i> Tambah Organisasi</a>
        </li>
        @if(isset($OrganisationComposer['widget_data']['sidebar']['organisation']))
            @foreach($OrganisationComposer['widget_data']['sidebar']['organisation'] as $key => $value)
                <li>
                    <a href="{{route('hr.organisations.show', $value['id'])}}"><i class="fa fa-bank fa-fw"></i> {{ $value['name'] }} <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        {{-- <li><a href="{{route('hr.organisations.show', $value['id'])}}"><i class="fa fa-eye fa-fw"></i> Show</a></li> --}}
                        <li><a href="{{route('hr.organisations.edit', $value['id'])}}"><i class="fa fa-pencil fa-fw"></i> Ubah</a></li>
                        <li><a href="javascript:;" data-toggle="modal" data-target="#delete" data-delete-action="{{ route('hr.organisations.delete', [$value['id'], 'org_id' => $value['id']]) }}"><i class="fa fa-trash"></i> Hapus</a>
                        <li><a href="{{route('hr.organisations.show', [$value['id'], 'org_id' => $value['id']])}}"><i class="fa fa-tachometer fa-fw"></i> Dashboard</a></li>
                        <li>
                            <a href="javascript"><i class="fa fa-cog"></i> Pengaturan <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li>
                                    @if ((!isset($value['branches']))|(!Input::get('branch_id')))
                                        <a href="{{route('hr.branches.index', ['org_id' => $value['id']])}}"><i class="fa fa-building fa-fw"></i> Cabang</a>
                                    @else
                                        <a href="{{route('hr.branches.index', ['org_id' => $value['id']])}}"><i class="fa fa-building fa-fw"></i> Cabang <span class="fa arrow"></span></a>
                                        <ul class="nav nav-fourty-level">
                                            <li><a href="{{route('hr.branches.index', ['org_id' => $value['id']])}}">Semua Cabang</a></li>
                                            @foreach($value['branches'] as $branch)
                                                <li>
                                                    <a href="">{{ $branch['name'] }} <span class="fa arrow"></span></a>
                                                    <ul class="nav nav-fifty-level">
                                                        <li>
                                                            <a href="{{ route('hr.branch.contacts.index', ['org_id' => $value['id'], 'branch_id' => $branch['id']]) }}">Contact</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('hr.branch.charts.index', ['org_id' => $value['id'], 'branch_id' => $branch['id']]) }}">Chart</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('hr.branch.apis.index', ['org_id' => $value['id'], 'branch_id' => $branch['id']]) }}">Api</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('hr.branch.fingers.index', ['org_id' => $value['id'], 'branch_id' => $branch['id']]) }}">Finger</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                                <li>
                                    <a href="{{route('hr.calendars.index', ['org_id' => $value['id']])}}"><i class="fa fa-calendar fa-fw"></i> Kalender</a>
                                </li>
                                <li>
                                    <a href="{{route('hr.workleaves.index', ['org_id' => $value['id']])}}"><i class="fa fa-calendar-o fa-fw"></i> Template Cuti</a>
                                </li>
                                <li>
                                    <a href="{{route('hr.documents.index', ['org_id' => $value['id']])}}"><i class="fa fa-archive fa-fw"></i> Template Dokumen</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;"><i class="fa fa-archive"></i> Data<span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li>
                                    <a href="{{route('hr.persons.index', ['org_id' => $value['id']])}}"><i class="fa fa-users fa-fw"></i> Data Karyawan</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{route('hr.reports.index', ['org_id' => $value['id']])}}"><i class="fa fa-file-text-o fa-fw"></i> Laporan</a>
                        </li>
                    </ul>
                </li>
            @endforeach
        @endif
    </ul>

    {!! Form::open(array('route' => array('hr.organisations.delete', 0),'method' => 'DELETE')) !!}
        @include('widgets.modal.delete', [
            'widget_template'       => 'plain_no_title'
        ])
    {!! Form::close() !!}
@overwrite