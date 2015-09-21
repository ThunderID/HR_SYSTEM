<script type="text/javascript">
$('.thumb').change(function(e){
		var x = $(this).attr('data-checked-action');
		var y = $(this).attr('data-unchecked-action');
		$(this).prop('disabled', true);
		
		if ($(this).attr('checked')=='checked') {
			$('.check').attr('action', y);
			$('.check').submit();
		}
		else {
			$('.check').attr('action', x);
			$('.check').submit();
		}
	});

	$('.affect_schedule_org').change(function() {
		var value 	= $(this).val();

		if ($(this).is(':checked')) {
			$('.schedule_delete').attr('data-affect', value);
		}
		else {
			$('.schedule_delete').attr('data-affect', 0);	
		}
	});

	/*====CHECKBOX AUTOMATIC GENERATE NIK=====*/
	$('.checkbox_person_automatic_nik').change(function() {
		var org_id = $(this).attr('data-org');
		// CODING GET NIK AJAX FROM PERSONCONTROLLER
		if ($(this).is(':checked')) {
			$.ajax({
				url: '{{ route("hr.person.getlastnik") }}',
				method: 'GET',
				dataType: 'json',
				data: { org_id: org_id },
				success: function(result) {
					if (result.data) {	
						$('.form_person_nik').val(result.data.nik);
					}	
				}
			});
		}
	});
</script>
