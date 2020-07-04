<div class="card">
	<div class="card-header py-3 border-bottom-danger">
	  <div class="row">
		<div class="col-md-9"> 
	  		<h3 class="m-0 font-weight-bold text-gray-600">Employees Directory</h3>
		</div> 
	   </div>
	</div>
	<!-- <div class="profile-head">
		<ul class="nav nav-tabs mb-0" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="organogram-tab" data-toggle="tab" href="#organogram" role="tab" aria-controls="employee" aria-selected="false">Organogram</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="home-tab" data-toggle="tab" href="#directory" role="tab" aria-controls="home" aria-selected="true">Directory</a>
			</li>	
		</ul>
	</div> -->
	<div class="card-body">
		<!-- <div class="tab-content employee-tab" id="myTabContent">
			<div class="tab-pane fade show active" id="organogram" role="tabpanel" aria-labelledby="organogram-tab">
			 	<div class="org-chart" id="very-basic-example"></div>				
			</div> -->
			<div class="tab-pane fade show active" id="directory" role="tabpanel" aria-labelledby="directory-tab">
				<?php include_once('views/contact-directory.php');?>
			</div>
		<!-- </div> -->
	</div>
</div>