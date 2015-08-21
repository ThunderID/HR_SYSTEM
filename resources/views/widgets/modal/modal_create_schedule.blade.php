@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_body')
	<div class="modal fade modal_schedule" id="modal_schedule" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true">
		<div class="modal-dialog form-horizontal">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-xl schedule_title" id="formModalLabel">Edit Jadwal</h4>
				</div>
				<div class="modal-body" style="background-color:#f5f5f5">
					<div class="row">
						<div class="col-lg-12">
							<h4 class="text-primary">Petunjuk</h4>
							<article class="margin-bottom-xxl">
								<p class="opacity-75">
									Start merupakan jam masuk, dan end merupakan jam pulang.
								</p>
							</article>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 p-30">
							<div class="form-group pt-20">						
								<label class="control-label">Label</label>						
								<input type="text" name="name" class="form-control schedule_label">						
							</div>
							<div class="form-group">						
								<label class="control-label">Status</label>
								<select name="status" class="form-control schedule_status">
									<option value="HB">Hadir</option>
									<option value="L">Libur</option>
									@if(Input::has('person_id'))
										<!-- <option value="CI">Cuti Istimewa</option> -->
										<option value="CN">Cuti Pribadi</option>
										<option value="UL">Cuti Tidak Dibayar</option>
										<option value="SS">Sakit (dalam waktu pendek)</option>
										<option value="SL">Sakit (dalam waktu panjang)</option>
										<option value="DN">Dinas Luar</option>
									@else
										<option value="CB">Cuti Bersama</option>
									@endif
								</select>						
							</div>
							{{-- In microtemplate.blade.php --}}
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" class="is_range" > Range Tanggal
									</label>
								</div>	
							</div>
							<div class="row date_range">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label">Tanggal</label>						
										<input type="text" name="on" class="form-control date-mask schedule_on">		
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-5">
									<div class="form-group">
										<label class="control-label">Start</label>
										<input type="text" name="start" class="form-control time-mask schedule_start">
									</div>
								</div>
								<div class="col-sm-2"></div>
								<div class="col-sm-5">
									<div class="form-group">
										<label class="control-label">End</label>
										<input type="text" name="end" class="form-control time-mask schedule_end">
									</div>
								</div>
							</div>
							@if(isset($calendar) && (count($calendar['childs']) || count($calendar['parent']['childs'])))
								<div class="form-group">
									<div class="checkbox">
										<label>
											{!!Form::checkbox('affect', '1', '', ['class' => '', 'tabindex' => '6'])!!} Perubahan Pada Kalender ini akan mempengaruhi : 
										</label>
									</div>
								</div>
								
								@if($calendar['parent'])
									{{$calendar['parent']['name']}}
								@endif
								
								@foreach($calendar['parent']['childs'] as $key => $value)
									@if($value['id']!=$calendar['id'])
										{{$value['name']}} <br/> 
									@endif
								@endforeach

								@foreach($calendar['childs'] as $key => $value)
									{{$value['name']}} <br/> 
								@endforeach
							@endif
						</div>
					</div>
				</div>
				<div class="modal-footer bg-grey">
					<a href="javascript:;" class="btn btn-danger pull-left schedule_delete" data-toggle="modal" data-target="#delete" data-delete-action="">Hapus</a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</div>
		</div>
	</div>
@overwrite
