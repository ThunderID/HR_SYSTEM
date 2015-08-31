<div class="clearfix">&nbsp;</div>
{!! Form::open(['url' => $DocumentComposer['widget_data']['documentlist']['form_url'], 'class' => 'form no_enter']) !!}	
	<?php $tabindex=1; ?>
	@foreach($DocumentComposer['widget_data']['documentlist']['document'] as $key => $value)
		<div class="row">
			<div class="col-sm-12 mb-15">
				<h3>{{ $value['name'] }}</h3>
				@foreach($value['templates'] as $key2 => $value2)
					<div class="form-group">
						<label for="field" class="control-label">{{ucwords($value2['field'])}}</label>					
						<?php 
							$content = null;
							$detailid = null;

							switch (strtolower($value2['type'])) 
							{
								case 'text':
									$form 		= '<textarea name="content['.$key.']['.$key2.']" value="" class="form-control" tabindex="'.$tabindex.'">'.$content.'</textarea>';
									$tabindex++;
									break;
								case 'date':
									$form 		= '<input type="date" class="form-control date-mask" id="text" name="content['.$key.']['.$key2.']" value="'.$content.'" tabindex="'.$tabindex.'">';
									$tabindex++;
									break;
								case 'numeric':
									$form 		= '<input type="numeric" class="form-control" id="text" name="content['.$key.']['.$key2.']" value="'.$content.'" tabindex="'.$tabindex.'">';
									$tabindex++;
									break;
								default:
									$form 		= '<input type="text" class="form-control" id="text" name="content['.$key.']['.$key2.']" value="'.$content.'" tabindex="'.$tabindex.'">';
									$tabindex++;
									break;
							}
						;?>					
						{!!$form!!}					
						<input type="hidden" class="form-control" id="text" name="template_id[{{$key}}][{{$key2}}]" value="{{$value2['id']}}">
						<input type="hidden" class="form-control" id="text" name="detail_id[{{$key}}][{{$key2}}]" value="{{$detailid}}">
					</div>
				@endforeach
				<input type="hidden" class="form-control" id="text" name="document_id[{{$key}}]" value="{{$value['id']}}">
			</div>
		</div>
	@endforeach
	<div class="row">
		<div class="col-sm-12 text-right">
			<input type="submit" class="btn btn-primary pull-right" value="Simpan" tabindex="{{ $tabindex }}">
			<?php $tabindex++; ?>
			<button class="btn btn-primary prevBtn pull-right mr-10" type="button" tabindex="{{ $tabindex }}">Kembali</button>
			<?php $tabindex++; ?>
		</div>
	</div>
{!! Form::close() !!}