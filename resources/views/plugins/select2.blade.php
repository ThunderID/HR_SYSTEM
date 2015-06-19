{!! HTML::style('plugins/select2/select2.css') !!}
{!! HTML::script('plugins/select2/select2.3.5.min.js') !!}

<script type="text/javascript">
	$(document).ready(function(){


	// ---------------------------- BASIC SELECT2 ----------------------------
	$('.select2').select2();

	// ---------------------------- SELECT2 SKINs ----------------------------
	
	$('.select2-tag-contact').select2({
		tokenSeparators: [",", " ", "_", "-"],
		tags: 'true',
		placeholder: ""
	});

	// ---------------------------- SELECT2 ARTICLE ----------------------------
	// function formatArticle (repo) 
	// {
	// 	if (repo.loading) return repo.text;

	// 	var markup = '<div class="clearfix">' +
	// 					'<div class="col-sm-1">' +
	// 						'<img src="' + repo.thumbnail + '" style="max-width: 100%" />' +
	// 					'</div>' +
	// 					'<div clas="col-sm-10">' +
	// 						'<div class="col-sm-10">' + repo.title + '</div>' +
	// 					'</div>' + 
	// 				'</div>';
	// 	markup += '</div>';

	// 	return markup;
	// }

	// function formatArticleSelection (repo) 
	// {
	// 	return repo.title;
	// }
	});	
</script>