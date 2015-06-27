<script>
	/* Modal */
	$('.delete').on('show.bs.modal', function(e) {
		var action 	= $(e.relatedTarget).attr('data-delete-action');		
		$(this).parent().attr('action', action);
		$('input-delete').focus();
	});	

	/* Modal Schedule Branch */
	$('.modal_schedule').on('show.bs.modal', function(e) {
		var id 				= $(e.relatedTarget).attr('data-id');
		var title 			= $(e.relatedTarget).attr('data-title');
		var date_start 		= $(e.relatedTarget).attr('data-date');
		var date_end 		= $(e.relatedTarget).attr('data-date');
		var start 			= $(e.relatedTarget).attr('data-start');
		var end 			= $(e.relatedTarget).attr('data-end');
		var status 			= $(e.relatedTarget).attr('data-status');
		var add				= $(e.relatedTarget).attr('data-add-action');
		var edit 			= $(e.relatedTarget).attr('data-edit-action'); 
		var del 			= $(e.relatedTarget).attr('data-delete-action');

		if ((id != 0)&&(typeof(id) != "undefined"))
		{
			$('.modal_schedule_id').val(id);
			$('.schedule_label').val(title);
			$('.schedule_on').val(date_start);			
			$('.schedule_start').val(start);
			$('.schedule_end').val(end);
			$('.schedule_status').val(status);
			$('.schedule_delete').attr('data-delete-action', del);
			$('.schedule_delete').show();
			$('.schedule_title').text('Edit Jadwal');			
			$(this).parent().attr('action', edit);			
		}
		else
		{
			$('.modal_schedule_id').val(null);
			$('.schedule_label').val('');
			$('.schedule_on').val(date_start);			
			$('.schedule_start').val(start);
			$('.schedule_end').val(end);
			$('.schedule_status').val(status);
			$('.schedule_delete').hide();
			$('.schedule_title').text('Tambah Jadwal');
			$(this).parent().attr('action', add);
		}		
	});
</script>