@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_body')
	@if(isset($widget_data['data']))
		<div class="row">
			@foreach($widget_data['data'] as $key => $value)
				@if($key%2==0 && $key!=0)
					</div>
					<div class="row">
				@endif
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4 class="text-left">{{$value['name']}}</h4>
							@foreach($value['works'] as $key2 => $value2)
								@if(strtolower($value2['pivot']['status']) != 'admin')
									<h5 class="text-left">
										<i class="fa fa-building"></i> &nbsp;
										{{$value2['name']}} Departemen {{$value2['tag']}} Cabang {{$value2['branch']['name']}}
									</h5>
								@endif
							@endforeach
							@foreach($value['contacts'] as $key2 => $value2)
								<h5 class="text-left">
									<i class="fa fa-envelope"></i> &nbsp;
									{{$value2['value']}}
								</h5>
							@endforeach
							<span class="pull-right">
								<a href="" class="btn btn-default"><i class="fa fa-trash"></i></a>
								<a href="{{route('hr.persons.edit', [$value['id'], 'org_id' => $data['id']])}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
								<a href="" class="btn btn-default"><i class="fa fa-eye"></i></a>
							</span>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	@endif
@overwrite	
