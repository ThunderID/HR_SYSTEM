{!! HTML::script('plugins/jquery-input-mask/jquery.inputmask.bundle.min.js') !!}

<script type="text/javascript">
	$('.date-mask').inputmask("dd-mm-yyyy");
	$('.time-mask').inputmask('h:s', {placeholder: 'hh:mm'});
</script>