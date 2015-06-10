@section('area')
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
				@include('widget.form.form_login', [
					'widget_template'	=> 'panel'					
				])
			</div>
		</div>
	</div>
@stop