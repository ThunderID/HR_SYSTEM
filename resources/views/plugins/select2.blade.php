{!! HTML::style('plugins/select2/select2.css') !!}
{!! HTML::script('plugins/select2/select2.min.js') !!}

<script type="text/javascript">
	$(document).ready(function(){
		// ---------------------------- BASIC SELECT2 ----------------------------
		$('.select2').select2();

		// ---------------------------- SELECT2 SKINs ----------------------------
		
		$('.select2-tag-contact').select2({
	 		tokenSeparators: [",", " ", "_", "-"],
			tags: ['alamat', 'bbm', 'email', 'line', 'mobile', 'whatsapp'],			
			maximumSelectionSize: 1,
			selectOnBlur: true,
			multiple: false
	 	});	 	

	 	$('.select2-tag-document').select2({
	 		tokenSeparators: [",", " ", "_", "-"],
			tags: ['akun', 'appraisal', 'kontrak', 'identitas', 'pajak', 'pendidikan', 'sp'],		
			maximumSelectionSize: 1,
			selectOnBlur: true
	 	});

		$('.select2-tag-days').select2({
	 		tokenSeparators: [",", " ", "_", "-"],
			tags: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],			
			maximumSelectionSize: 7,
			selectOnBlur: true,
			createSearchChoice: function() { return null; }
		});

		$('.select_chart').on('change', function()
		{			
			var org_id		= $(this).select2().data('organisationid');
			var branch_id	= $(this).select2().find(':selected').data('branchid');
			var chart_id 	= $(this).select2("val");
			var select 		= [];			
			$.ajax({
				url: "{{ route('hr.person.work.ajax') }}",
				dataType: 'json',
				data: { org_id: org_id, term: chart_id },
				success: function (data){					
					if (data.length!=0) {
						for (var i=0;i<data.length;i++) 
						{
							select += '<option value="'+data[i].calendar['id']+'">'+data[i].calendar['name']+'</option>';
						}					
						$('select.select_follow').html(select);
						$('select.select_follow').select2("val", data[0].calendar['id']);						
					} else {
						select += '<option value="">Tidak Ada Data</option>';
						$('select.select_follow').html(select);
						$('select.select_follow').select2("val", "");
					}

				}
			});
		});

		/* SELECT on change in person workleave */
		$('.select_person_workleave_widget').on('change', function()
		{
			$('form.form_widget_person_workleave').submit();
		});

		$('.select_doc_person').on('change', function() {
			var value = $(this).val();
			var name  = $(this).find(':selected').attr('data-name');

			$('.import_doc').attr('data-doc_id', value).attr('data-doc_name', name);
		});

		/* Select2 widget org */
		$(".select_widget_org").on('change', function(e)
		{
			var value 					= $(this).val();
			var type 					= $(this).find(':selected').attr('data-type');
			var widget_option_title 	= $(this).find(':selected').attr('data-widget-option-title');
			var widget_template 		= $(this).find(':selected').attr('data-template');
			var widget_query 			= $(this).find(':selected').attr('data-query');

			if (value=='totalprocesslogondate') {
				var status = $(this).find(':selected').attr('data-status');
				$('.processlog_status').val(status);
			}
			else {
				$('.processlog_status').val('');	
			}

			$('.type_widget').val(type);
			$('.widget_option_title').val(widget_option_title);
			$('.widget_template').val(widget_template);
			$('.widget_data').val(value);
			$('.widget_query').val(widget_query);
		});

		/* Select2  widget person */
		$('.select_widget_person').on('change', function(e)
		{
			var value 					= $(this).val();
			var type 					= $(this).find(':selected').attr('data-type');
			var widget_option_title 	= $(this).find(':selected').attr('data-widget-option-title');
			var widget_template 		= $(this).find(':selected').attr('data-template');
			var widget_query 			= $(this).find(':selected').attr('data-query');

			$('.processlog_status').val('');
			$('.type_widget').val(type);
			$('.widget_option_title').val(widget_option_title);
			$('.widget_template').val(widget_template);
			$('.widget_data').val(value);
			$('.widget_query').val(widget_query);
		});

		/* select type widget */
		$('.select_type_widget').on('change', function() {
			var value 			= $(this).val();

			if (value=='organisation') {
				$('select.select_widget_person').addClass('hide');
				$('select.select_widget_org').removeClass('hide').val('');
			}
			else {
				$('select.select_widget_org').addClass('hide');	
				$('select.select_widget_person').removeClass('hide').val('');
			}
		});
	});	
</script>