<script>
	/* Modal */
	$('.delete').on('show.bs.modal', function(e) {
		var action 	= $(e.relatedTarget).attr('data-delete-action');
		$(this).parent().attr('action', action);
	});	
</script>