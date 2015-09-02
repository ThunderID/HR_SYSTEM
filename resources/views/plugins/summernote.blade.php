{!! HTML::style('plugins/summernote/summernote.css')!!}	
{!! HTML::script('plugins/summernote/summernote.js')!!}	

<script>
	/*------------------Original summernote----------------------*/
	$('.summernote').summernote();

	$('.summernote-document').summernote({
		height: 350,
		toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['para', ['ul', 'ol', 'paragraph']]
		]
	});
</script>