@if ($errors->count())
	<div class='alert alert-danger mt-10'>
		<div class="row">
			<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">
				<i class="fa fa-exclamation-circle" style="font-size:40px"></i>
			</div>
			<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
				@foreach ($errors->all('<p>:message</p>') as $error)
					{!! $error !!}
				@endforeach
			</div>
		</div>
	</div>
@endif
