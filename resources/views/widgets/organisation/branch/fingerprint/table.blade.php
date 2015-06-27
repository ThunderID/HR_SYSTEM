@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {!! $widget_title or 'Sidik Jari Hari Ini' !!} </h1>
	@overwrite

	@section('widget_body')
			<div class="table-responsive">
				<table class="table table-bordered text-center">
					<thead>
						<tr>
							<th class="text-center">Jempol Kiri</th>
							<th class="text-center">Telunjuk Kiri</th>
							<th class="text-center">Tengah Kiri</th>
							<th class="text-center">Manis Kiri</th>
							<th class="text-center">Kelingking Kiri</th>
							<th class="text-center">Jempol Kanan</th>
							<th class="text-center">Telunjuk Kanan</th>
							<th class="text-center">Tengah Kanan</th>
							<th class="text-center">Manis Kanan</th>
							<th class="text-center">Kelingking Kanan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<form action="" class="check" method="post">
							<td>
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'left_thumb']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'left_thumb']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_thumb']) checked @endif>
									</label>
								</div>
							</td>
							<td>
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'left_index_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'left_index_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_index_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td>
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'left_middle_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'left_middle_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_middle_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td>
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'left_ring_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'left_ring_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_ring_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td>
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'left_little_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'left_little_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['left_little_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td>
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'right_thumb']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'right_thumb']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_thumb']) checked @endif>
									</label>
								</div>
							</td>
							<td>
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'right_index_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'right_index_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_index_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td>
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'right_middle_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'right_middle_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_middle_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td>
								<div class="checkbox checkbox-inline checkbox-styled">
									<label>	
										<input type="checkbox" class="thumb" data-checked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'wrong' => 'right_ring_finger']) }}" data-unchecked-action="{{ route('hr.branch.fingers.store', ['branch_id' => $branch['id'], 'org_id' => $data['id'], 'id' => $FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['id'], 'right' => 'right_ring_finger']) }}" @if($FingerPrintComposer['widget_data']['fingerprintlist']['fingerprint']['right_ring_finger']) checked @endif>
									</label>
								</div>
							</td>
							<td>
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
			</div>
		<div class="clearfix">&nbsp;</div>
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif