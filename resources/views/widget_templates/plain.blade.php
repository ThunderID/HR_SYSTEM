@if (isset($widget_errors) && $widget_errors->count())
	<div class="alert alert-danger">
		<h4>{{$widget_data['widget_name']}}</h4>
		@foreach ($widget_errors->all('<li>:message</li>') as $message)
			{!! $message !!}
		@endforeach
	</div>
@else
	<h4 class='text-bold'>
		@yield('widget_title','[widget_title]')
	</h4>

	<hr style="margin-bottom:0">
	<div class='{{$widget_body_class}}'>
		@yield('widget_body','[widget_body]')
	</div>
@endif