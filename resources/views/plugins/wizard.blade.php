{{-- Next Wizard --}}
<script type="text/javascript">
  $(document).ready(function () {
	  var navListItems = $('ul.setup-panel li a'),
			  allWells = $('.setup-content'),
			  allNextBtn = $('.nextBtn');
			  allPrevBtn = $('.prevBtn');

	  // allWells.hide();

	  navListItems.click(function (e) {
		  e.preventDefault();
		  var $target = $($(this).attr('href')),
				  $item = $(this);

		  if (!$item.attr('disabled')) {
			  navListItems.removeClass('btn-primary').addClass('btn-default');
			  allWells.hide();
			  $target.show();
			  $target.find('input:eq(0)').focus();
		  }
		  else 
		  {
		  	e.stopImmediatePropagation();
		  	return false;
		  }
	  });

		allNextBtn.click(function(){
			var curStep = $(this).closest(".setup-content"),
			  curStepBtn = curStep.attr("id"),
			  nextStepWizard = $('ul.setup-panel li a[href="#' + curStepBtn + '"]').parent().next().children("a"),
			  curInputs = curStep.find("input[type='text']");
			  isValid = true;

			$(".form-group").removeClass("has-error");
			for(var i=0; i<curInputs.length; i++){
			  if (!curInputs[i].validity.valid){
				  isValid = false;
				  $(curInputs[i]).closest(".form-group").addClass("has-error");
			  }
			}

			if (isValid)
			nextStepWizard.removeAttr('disabled').trigger('click');
		});

		allPrevBtn.click(function(){
		    var curStep = $(this).closest(".setup-content"),
		  	  curStepBtn = curStep.attr("id"),
		  	  nextStepWizard = $('ul.setup-panel li a[href="#' + curStepBtn + '"]').parent().prev().children("a"),
		  	  isValid = true;

		  	if (isValid)
		  		nextStepWizard.removeAttr('disabled').trigger('click');
		});

	  $('ul.setup-panel li a').trigger('click');
	});
  </script>