{!! HTML::style('plugins/table-fix-header/table-fixed-header.css') !!}
{!! HTML::script('plugins/table-fix-header/table-fixed-header.js') !!}

<style>
	table {
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
		var row = $('table#mytable > tbody > tr:first');
		for (i=0; i<30; i++) {
		  $('table#mytable > tbody').append(row.clone());
		}

		// make the header fixed on scroll
		$('.table-fixed-header').fixedHeader();
	});

</script>