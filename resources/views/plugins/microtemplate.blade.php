<script type="text/javascript">
	$('.btn-delete-doc').on('click', function(){bind_delete($(this))});
	$('.btn-add-doc').on('click', function(e){	
		var tot = 0;
		var x = $('.template > .form-group').length+1; 
		
		var template = '';		

			template += '<div class="form-group"> \
							<div class="col-md-2">&nbsp;</div> \
							<div class="col-md-2">				\
								<label for="field[]" class="control-label">Nama Input</label> \
							</div> \
							<div class="col-md-2"> \
								<input type="text" class="form-control field" id="field[]" name="field[]"> \
							</div> \
							<div class="col-md-2"> \
								<label for="" class="control-label">Tipe Input</label> \
							</div> \
							<div class="col-md-2"> \
								<select id="Type" class="form-control form-control input-md type" name="type[]"> \
									<option value="numeric">Angka</option> \
									<option value="date">Tanggal</option> \
									<option value="string">Teks Singkat</option> \
									<option value="text">Teks Panjang</option> \
								</select> \
							</div> \
							<div class="col-md-2"> \
								<a href="javascript:;" class="btn-delete-doc" style="color:#666;" data-count="" data-total-count="'+x+'"><i class="fa fa-minus-circle fa-lg mt-10"></i></a> \
							</div> \
						</div> \
						';	
							
			$("#template").append(template);
			$('.btn-delete-doc').on('click', function(){bind_delete($(this))});			
	});	
		
	function bind_delete(e) {		
		$(e).parent().parent().remove();
		$('.btn-delete-doc').on('click', function(){bind_delete($(this))});		
	}
	
</script>