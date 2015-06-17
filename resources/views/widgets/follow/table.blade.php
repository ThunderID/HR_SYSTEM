@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Kalender </h1>
<small>Total data {{$FollowComposer['widget_data']['followlist']['follow-pagination']->total()}}</small>
@overwrite

@section('widget_body')
	@if(isset($FollowComposer['widget_data']['followlist']['follow']))
		<div class="clearfix">&nbsp;</div>
		<table class="table">
			<thead>
				<tr>
					<th>Kalender</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			@foreach($FollowComposer['widget_data']['followlist']['follow'] as $key => $value)
				<tbody>
					<tr>
						<td>
							{{$value['calendar']['name']}}
						</td>
						<td class="text-right">
							<a href="" class="btn btn-default"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
				</tbody>
			@endforeach
		</table>

		<div class="row">
			<div class="col-sm-12 text-center">
				<p>Menampilkan {!!$FollowComposer['widget_data']['followlist']['follow-display']['from']!!} - {!!$FollowComposer['widget_data']['followlist']['follow-display']['to']!!}</p>
				{!!$FollowComposer['widget_data']['followlist']['follow-pagination']->appends(Input::all())->render()!!}
			</div>
		</div>

		<div class="clearfix">&nbsp;</div>
	@endif
@overwrite	
