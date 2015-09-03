@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_body')
	<div class="modal fade" id="{{ (isset($modal) ? $modal : 'delete') }}" tabindex="-1" role="dialog" aria-labelledby="Hapus" aria-hidden="true">
		<div class="modal-dialog form">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-xl " id="formModalLabel">Tambah Widget Organisasi</h4>
				</div>
				<div class="modal-body" style="background-color:#f5f5f5">
					<div class="row">
						<div class="col-lg-12">
							<h4 class="text-primary">Perhatian</h4>
							<article class="margin-bottom-xxl">
								<p class="opacity-75">
									Apakah Anda yakin akan menghapus data? Silahkan masukkan password Anda untuk konfirmasi.
								</p>
							</article>
						</div>
					</div>
					<div class="row mt-20 mb-20">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="password" >Tipe</label>
								<input type="text" name="type" id="password" class="form-control" placeholder="" autofocus>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Nama</label>
								<input type="text" name="name" class="form-control" />
							</div>
						</div>
					</div>
					<div class="row mt-20 mb-20">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Data</label>
								<input type="text" name="data" class="form-control">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="">Range Data</label>
								<select name="range" id="" class="form-control">
									<option value="">1 Hari</option>
									<option value="">1 Bulan</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer bg-grey">
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary">Tambah</button>
				</div>
			</div>
		</div>
	</div>
@overwrite