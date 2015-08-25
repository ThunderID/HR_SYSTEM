<div id="columns">	
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<ul id="column1" class="column">
				<li class="widget">
					<div class="widget-content">
						@include('widgets.organisation.person.stat.total_employee', [
							'widget_template'		=> 'plain',
							'widget_title'			=> 'Total Karyawan '.$data['name'],
							'widget_options'		=> 	[
															'personlist'		=>
															[
																'title'				=> 'Total Karyawan "'.$data['name'].'"',
																'organisation_id'	=> $data['id'],
																'search'			=> ['chartnotadmin' => true],
																'sort'				=> [],
																'page'				=> 1,
																'per_page'			=> 100,
															]
														]
						])
					</div>
				</li>
			</ul>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<ul id="column2" class="column">
				<li class="widget">
			        <div class="widget-content">
				        @include('widgets.organisation.branch.stat.total_branch', [
	        				'widget_template'		=> 'plain',
	        				'widget_title'			=> 'Total Cabang '.$data['name'],
	        				'widget_options'		=> 	[
	        												'branchlist'		=>
	        												[
	        													'organisation_id'	=> $data['id'],
	        													'search'			=> [],
	        													'sort'				=> [],
	        													'page'				=> 1,
	        													'per_page'			=> 100,
	        												]
	        											]
	        			])
				    </div>
				</li>
			</ul>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<ul id="column3" class="column">
			    <li class="widget">  
				    <div class="widget-content">
				        @include('widgets.organisation.document.stat.total_document', [
	        				'widget_template'		=> 'plain',
	        				'widget_title'			=> 'Total Dokumen '.$data['name'],
	        				'widget_options'		=> 	[
	        												'documentlist'		=>
	        												[
	        													'organisation_id'	=> $data['id'],
	        													'search'			=> [],
	        													'sort'				=> [],
	        													'page'				=> 1,
	        													'per_page'			=> 100,
	        												]
	        											]
	        			])
				    </div>
				</li>
			</ul>
		</div>
	</div>
</div>