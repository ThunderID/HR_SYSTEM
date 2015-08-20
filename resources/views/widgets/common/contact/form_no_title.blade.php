<div class="clearfix">&nbsp;</div>
<?php $tabindex = 1; ?>
@for ($x=0; $x<=2; $x++)
	<div class="form-group">				
		<label class="control-label">Item</label>				
		{!!Form::input('text', 'item[]', $ContactComposer['widget_data']['contactlist']['value'][$x], ['class' => 'form-control select2-tag-contact', 'style' => 'width:100%', 'tabindex' => $tabindex])!!}				
		<?php $tabindex++; ?>
	</div>
	<div class="form-group">				
		<label class="control-label">Kontak</label>				
		{!!Form::input('text', 'value[]', $ContactComposer['widget_data']['contactlist']['contact']['value'], ['class' => 'form-control val-contact', 'tabindex' => $tabindex])!!}
		<?php $tabindex++; ?>
	</div>
	<div class="form-group mb-25">
		<div class="checkbox">
			<label>
				{!!Form::checkbox('is_default[]', '1', $ContactComposer['widget_data']['contactlist']['contact']['is_default'], ['class' => '', 'tabindex' => $tabindex])!!} Aktif
				<?php $tabindex++; ?>
			</label>
		</div>				
	</div>
@endfor
@if (isset($ContactComposer['widget_data']['contactlist']['multiple']))
	<div id="duplicate_contact"></div>
	<div class="form-group">
		<a href="javascript:;" class="btn btn-default btn_duplicate_add_contact">Tambah Kontak</a>
	</div>
@endif

<div class="row">
	<div class="col-sm-12 text-right">
		<button class="btn btn-primary nextBtn pull-right" type="button" tabindex="{{ $tabindex }}">Lanjut</button>
		<?php $tabindex++; ?>
		<button class="btn btn-primary prevBtn pull-right mr-10" type="button" tabindex="{{ $tabindex }}">Kembali</button>
	</div>
</div>