<script>
	/* Modal Delete */
	$('.delete').on('show.bs.modal', function(e) {
		var action 	= $(e.relatedTarget).attr('data-delete-action');		
		var affect 	= parseInt($(e.relatedTarget).attr('data-affect'));

		if (typeof(affect) != "undefined" && affect !== null && (!isNaN(affect))) {
			if (affect==0) {
				$('.modal_delete_affect_org').html('');
			}
			else {
				$('.modal_delete_affect_org').html('<input type="hidden" name="affect" value="'+affect+'"/>');
			}
		}

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
		var is_check_range 	= $(e.relatedTarget).find('is_range');
		// console.log(is_check_range);
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
			$('.is_range').parent().parent().parent().remove();
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

	/* Modal Import CSV person document */
	$('.import_csv_doc_person').on('show.bs.modal', function(e) {
		var action 		= $(e.relatedTarget).attr('data-action');
		var org_id		= $(e.relatedTarget).attr('data-org_id');
		var doc_id		= $(e.relatedTarget).attr('data-doc_id');
		var doc_name	= $(e.relatedTarget).attr('data-doc_name');

		$('.span-title').html(doc_name);
		$(this).parent().attr('action', action+'?doc_id='+doc_id+'&org_id='+org_id);
	});

	/* Modal Import CSV org document */
	$('.import_csv_doc_org').on('show.bs.modal', function(e) {
		var action 		= $(e.relatedTarget).attr('data-action');
		var org_id		= $(e.relatedTarget).attr('data-org_id');
		var doc_id		= $(e.relatedTarget).attr('data-doc_id');
		var doc_name	= $(e.relatedTarget).attr('data-doc_name');

		$('.span-title').html(doc_name);
		$(this).parent().attr('action', action+'?doc_id='+doc_id+'&org_id='+org_id);
	});

	/* Modal import csv create person */
	$('.import_csv_person_create').on('show.bs.modal', function(e) {
		var action 		= $(e.relatedTarget).attr('data-action');
		var org_id		= $(e.relatedTarget).attr('data-org_id');

		$('.span-title').html('person');
		$(this).parent().attr('action', action+'?org_id='+org_id);
	});

	/* modal import csv person work */
	$('.import_csv_work_person').on('show.bs.modal', function(e) {
		var action 		= $(e.relatedTarget).attr('data-action');
		var org_id 		= $(e.relatedTarget).attr('data-org_id');

		$('.span-title').html('pekerjaan');
		$(this).parent().attr('action', action+'?org_id='+org_id);
	});

	/* modal import csv person workleave */
	$('.import_csv_person_workleave').on('show.bs.modal', function(e) {
		var action 		= $(e.relatedTarget).attr('data-action');
		var org_id 		= $(e.relatedTarget).attr('data-org_id');
		var person_id 	= $(e.relatedTarget).attr('data-person_id');

		$(this).parent().attr('action', action+'?org_id='+org_id+'&person_id='+person_id);
	});

	/* modal add widget org */
	$('.add_widget').on('show.bs.modal', function(e) {
		var org_id 		= $(e.relatedTarget).attr('data-org');
		var select_id 	= $(this).find('.select2-dashboard-widget');

		$(this).find('.hid_org_id').val(org_id);

		// $(select_id).select2().on('change', function(){alert('halo');});
	});
</script>