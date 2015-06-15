<div class="panel">
	<div class="panel-body">
		<h4>
			<a href="{{ $url }}">{{ $data['name'] }}</a>
		</h4>
		@if ((isset($data['contacts']))&&count($data['contacts']))
			@foreach ($data['contacts'] as $key2 => $value2)
				@if ($value2['item']=='phone')
					<p><i class="fa fa-mobile fa-fw"></i> {{ $value2['value'] }}</p>
				@elseif ($value2['item']=='email')
					<p><i class="fa fa-envelope fa-fw"></i> {{ $value2['value'] }}</p>
				@endif
			@endforeach
		@endif
		<span class="pull-right">
			<a href="{{ isset($edit) ? $edit : '' }}" class="btn btn-default">
				<i class="fa fa-pencil"></i>
			</a>
			<a href="{{ isset($delete) ? $delete : '' }}" class="btn btn-default" data-toggle="modal" data-target="#delete"><i class="fa fa-trash"></i></a>
		</span>
	</div>
</div>