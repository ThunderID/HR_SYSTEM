@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {!! $widget_title or 'Sidik Jari Hari Ini' !!} </h1>
	@overwrite

	@section('widget_body')
				<table class="table table-hover table-affix table-bordered text-center">
					<thead>
						<tr>
							<th class="text-center font-12">Jempol <br> Kiri</th>
							<th class="text-center font-12">Telunjuk <br> Kiri</th>
							<th class="text-center font-12">Tengah <br> Kiri</th>
							<th class="text-center font-12">Manis <br> Kiri</th>
							<th class="text-center font-12">Kelingking <br> Kiri</th>
							<th class="text-center font-12">Jempol <br> Kanan</th>
							<th class="text-center font-12">Telunjuk <br> Kanan</th>
							<th class="text-center font-12">Tengah <br> Kanan</th>
							<th class="text-center font-12">Manis <br> Kanan</th>
							<th class="text-center font-12">Kelingking <br> Kanan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<form action="" class="check" method="post">
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'left_thumb']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'left_thumb']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_thumb']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'left_index_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'left_index_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_index_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'left_middle_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'left_middle_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_middle_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'left_ring_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'left_ring_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_ring_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'left_little_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'left_little_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_little_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'right_thumb']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'right_thumb']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_thumb']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'right_index_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'right_index_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_index_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'right_middle_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'right_middle_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_middle_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'right_ring_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'right_ring_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_ring_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td class="text-center">
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'right_little_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'right_little_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_little_finger']) checked @endif>
									</label>
								</div>
							</td>
							
						</form> 
						</tr>
					</tbody>
				</table>
		<div class="clearfix">&nbsp;</div>
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif