<li @if(Input::has('org_id') && Input::get('org_id')==$value) class="active" @endif>
    <a href="{{route('hr.organisations.show', $value)}}" class="top-level"><i class="fa fa-bank fa-fw"></i> {{ Session::get('user.organisationnames')[$key] }} <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
        <li @if(isset($branch['id'])|(Input::has('branch_id'))|(isset($widget_options['sidebar']['pengaturan'])))class="active" @endif>
            <a href="javascript"><i class="fa fa-cog fa-fw"></i> Pengaturan <span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
               
				<li @if(isset($widget_options['sidebar']['active_calendar'])) class="active-li" @endif>
				    <a href="{{route('hr.calendars.index', ['org_id' => $value])}}" @if(isset($widget_options['sidebar']['active_calendar'])) class="active" @endif><i class="fa fa-calendar fa-fw"></i> Kalender</a>
				</li>
                <li @if(isset($widget_options['sidebar']['active_cuti'])) class="active-li" @endif>
                    <a href="{{route('hr.workleaves.index', ['org_id' => $value])}}" @if(isset($widget_options['sidebar']['active_cuti'])) class="active" @endif><i class="fa fa-calendar-o fa-fw"></i> Template Cuti</a>
                </li>
				<li @if(isset($widget_options['sidebar']['active_document'])) class="active-li" @endif>
				    <a href="{{route('hr.documents.index', ['org_id' => $value])}}" @if(isset($widget_options['sidebar']['active_document'])) class="active" @endif><i class="fa fa-archive fa-fw"></i> Template Dokumen</a>
				</li>
				<li @if(isset($widget_options['sidebar']['active_idle'])) class="active-li" @endif>
				    <a href="{{route('hr.idles.index', ['org_id' => $value])}}" @if(isset($widget_options['sidebar']['active_idle'])) class="active" @endif><i class="fa fa-clock-o fa-fw"></i> Pengaturan Idle</a>
				</li>
            </ul>
        </li>
        <li @if(isset($person['id'])|(Input::get('person_id'))) class="active" @endif>
            <a href="javascript:;"><i class="fa fa-briefcase fa-fw"></i> Data<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
                <li @if(isset($person['id'])|(Input::get('person_id'))) class="active" @endif>
                    <a href="javascript:;"><i class="fa fa-users fa-fw"></i> Data Karyawan <span class="fa arrow"></span></a>
                    <ul class="nav nav-fourty-level">
                        <li @if(isset($widget_options['sidebar']['active_form'])&&($widget_options['sidebar']['active_form']=='active_add_person')) class="active-li" @endif>
                            <a href="{{route('hr.persons.create', ['org_id' => $value, 'person_id' => 0])}}">Tambah Karyawan</a>
                        </li>
                        <li @if(isset($widget_options['sidebar']['active_all_person'])) class="active-li" @endif>
                            <a href="{{route('hr.persons.index', ['org_id' => $value, 'person_id' => 0])}}" @if(isset($widget_options['sidebar']['active_all_person'])) class="active" @endif>Semua Karyawan</a>
                        </li>
                        @if(isset($person['id']))
                            <li @if(isset($person['id'])||(Input::get('person_id'))) class="active" @endif>
                                <a href="javascript:;">{{ $person['name'] }} <span class="fa arrow"></span></a>
                                <ul class="nav nav-fifty-level">
                                    <li @if(isset($widget_options['sidebar']['active_form'])&&($widget_options['sidebar']['active_form']=='active_edit_person')) class="active-li" @endif>
                                        <a href="{{route('hr.persons.edit', [$person['id'], 'org_id' => $value, 'person_id' => $person['id']])}}" @if(isset($widget_options['sidebar']['active_form'])&&($widget_options['sidebar']['active_form']=='active_edit_person')) class="active" @endif>Ubah</a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-target="#deleteorg" data-toggle="modal" data-delete-action="{{ route('hr.persons.delete', [$person['id'], 'org_id' => $value, 'person_id' => $person['id']]) }}">Hapus</a>
                                    </li>
                                    <li @if(isset($widget_options['sidebar']['active_contact_person'])) class="active-li" @endif>
                                        <a href="{{ route('hr.person.contacts.index', ['org_id' => $value, 'person_id' => $person['id']]) }}" @if(isset($widget_options['sidebar']['active_contact_person'])) class="active" @endif>Kontak</a>
                                    </li>    
                                    <li @if(isset($widget_options['sidebar']['active_relative_person'])) class="active-li" @endif>
                                        <a href="{{ route('hr.person.relatives.index', ['org_id' => $value, 'person_id' => $person['id']]) }}" @if(isset($widget_options['sidebar']['active_relative_person'])) class="active" @endif>Kerabat</a>
                                    </li>
                                    <li @if(isset($widget_options['sidebar']['active_work_person'])) class="active-li" @endif>
                                        <a href="{{ route('hr.person.works.index', ['org_id' => $value, 'person_id' => $person['id']]) }}" @if(isset($widget_options['sidebar']['active_work_person'])) class="active" @endif>Pekerjaan</a>
                                    </li>
                                    <li @if(isset($widget_options['sidebar']['active_schedule_person'])) class="active-li" @endif>
                                        <a href="{{ route('hr.person.schedules.index', ['org_id' => $value, 'person_id' => $person['id']]) }}" @if(isset($widget_options['sidebar']['active_schedule_person'])) class="active" @endif>Jadwal</a>
                                    </li>
                                    <li @if(isset($widget_options['sidebar']['active_workleave_person'])) class="active-li" @endif>
                                        <a href="{{ route('hr.person.workleaves.index', ['org_id' => $value, 'person_id' => $person['id']]) }}" @if(isset($widget_options['sidebar']['active_workleave_person'])) class="active" @endif>Jatah Cuti</a>
                                    </li>
                                    <li @if(isset($widget_options['sidebar']['active_document_person'])) class="active-li" @endif>
                                        <a href="{{ route('hr.person.documents.index', ['org_id' => $value, 'person_id' => $person['id']]) }}" @if(isset($widget_options['sidebar']['active_schedule_person'])) class="active" @endif>Dokumen</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;"><i class="fa fa-database"></i> Laporan <span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
                <li>
                    <a href="{{route('hr.report.attendances.index', ['org_id' => $value])}}"><i class="fa fa-file-text-o fa-fw"></i> Laporan Aktivitas</a>
                </li>
                <li>
                    <a href="{{route('hr.report.wages.index', ['org_id' => $value])}}"><i class="fa fa-file-text-o fa-fw"></i> Laporan Kehadiran</a>
                </li>
            </ul>
        </li>
    </ul>
</li>