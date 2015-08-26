@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')	
		<h1> {{ is_null($id) ? 'Tambah ' : 'Ubah '}} Pengaturan Kebijakan </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $PolicyComposer['widget_data']['policylist']['form_url'], 'class' => 'form no-enter']) !!}	
			<!-- password reminder -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">
						<label class="mt-5">Pengingat Password</label>
						{!! Form::input('hidden', 'type', 'passwordreminder') !!}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">						
						<select name="passwordreminder" class="form-control">
							@for ($i=1; $i<=12; $i++)
								@if ($i==1)
									<option value="- {{$i}} month" @if(strpos($p['passwordreminder'], $i." month")) selected @endif>{{$i}} Bulan</option>
								@else
									<option value="- {{$i}} months" @if(strpos($p['passwordreminder'], $i." months")) selected @endif>{{$i}} Bulan</option>
								@endif
							@endfor
						</select>									
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="mt-5">Sekali</label>
					</div>
				</div>
			</div>
			<!-- assplimit  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="mt-5">Batas AS mendapat SP</label>				
						{!! Form::input('hidden', 'type', 'Assplimit') !!}				
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						{!! Form::input('number', 'assplimit', $p['assplimit'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0', 'max' => '366']) !!}					
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="mt-5">Pelanggaran</label>
					</div>
				</div>
			</div>
			
			<!-- ulsplimit  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="mt-5">Batas UL mendapat SP</label>				
						{!! Form::input('hidden', 'type', 'ulsplimit') !!}				
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">				
						{!! Form::input('number', 'ulsplimit', $p['ulsplimit'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0', 'max' => '366']) !!}					
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="mt-5">Pelanggaran</label>
					</div>
				</div>
			</div>
			<!-- hpsplimit  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="mt-5">Batas HP mendapat SP</label>				
						{!! Form::input('hidden', 'type', 'hpsplimit') !!}				
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">				
						{!! Form::input('number', 'hpsplimit', $p['hpsplimit'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0', 'max' => '366']) !!}					
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="mt-5">Pelanggaran</label>
					</div>
				</div>
			</div>
			<!-- htsplimit  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="mt-5">Batas HT mendapat SP</label>				
						{!! Form::input('hidden', 'type', 'htsplimit') !!}				
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">				
						{!! Form::input('number', 'htsplimit', $p['htsplimit'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0', 'max' => '366']) !!}					
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="mt-5">Pelanggaran</label>
					</div>
				</div>
			</div>
			<!-- hcsplimit  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="mt-5">Batas HC mendapat SP</label>				
						{!! Form::input('hidden', 'type', 'hcsplimit') !!}				
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">				
						{!! Form::input('number', 'hcsplimit', $p['hcsplimit'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0', 'max' => '366']) !!}					
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label class="mt-5">Pelanggaran</label>
					</div>
				</div>
			</div>
			<!-- firststatussettlement  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">Kunci Pertama</label>				
						{!! Form::input('hidden', 'type', 'firststatussettlement') !!}				
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="firststatussettlement_year" class="form-control">
							<option value="0">---------------------------</option>
							<option value="- 1 year" @if(strpos($p['firststatussettlement'], "1 year")) selected @endif>1 Tahun</option>
							<option value="- 2 years" @if(strpos($p['firststatussettlement'], "2 years")) selected @endif>2 Tahun</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="firststatussettlement_month" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=11; $i++)
								@if ($i==1)
									<option value="- {{$i}} month" @if(strpos($p['firststatussettlement'], $i." month")) selected @endif>{{$i}} Bulan</option>
								@else
									<option value="- {{$i}} months" @if(strpos($p['firststatussettlement'], $i." months")) selected @endif>{{$i}} Bulan</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="firststatussettlement_day" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=30; $i++)
								@if ($i==1)
									<option value="- {{$i}} day" @if(strpos($p['firststatussettlement'], $i." day")) selected @endif>{{$i}} Hari</option>
								@else
									<option value="- {{$i}} days" @if(strpos($p['firststatussettlement'], $i." days")) selected @endif>{{$i}} Hari</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group ">
						<label class="mt-5">Sekali</label>
					</div>
				</div>
			</div>
			<!-- secondstatussettlement  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">Kunci Kedua</label>				
						{!! Form::input('hidden', 'type', 'secondstatussettlement') !!}				
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="secondstatussettlement_year" class="form-control">
							<option value="0">---------------------------</option>
							<option value="- 1 year" @if(strpos($p['secondstatussettlement'], "1 year")) selected @endif>1 Tahun</option>
							<option value="- 2 years" @if(strpos($p['secondstatussettlement'], "2 years")) selected @endif>2 Tahun</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="secondstatussettlement_month" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=11; $i++)
								@if ($i==1)
									<option value="- {{$i}} month" @if(strpos($p['secondstatussettlement'], $i." month")) selected @endif>{{$i}} Bulan</option>
								@else
									<option value="- {{$i}} months" @if(strpos($p['secondstatussettlement'], $i." months")) selected @endif>{{$i}} Bulan</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="secondstatussettlement_day" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=30; $i++)
								@if ($i==1)
									<option value="- {{$i}} day" @if(strpos($p['secondstatussettlement'], $i." day")) selected @endif>{{$i}} Hari</option>
								@else
									<option value="- {{$i}} days" @if(strpos($p['secondstatussettlement'], $i." days")) selected @endif>{{$i}} Hari</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group ">
						<label class="mt-5">Sekali</label>
					</div>
				</div>
			</div>
			<!-- firstidle  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">Batas Idle pertama</label>				
						{!! Form::input('hidden', 'type', 'firstidle') !!}				
					</div>
				</div>
				<?php 
					$fi 			= gmdate('H:i:s', $p['firstidle']);
					list($fih, $fim, $fis) = explode(":", $fi);
				?>
				<div class="col-sm-3">
					<div class="form-group">
						<select name="firstidle_hour" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=24; $i++ )
								@if ($i==1)
									<option value="{{$i}}" @if($fih==$i) selected @endif>{{$i}} Jam</option>
								@else	
									<option value="{{$i}}" @if($fih==$i) selected @endif>{{$i}} Jam</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<select name="firstidle_minute" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=60; $i++)
								@if ($i==1)
									<option value="{{$i}}"  @if($fim==$i) selected @endif>{{$i}} Menit</option>
								@else
									<option value="{{$i}}"  @if($fim==$i) selected @endif>{{$i}} Menit</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
			</div>
			<!-- secondidle  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">Batas Idle Kedua</label>				
						{!! Form::input('hidden', 'type', 'secondidle') !!}				
					</div>
				</div>
				<?php 
					$si 			= gmdate('H:i:s', $p['secondidle']);
					list($sih, $sim, $sis) = explode(":", $si);
				?>
				<div class="col-sm-3">
					<div class="form-group">
						<select name="secondidle_hour" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=24; $i++ )
								@if ($i==1)
									<option value="{{$i}}" @if($sih==$i) selected @endif>{{$i}} Jam</option>
								@else	
									<option value="{{$i}}" @if($sih==$i) selected @endif>{{$i}} Jam</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<select name="secondidle_minute" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=60; $i++)
								@if ($i==1)
									<option value="{{$i}}"  @if($sim==$i) selected @endif>{{$i}} Menit</option>
								@else
									<option value="{{$i}}"  @if($sim==$i) selected @endif>{{$i}} Menit</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
			</div>
			<!-- thirdidle  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">Batas Idle Ketiga</label>				
						{!! Form::input('hidden', 'type', 'thirdidle') !!}				
					</div>
				</div>
				<?php 
					$ti 			= gmdate('H:i:s', $p['thirdidle']);
					list($tih, $tim, $tis) = explode(":", $ti);
				?>
				<div class="col-sm-3">
					<div class="form-group">
						<select name="thirdidle_hour" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=24; $i++ )
								@if ($i==1)
									<option value="{{$i}}" @if($tih==$i) selected @endif>{{$i}} Jam</option>
								@else	
									<option value="{{$i}}" @if($tih==$i) selected @endif>{{$i}} Jam</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<select name="thirdidle_minute" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=60; $i++)
								@if ($i==1)
									<option value="{{$i}}"  @if($tim==$i) selected @endif>{{$i}} Menit</option>
								@else
									<option value="{{$i}}"  @if($tim==$i) selected @endif>{{$i}} Menit</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
			</div>
			<!-- extendsworkleave  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">Perpanjangan Cuti</label>				
						{!! Form::input('hidden', 'type', 'extendsworkleave') !!}				
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="extendsworkleave_year" class="form-control">
							<option value="0">---------------------------</option>
							<option value="+ 1 year" @if(strpos($p['extendsworkleave'], "1 year")) selected @endif>1 Tahun</option>
							<option value="+ 2 years" @if(strpos($p['extendsworkleave'], "2 years")) selected @endif>2 Tahun</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="extendsworkleave_month" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=11; $i++)
								@if ($i==1)
									<option value="+ {{$i}} month" @if(strpos($p['extendsworkleave'], $i." month")) selected @endif>{{$i}} Bulan</option>
								@else
									<option value="+ {{$i}} months" @if(strpos($p['extendsworkleave'], $i." months")) selected @endif>{{$i}} Bulan</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="extendsworkleave_day" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=30; $i++)
								@if ($i==1)
									<option value="+ {{$i}} day" @if(strpos($p['extendsworkleave'], $i." day")) selected @endif>{{$i}} Hari</option>
								@else
									<option value="+ {{$i}} days" @if(strpos($p['extendsworkleave'], $i." days")) selected @endif>{{$i}} Hari</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
			</div>
			<!-- extendsmidworkleave  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">Perpanjangan Cuti (tengah tahun)</label>				
						{!! Form::input('hidden', 'type', 'extendsmidworkleave') !!}				
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="extendsmidworkleave_year" class="form-control">
							<option value="0">---------------------------</option>
							<option value="+ 1 year" @if(strpos($p['extendsmidworkleave'], "1 year")) selected @endif>1 Tahun</option>
							<option value="+ 2 years" @if(strpos($p['extendsmidworkleave'], "2 years")) selected @endif>2 Tahun</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="extendsmidworkleave_month" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=11; $i++)
								@if ($i==1)
									<option value="+ {{$i}} month" @if(strpos($p['extendsmidworkleave'], $i." month")) selected @endif>{{$i}} Bulan</option>
								@else
									<option value="+ {{$i}} months" @if(strpos($p['extendsmidworkleave'], $i." months")) selected @endif>{{$i}} Bulan</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="extendsmidworkleave_day" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=30; $i++)
								@if ($i==1)
									<option value="+ {{$i}} day" @if(strpos($p['extendsmidworkleave'], $i." day")) selected @endif>{{$i}} Hari</option>
								@else
									<option value="+ {{$i}} days" @if(strpos($p['extendsmidworkleave'], $i." days")) selected @endif>{{$i}} Hari</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
			</div>
			<!-- firstacleditor  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">Kunci interferensi level 1</label>				
						{!! Form::input('hidden', 'type', 'firstacleditor') !!}				
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="firstacleditor_year" class="form-control">
							<option value="0">---------------------------</option>
							<option value="- 1 year" @if(strpos($p['firstacleditor'], "1 year")) selected @endif>1 Tahun</option>
							<option value="- 2 years" @if(strpos($p['firstacleditor'], "2 years")) selected @endif>2 Tahun</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="firstacleditor_month" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=11; $i++)
								@if ($i==1)
									<option value="- {{$i}} month" @if(strpos($p['firstacleditor'], $i." month")) selected @endif>{{$i}} Bulan</option>
								@else
									<option value="- {{$i}} months" @if(strpos($p['firstacleditor'], $i." months")) selected @endif>{{$i}} Bulan</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="firstacleditor_day" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=30; $i++)
								@if ($i==1)
									<option value="- {{$i}} day" @if(strpos($p['firstacleditor'], $i." day")) selected @endif>{{$i}} Hari</option>
								@else
									<option value="- {{$i}} days" @if(strpos($p['firstacleditor'], $i." days")) selected @endif>{{$i}} Hari</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
			</div>
			<!-- secondacleditor  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">Kunci interferensi level 2</label>				
						{!! Form::input('hidden', 'type', 'secondacleditor') !!}				
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="secondacleditor_year" class="form-control">
							<option value="0">---------------------------</option>
							<option value="- 1 year" @if(strpos($p['secondacleditor'], "1 year")) selected @endif>1 Tahun</option>
							<option value="- 2 years" @if(strpos($p['secondacleditor'], "2 years")) selected @endif>2 Tahun</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="secondacleditor_month" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=11; $i++)
								@if ($i==1)
									<option value="- {{$i}} month" @if(strpos($p['secondacleditor'], $i." month")) selected @endif>{{$i}} Bulan</option>
								@else
									<option value="- {{$i}} months" @if(strpos($p['secondacleditor'], $i." months")) selected @endif>{{$i}} Bulan</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="secondacleditor_day" class="form-control">
							<option value="0">---------------------------</option>
							@for ($i=1; $i<=30; $i++)
								@if ($i==1)
									<option value="- {{$i}} day" @if(strpos($p['secondacleditor'], $i." day")) selected @endif>{{$i}} Hari</option>
								@else
									<option value="- {{$i}} days" @if(strpos($p['secondacleditor'], $i." days")) selected @endif>{{$i}} Hari</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
			</div>
			<!-- asid  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">ID Dokumen SP - AS (1,2,3)</label>				
						{!! Form::input('hidden', 'type', 'asid') !!}				
					</div>
				</div>
				<?php $asid = explode(',', $p['asid']);?>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'asid_sp1', $asid[0], ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'asid_sp2', $asid[1], ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'asid_sp3', $asid[2], ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>

			<!-- ulid  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">ID Dokumen SP - UL (1,2,3)</label>				
						{!! Form::input('hidden', 'type', 'ulid') !!}				
					</div>
				</div>
				<?php $ulid = explode(',', $p['ulid']);?>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'ulid_sp1', $ulid[0], ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'ulid_sp2', $ulid[1], ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'ulid_sp3', $ulid[2], ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>

			<!-- hcid  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">ID Dokumen SP - HC (1,2,3)</label>				
						{!! Form::input('hidden', 'type', 'hcid') !!}				
					</div>
				</div>
				<?php $hcid = explode(',', $p['hcid']);?>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'hcid_sp1', $hcid[0], ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'hcid_sp2', $hcid[1], ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'hcid_sp3', $hcid[2], ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>

			<!-- htid  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">ID Dokumen SP - HT (1,2,3)</label>				
						{!! Form::input('hidden', 'type', 'htid') !!}				
					</div>
				</div>
				<?php $htid = explode(',', $p['htid']);?>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'htid_sp1', $htid[0], ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'htid_sp2', $htid[1], ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'htid_sp3', $htid[2], ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>
			
			<!-- hpid  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">ID Dokumen SP - HP (1,2,3)</label>				
						{!! Form::input('hidden', 'type', 'hpid') !!}				
					</div>
				</div>
				<?php $hpid = explode(',', $p['hpid']);?>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'hpid_sp1', $hpid[0], ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'hpid_sp2', $hpid[1], ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::input('number', 'hpid_sp3', $hpid[2], ['class' => 'form-control']) !!}
					</div>
				</div>
			</div>

			<div class="form-group text-right">				
				<a href="{{ $PolicyComposer['widget_data']['policylist']['route_edit'] }}" class="btn btn-default mr-5" tabindex="5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="4">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif