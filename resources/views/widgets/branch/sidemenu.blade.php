<ul class="list-unstyled">
	<li class="text-uppercase text-bold font-18">Pengaturan</li>
	<li class="ml-5 mt-10">
		<a href=""><i class="fa fa-calendar-o fa-fw"></i> Kontak</a>
	</li>
	<li class="ml-5 mt-10">
		<a href="{{ route('hr.branch.charts.index', ['org_id' => $data['id'], 'branch_id' => $branch['id']]) }}"><i class="fa fa-calendar fa-fw"></i> Struktur/Jabatan</a>
	</li>
	<li class="ml-5 mt-10">
		<a href="{{ route('hr.branch.apis.index', ['org_id' => $data['id'], 'branch_id' => $branch['id']]) }}"><i class="fa fa-file fa-fw"></i> Api Key</a>
	</li>
	<li class="ml-5 mt-10">
		<a href=""><i class="fa fa-file fa-fw"></i> Absen Jari</a>
	</li>
</ul>