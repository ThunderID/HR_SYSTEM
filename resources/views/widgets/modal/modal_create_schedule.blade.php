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
					<div class="form-group pt-20">
						<div class="col-md-2">
							<label class="control-label">Label</label>
						</div>	
						<div class="col-md-10">
							<input type="text" name="name" class="form-control schedule_label">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label">Status</label>
						</div>	
						<div class="col-md-10">
							<select name="status" class="schedule_status">
								<option value="presence_indoor">Masuk, Dalam Ruangan</option>
								<option value="presence_outdoor">Masuk, Luar Ruangan</option>
								<option value="absence_not_workleave">Absen, Tidak Mengurangi Cuti</option>
								<option value="absence_workleave" >Absen, Mengurangi Cuti</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label">Tanggal</label>
						</div>	
						<div class="col-md-10">
							<input type="text" name="on" class="form-control date-mask schedule_on">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2">
							<label class="control-label">Start</label>
						</div>	
						<div class="col-md-4">
							<input type="text" name="start" class="form-control time-mask schedule_start">
						</div>
						<div class="col-md-1 col-md-offset-1">
							<label class="control-label">End</label>
						</div>	
						<div class="col-md-4">
							<input type="text" name="end" class="form-control time-mask schedule_end">
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
