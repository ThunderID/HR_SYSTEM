<div class="clearfix">&nbsp;</div>
<!-- Checbox flag ignore -->
{!! Form::checkbox('flag_ignore_contact', '1', '') !!} 
<span class="label-automatic-generate">Lewati</span>
<div class="clearfix">&nbsp;</div>

<?php $tabindex = 1; ?>
@for ($x=0; $x<=2; $x++)
	<div class="form-group">				
		<label class="control-label text-capitalize">{{ $ContactComposer['widget_data']['contactlist']['value'][$x] }}</label>
		{!!Form::input('text', $ContactComposer['widget_data']['contactlist']['value'][$x], '', ['class' => 'form-control', 'tabindex' => $tabindex])!!}				
		<?php $tabindex++; ?>
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