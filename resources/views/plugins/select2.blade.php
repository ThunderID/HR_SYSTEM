{!! HTML::style('plugins/select2/select2.css') !!}
{!! HTML::script('plugins/select2/select2.min.js') !!}

<script type="text/javascript">
	$(document).ready(function(){
		// ---------------------------- BASIC SELECT2 ----------------------------
		$('.select2').select2();

		// ---------------------------- SELECT2 SKINs ----------------------------
		
		$('.select2-tag-contact').select2({
	 		tokenSeparators: [",", " ", "_", "-"],
			tags: ['alamat', 'bbm', 'email', 'line', 'phone', 'whatsapp'],			
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
	});	
</script>