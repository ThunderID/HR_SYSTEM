<script>
	/* Modal */
	$('.delete').on('show.bs.modal', function(e) {
		var action 	= $(e.relatedTarget).attr('data-delete-action');		
		$(this).parent().attr('action', action);
	});	

	/* Modal Schedule Branch */
	$('.modal_schedule_branch').on('show.bs.modal', function(e) {
		var id 				= $(e.relatedTarget).attr('data-id');
		var title 			= $(e.relatedTarget).attr('data-title');
		var date_start 		= $(e.relatedTarget).attr('data-date');
		var date_end 		= $(e.relatedTarget).attr('data-date');
		var start 			= $(e.relatedTarget).attr('data-start');
		var end 			= $(e.relatedTarget).attr('data-end');
		var status 			= $(e.relatedTarget).attr('data-status');
		var edit 			= $(e.relatedTarget).attr('data-edit-action'); 
		var del 			= $(e.relatedTarget).attr('data-delete-action');

		if (id != 0) 
		{
			$('.modal_schedule_id').val(id);
			$('.schedule_branch_label').val(title);
			$('.schedule_branch_on').val(date_start);			
			$('.schedule_branch_start').val(start);
			$('.schedule_branch_end').val(end);
			$('.schedule_branch_status').val(status);
			$('.schedule_branch_delete').attr('href', del);
			$(this).parent().attr('action', edit);
			$(this).find('.modal_schedule_btn_save').text('Edit');
		}
		else
		{
			$('.modal_schedule_id').val(null);
			$('.schedule_branch_label').val('');
			$('.schedule_branch_on').val('');			
			$('.schedule_branch_start').val('');
			$('.schedule_branch_end').val('');
			$('.schedule_branch_status').val('');
			$(this).find('.modal_schedule_btn_save').text('Tambah');
		}		
	});
</script>