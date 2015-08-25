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
						<select name="value" class="form-control">
							@for ($i=1; $i<=12; $i++)
								@if ($i==1)
									<option value="- {{$i}} month">{{$i}} Bulan</option>	
								@else
									<option value="- {{$i}} months">{{$i}} Bulan</option>
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
						{!! Form::input('number', 'value', $PolicyComposer['widget_data']['policylist']['policy']['value'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0']) !!}					
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
						{!! Form::input('number', 'value', $PolicyComposer['widget_data']['policylist']['policy']['value'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0']) !!}					
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
						{!! Form::input('number', 'value', $PolicyComposer['widget_data']['policylist']['policy']['value'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0']) !!}					
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
						{!! Form::input('number', 'value', $PolicyComposer['widget_data']['policylist']['policy']['value'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0']) !!}					
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
						{!! Form::input('number', 'value', $PolicyComposer['widget_data']['policylist']['policy']['value'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0']) !!}					
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
							<option value="">---------------------------</option>
							<option value="- 1 year">1 Tahun</option>
							<option value="- 2 years">2 Tahun</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="firststatussettlement_month" class="form-control">
							<option value="">---------------------------</option>
							@for ($i=1; $i<=11; $i++)
								@if ($i==1)
									<option value="- {{$i}} month">{{$i}} Bulan</option>
								@else
									<option value="- {{$i}} months">{{$i}} Bulan</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="firststatussettlement_day" class="form-control">
							@for ($i=1; $i<=30; $i++)
								@if ($i==1)
									<option value="- {{$i}} day">{{$i}} Hari</option>
								@else
									<option value="- {{$i}} days">{{$i}} Hari</option>
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
							<option value="">---------------------------</option>
							<option value="- 1 year">1 Tahun</option>
							<option value="- 2 years">2 Tahun</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="secondstatussettlement_month" class="form-control">
							<option value="">---------------------------</option>
							@for ($i=1; $i<=11; $i++)
								@if ($i==1)
									<option value="- {{$i}} month">{{$i}} Bulan</option>
								@else
									<option value="- {{$i}} months">{{$i}} Bulan</option>
								@endif
							@endfor
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select name="secondstatussettlement_day" class="form-control">
							@for ($i=1; $i<=30; $i++)
								@if ($i==1)
									<option value="- {{$i}} day">{{$i}} Hari</option>
								@else
									<option value="- {{$i}} days">{{$i}} Hari</option>
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
			<!-- firstidle  -->
			<div class="row mb-10">
				<div class="col-sm-3">
					<div class="form-group">				
						<label class="control-label">Batas Idle pertama</label>				
						{!! Form::input('hidden', 'type', 'firstidle') !!}				
					</div>
				</div>
				<div class="col-sm-9">
					<div class="row">
						<div class="col-sm-10">
							<div class="form-group">
								{!! Form::input('number', 'value', $PolicyComposer['widget_data']['policylist']['policy']['value'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0']) !!}
							</div>
						</div>
						<div class="col-sm-2">
							<label class="mt-5">Menit</label>
						</div>
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
				<div class="col-sm-9">
					<div class="row">
						<div class="col-sm-10">
							<div class="form-group">
								{!! Form::input('number', 'value', $PolicyComposer['widget_data']['policylist']['policy']['value'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0']) !!}
							</div>
						</div>
						<div class="col-sm-2">
							<label class="">Menit</label>
						</div>
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
				<div class="col-sm-9">
					<div class="row">
						<div class="col-sm-10">
							<div class="form-group">				
								{!! Form::input('number', 'value', $PolicyComposer['widget_data']['policylist']['policy']['value'], ['class' => 'form-control', 'placeholder' => 'Value', 'tabindex' => '1', 'min' => '0']) !!}
							</div>
						</div>
						<div class="col-sm-2">
							<label class="mt-5">Menit</label>
						</div>
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
				<div class="col-sm-9">
					<div class="row">
						<div class="col-sm-2">
							<div class="form-group ">
								<label class="mt-5">Lebih dari</label>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="secondacleditor_year" class="form-control">
									<option value="">---------------------------</option>
									<option value="+ 1 year">1 Tahun</option>
									<option value="+ 2 years">2 Tahun</option>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="secondacleditor_month" class="form-control">
									<option value="">---------------------------</option>
									@for ($i=1; $i<=11; $i++)
										@if ($i==1)
											<option value="+ {{$i}} month">{{$i}} Bulan</option>
										@else
											<option value="+ {{$i}} months">{{$i}} Bulan</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="secondacleditor_day" class="form-control">
									@for ($i=1; $i<=30; $i++)
										@if ($i==1)
											<option value="+ {{$i}} day">{{$i}} Hari</option>
										@else
											<option value="+ {{$i}} days">{{$i}} Hari</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
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
				<div class="col-sm-9">
					<div class="row">
						<div class="col-sm-2 ">
							<label class="mt-5">Lebih dari</label>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="secondacleditor_year" class="form-control">
									<option value="">---------------------------</option>
									<option value="+ 1 year">1 Tahun</option>
									<option value="+ 2 years">2 Tahun</option>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="secondacleditor_month" class="form-control">
									<option value="">---------------------------</option>
									@for ($i=1; $i<=11; $i++)
										@if ($i==1)
											<option value="+ {{$i}} month">{{$i}} Bulan</option>
										@else
											<option value="+ {{$i}} months">{{$i}} Bulan</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="secondacleditor_day" class="form-control">
									@for ($i=1; $i<=30; $i++)
										@if ($i==1)
											<option value="+ {{$i}} day">{{$i}} Hari</option>
										@else
											<option value="+ {{$i}} days">{{$i}} Hari</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
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
				<div class="col-sm-9">
					<div class="row">
						<div class="col-sm-2 ">
							<label class="mt-5">Kurang dari</label>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="firstacleditor_year" class="form-control">
									<option value="">---------------------------</option>
									<option value="- 1 year">1 Tahun</option>
									<option value="- 2 years">2 Tahun</option>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="firstacleditor_month" class="form-control">
									<option value="">---------------------------</option>
									@for ($i=1; $i<=11; $i++)
										@if ($i==1)
											<option value="- {{$i}} month">{{$i}} Bulan</option>
										@else
											<option value="- {{$i}} months">{{$i}} Bulan</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="firstacleditor_day" class="form-control">
									@for ($i=1; $i<=30; $i++)
										@if ($i==1)
											<option value="- {{$i}} day">{{$i}} Hari</option>
										@else
											<option value="- {{$i}} days">{{$i}} Hari</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
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
				<div class="col-sm-9">
					<div class="row">
						<div class="col-sm-2 ">
							<label class="mt-5">Kurang dari</label>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="secondacleditor_year" class="form-control">
									<option value="">---------------------------</option>
									<option value="- 1 year">1 Tahun</option>
									<option value="- 2 years">2 Tahun</option>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="secondacleditor_month" class="form-control">
									<option value="">---------------------------</option>
									@for ($i=1; $i<=11; $i++)
										@if ($i==1)
											<option value="- {{$i}} month">{{$i}} Bulan</option>
										@else
											<option value="- {{$i}} months">{{$i}} Bulan</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<select name="secondacleditor_day" class="form-control">
									@for ($i=1; $i<=30; $i++)
										@if ($i==1)
											<option value="- {{$i}} day">{{$i}} Hari</option>
										@else
											<option value="- {{$i}} days">{{$i}} Hari</option>
										@endif
									@endfor
								</select>
							</div>
						</div>
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