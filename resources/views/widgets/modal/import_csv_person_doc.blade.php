@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_body')
	<div class="modal fade import_csv_person" id="import_csv_person" tabindex="-1" role="dialog" aria-labelledby="Import CSV" aria-hidden="true">
		<div class="modal-dialog form">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-xl " id="formModalLabel">Import CSV Person Dokumen</h4>
				</div>
				<div class="modal-body" style="background-color:#f5f5f5">
					<div class="row mt-20 mb-20">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Browse CSV</label>
								<input type="file" name="file_csv">
								<input type="hidden" name="import" value="yes">
								<span id="helpBlock" class="help-block font-12">* Masukkan dalam bentuk .csv</span>
							</div>
						</div>
					</div>						
				</div>
				<div class="modal-footer bg-grey">
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary">Import</button>
				</div>
			</div>
		</div>
	</div>
@overwrite