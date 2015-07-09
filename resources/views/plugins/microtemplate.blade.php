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
	$('.filter-key').on('change', function(){create_o($(this))});
	function create_o(e)
	{
		var fil = $(e).val().split('_');
		var a 	= new Object();
		var tmp = '';
		fil 	= fil[1];
		
		jQuery.each(x, function(i, val) {
			a[val.key] = val.values;
		});

		jQuery.each(a[fil], function(i, val) {
			tmp +='<option value="'+val.key+'">'+val.value+'</option>';
		});
		// console.log($(e).parent().siblings().children());
		$(e).parent().next().children().html(tmp);
		$('.filter-key').on('change', function(){create_o($(this))});
	}

	$('.btn-add-filter').bind('click', function()
	{
		var template = '';
		template += '<div class="btn-group ml-10"> \
							<select name="key[]" id="" class="form-control filter-key"> \
					';
		jQuery.each(x, function(i, val)
		{
			template +='<option value="'+val.prefix+'_'+val.key+'">'+val.value+'</option>';
		});

		template 	+='</select> \
							</div> \
							<div class="btn-group ml-10"> \
								<select name="value[]" id="" class="form-control filter-value"> \
									<option value=""></option> ';

		template 	+='</select> \
						</div>';

		$('.filter-add').append(template);
		// $('.btn-delete-filter').on('click', function(){bind_delete_filter($(this))});
		$('.filter-key').on('change', function(){create_o($(this))});
	});	

	function bind_delete_filter(e) {

	}

	/* =====schedule modal===== */
	$('.is_range').on('change', function() {
		var temp_sch 		= '';
		if ($(this).prop('checked')) {
			var sch_on		= $('.schedule_on').val();
				temp_sch	+= '<div class="col-sm-5"> \
									<div class="form-group"> \
										<label class="control-label">Tanggal Start</label> \
										<input type="text" name="onstart" class="form-control date-mask schedule_on_start" value="'+sch_on+'"> \
									</div> \
								</div> \
								<div class="col-sm-5 col-sm-offset-2"> \
									<div class="form-group"> \
										<label class="control-label">Tanggal End</label> \
										<input type="text" name="onend" class="form-control date-mask schedule_on_end" value="'+sch_on+'"> \
									</div> \
								</div>';
		} else {
			var sch_on_start 	= $('.schedule_on_start').val(); 
				temp_sch 		+= '<div class="col-sm-12"> \
										<div class="form-group"> \
											<label class="control-label">Tanggal</label> \
											<input type="text" name="on" class="form-control date-mask schedule_on" value="'+sch_on_start+'"> \
										</div> \
									</div>';
		}
		$('.date_range').html(temp_sch);
		$('.date-mask').inputmask('dd-mm-yyyy');
	});
</script>