<script type="text/javascript">
	/* delete document */
	$('.btn-delete-doc').on('click', function(){bind_delete($(this))});
	$('.btn-add-doc').bind('click', function(e){			
		var template = '';		

		template += '<div class="row"> \
						<div class="col-sm-5"> \
							<div class="form-group"> \
								<label for="field[]" class="control-label">Nama Input</label> \
								<input type="text" class="form-control no-enter" id="field[]" name="field[]"> \
							</div> \
						</div> \
						<div class="col-sm-5"> \
							<div class="form-group"> \
								<label for="" class="control-label">Tipe Input</label> \
								<select id="Type" class="form-control form-control input-md type" name="type[]"> \
									<option value="numeric">Angka</option> \
									<option value="date">Tanggal</option> \
									<option value="string">Teks Singkat</option> \
									<option value="text">Teks Panjang</option> \
								</select> \
							</div> \
						</div> \
						<div class="col-sm-2"> \
							<div class="form-group"> \
								<a href="javascript:;" class="btn-delete-doc" style="color:#666;"><i class="fa fa-minus-circle fa-lg mt-30"></i></a> \
							</div> \
						</div> \
					</div> \
					';	
						
		$("#template").append(template);
		$('#template input, #template select').on("keyup keypress", function(e) {
			var code = e.keyCode || e.which; 
			if (code  == 13) 
			{
				e.preventDefault();
				return false;
			}
		});
		$('.btn-delete-doc').on('click', function(){bind_delete($(this))});			
	});	
		
	function bind_delete(e) {		
		$(e).parent().parent().parent().remove();
		$('.btn-delete-doc').on('click', function(){bind_delete($(this))});		
	}

	/* delete filter */
	$('.btn-delete-filter').on('click', function(){bind_delete_filter($(this))});
	$('.btn-add-filter').bind('click', function()
	{
		var template = '';

		template += '<div class="btn-group ml-10"> \
							<select name="key[]" id="" class="form-control"> \
								<option value=""></option> \
							</select> \
						</div> \
						<div class="btn-group ml-10"> \
							<select name="value[]" id="" class="form-control"> \
								<option value=""></option> \
							</select> \
						</div> \
					';
		$('.filter-add').append(template);
		$('.btn-delete-filter').on('click', function(){bind_delete_filter($(this))});
	});	
	function bind_delete_filter(e) {

	}
</script>