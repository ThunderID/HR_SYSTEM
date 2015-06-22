<!-- {!! HTML::script('plugins/microtemplate/microtemplating.min.js')!!}	
{!! HTML::script('plugins/microtemplate/Pluginmicrotemplating.min.js')!!}
{!! HTML::script('plugins/microtemplate/handlebars-v3.0.3.js')!!} -->


<script type="text/javascript">

	$('.btn-add-doc').on('click', function(e){		
		var template = '';

		template += '<div class="form-group"> \
							<div class="col-md-2">&nbsp;</div> \
							<div class="col-md-2">				\
								<label for="field[]" class="control-label">Nama Input</label> \
							</div> \
							<div class="col-md-2"> \
								<input type="text" class="form-control" id="field[]" name="field[]"> \
							</div> \
							<div class="col-md-2"> \
								<label for="" class="control-label">Tipe Input</label> \
							</div> \
							<div class="col-md-2"> \
								<select id="Type" class="form-control form-control input-md" name="type[]"> \
									<option value="numeric">Angka</option> \
									<option value="date">Tanggal</option> \
									<option value="string">Teks Singkat</option> \
									<option value="text">Teks Panjang</option> \
								</select> \
							</div> \
							<div class="col-md-2"> \
								<a href="javascript:;" class="btn-delete-doc" style="color:#666;"><i class="fa fa-minus-circle fa-lg mt-10"></i></a> \
							</div> \
						</div> \
					';
		$("#documentList").append(template);
	});
	
	$('.btn-delete-doc').on('click', function(){bind_delete($(this))});

	function bind_delete(e) {
		console.log(e);
		bind_delete(e);
	}
	
</script>