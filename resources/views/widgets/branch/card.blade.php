<div class="clearfix">&nbsp;</div>
@if(isset($widget_data['data']))
	<div class="row">
		@foreach($widget_data['data'] as $key => $value)
			@if($key%2==0 && $key!=0)
				</div>
				<div class="row">
			@endif
			<div class="col-sm-6">
				<div class="well well-lg">
					{{$value['name']}}
				</div>
			</div>
		@endforeach
	</div>
@endif
