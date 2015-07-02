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
								<input type="text" name="name" class="form-control schedule_label" tabindex="1">						
							</div>
							<div class="form-group">						
								<label class="control-label">Status</label>
								<select name="status" class="form-control schedule_status" tabindex="2">
									<option value="presence_indoor">Hadir</option>
									<option value="presence_outdoor">Dinas Luar</option>
									<option value="absence_not_workleave">Absen, Tidak Mengurangi Cuti</option>
									<option value="absence_workleave" >Absen, Mengurangi Cuti</option>
								</select>						
							</div>
							<div class="form-group">
								<label class="control-label">Tanggal</label>						
								<input type="text" name="on" class="form-control date-mask schedule_on" tabindex="3">						
							</div>
							<div class="row">
								<div class="col-sm-5">
									<div class="form-group">
										<label class="control-label">Start</label>
										<input type="text" name="start" class="form-control time-mask schedule_start" tabindex="4">
									</div>
								</div>
								<div class="col-sm-2"></div>
								<div class="col-sm-5">
									<div class="form-group">
										<label class="control-label">End</label>
										<input type="text" name="end" class="form-control time-mask schedule_end" tabindex="5">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer bg-grey">
					<a href="javascript:;" class="btn btn-danger pull-left schedule_delete" data-toggle="modal" data-target="#delete" data-delete-action="" tabindex="8">Hapus</a>
					<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="7">Batal</button>
					<button type="submit" class="btn btn-primary" tabindex="6">Simpan</button>
				</div>
			</div>
		</div>
	</div>
@overwrite
