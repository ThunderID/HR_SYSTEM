{!! HTML::style('plugins/table-fix-header/table-fixed-header.css') !!}
{!! HTML::script('plugins/table-fix-header/table-fixed-header.js') !!}

<style>
	table.report {
	    table-layout:fixed;
	}
	.div-table-content {
	  height:600px;
	  overflow-y:auto;
	  margin-top: -20px;
	}
</style>

<script>
	$(document).ready(function()
	{
		// make the header fixed on scroll
		$('.table-fixed-header').fixedHeader();
		// $('.table-responsive').on('show.bs.dropdown', function () {
		// 	$('.table-responsive').css( "overflow", "inherit" );
		// });

		// $('.table-responsive').on('hide.bs.dropdown', function () {
		// 	$('.table-responsive').css( "overflow", "auto" );
		// })
	});

</script>