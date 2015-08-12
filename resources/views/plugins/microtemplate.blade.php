<script type="text/javascript">
	/*=====================TEMPLATE DOKUMEN====================*/
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
	/*====================END TEMPLATE DOKUMEN================*/

	/*====================PERSON DOKUMEN======================*/
	$('.btn_add_doclist').on('click', function() {
		var temp 		= '';
		var org_id 		= {{ Input::get('org_id')}};
		var list_doc; 	

		temp += '<div class="form-group"> \
					<label class="control-label">Jenis Dokumen</label> \
					<select name="doc_id[]" id="doc" class="form-control select2 select_doc" tabindex="1"> \
					</select> \
				</div> \
		';
		$('#doc_template').append(temp);		

		$.ajax({
			url: '{{ route("hr.documents.list") }}',
			method: 'GET',
			dataType: 'json',
			data: { org_id: org_id },
			success: function(result) {
				if (result.data) {		
					$.each(result.data, function(index, element) {
						$('.select_doc').append('<option value="'+element.id+'">'+element.name+'</option>');
					});																
				}	
			}
		});
	});
	/*==================END PERSON DOKUMEN=======================*/

	/*===================PERSON RELATIVE========================*/
	$('.btn_duplicate_add_relative').click(function() {
		var temp 	= '';
		temp 		+= '<div class="row pt-20"> \
							<div class="col-sm-12"> \
								<div class="row"> \
									<div class="col-sm-12"> \
										<div class="form-group"> \
											<label class="control-label">Hubungan</label> \
											<select name="relationship" class="form-control"> \
												<option value="parent">Orang Tua</option> \
												<option value="spouse">Pasangan (Menikah)</option> \
												<option value="partner">Pasangan (Tidak Menikah)</option> \
												<option value="child">Anak</option> \
											</select> \
										</div> \
									</div> \
								</div> \
								<div class="row"> \
									<div class="col-sm-12"> \
										<div class="form-group"> \
											<label class="control-label">ID</label> \
											<input type="text" name="uniqid" class="form-control" /> \
										</div> \
									</div> \
								</div> \
								<div class="row"> \
									<div class="col-sm-4"> \
										<div class="form-group"> \
											<label class="control-label">Gelar Depan</label> \
											<input type="text" name="prefix_title" class="form-control" /> \
										</div> \
									</div> \
									<div class="col-sm-4"> \
										<div class="form-group"> \
											<label class="control-label">Nama</label> \
											<input type="text" name="name" class="form-control" /> \
										</div> \
									</div> \
									<div class="col-md-4"> \
										<div class="form-group"> \
											<label class="control-label">Gelar Akhir</label> \
											<input type="text" name="suffix_title" class="form-control" /> \
										</div> \
									</div> \
								</div> \
								<div class="row"> \
									<div class="col-sm-6"> \
										<div class="form-group"> \
											<label class="control-label">Tempat Lahir</label> \
											<input type="text" name="place_of_birth" class="form-control" /> \
										</div> \
									</div> \
									<div class="col-sm-6"> \
										<div class="form-group"> \
											<label class="control-label">Tanggal Lahir</label> \
											<input type="text" name="date_of_birth" class="form-control date-mask" /> \
										</div>	\
									</div> \
								</div> \
								<div class="row"> \
									<div class="col-sm-4"> \
										<div class="form-group"> \
											<label class="mt-10">Jenis Kelamin</label> \
										</div> \
									</div> \
									<div class="col-md-2"> \
										<div class="radio"> \
											<label> \
												<input name="gender" type="radio" value="male"> Laki-laki \
											</label> \
										</div> \
									</div> \
									<div class="col-md-2"> \
										<div class="radio"> \
											<label> \
												<input name="gender" type="radio" value="female"> Perempuan \
											</label> \
										</div> \
									</div> \
								</div> \
							</div> \
						</div> \
					';
		$('#duplicate_relative').append(temp);
	});
	/*=================END PERSON RELATIVE==================*/

	/*=====================PERSON CONTACT==================*/
	$('.btn_duplicate_add_contact').on('click', function() {
		var temp 		= '';
		temp 			+= '<div class="form-group mt-20"> \
								<label class="control-label">Item</label> \
								<input type="text" name="item[]" class="form-control select2-tag-contact" style="width:100%" /> \
							</div> \
							<div class="form-group"> \
								<label class="control-label">Kontak</label>	\
								<input type="text" name="value[]" class="form-control val-contact" /> \
							</div> \
							<div class="form-group"> \
								<div class="checkbox"> \
									<label> \
										<input type="checkbox" name="is_default[]" /> Aktif \
									</label> \
								</div> \
							</div> \
						';
		$('#duplicate_contact').append(temp);
		$('.select2-tag-contact').select2({
	 		tokenSeparators: [",", " ", "_", "-"],
			tags: ['alamat', 'bbm', 'email', 'line', 'mobile', 'whatsapp'],			
			maximumSelectionSize: 1,
			selectOnBlur: true,
			multiple: false
	 	});	 
	});
	/*====================END PERSON CONTACT===============*/

	/*=========================FILTER======================*/
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
		$('.filter-key').on('click', function(){create_o($(this))});
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
		$('.filter-key').on('click', function(){create_o($(this))});
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