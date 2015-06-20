{!! HTML::style('plugins/summernote/summernote.css')!!}	
{!! HTML::script('plugins/summernote/summernote.min.js')!!}	

<script>
	/*------------------Original summernote----------------------*/
	$('.summernote').summernote();

	$('.summernote-document').summernote({
		height: 350,
		toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear', 'fontsize']],
			['para', ['ul', 'ol', 'paragraph']]
		]
	});
</script>