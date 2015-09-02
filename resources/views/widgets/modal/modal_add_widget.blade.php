@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_body')
	<div class="modal fade {{ isset($class_id) ? $class_id : '' }}" id="{{ isset($class_id) ? $class_id : '' }}" tabindex="-1" role="dialog" aria-labelledby="Import CSV" aria-hidden="true">
		<div class="modal-dialog form">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-xl " id="formModalLabel">Tambah Widget</h4>
				</div>
				<div class="modal-body" style="background-color:#f5f5f5">
					<div class="row">
						<div class="col-lg-12">
							<h4 class="text-primary">Petunjuk</h4>
							<article class="margin-bottom-xxl">
								<p class="opacity-75">
									Silahkan menambah widget sesuai pilihan anda.
								</p>
							</article>
						</div>
					</div>
					<div class="row mt-20">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Tipe</label>
								{!! Form::input('text', '', '', ['class' => 'form-control']) !!}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Nama</label>
								{!! Form::input('text', '', '', ['class' => 'form-control']) !!}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Data</label>
								{!! Form::input('text', '', '', ['class' => 'form-control']) !!}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="">Periode</label>
								{!! Form::input('text', '', '', ['class' => 'form-control']) !!}
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