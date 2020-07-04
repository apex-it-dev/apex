<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Performance Evaluation</h3>
			 </div>
			<div class="col-md-2">
				<!-- <button id="btnAddHoliday" type="reset" class="btn btn-sm btn-danger float-right">&nbsp;<i class="fa fa-plus" style="font-size:14px"></i>&nbsp;Add Holiday&nbsp;&nbsp;</button> -->
				<!-- <button id="btnBack" type="reset" class="btn btn-sm btn-danger float-right" style="background-color: #b11c21;"><i class="fas fa-arrow-left"></i> BACK</button> -->
			</div>
		</div>
	</div>
	<div class="card-body"> 
		<div class="row"> 
			<div class="col-md-12">  
				<!-- <div class="profile-head">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="holiday-tab" data-toggle="tab" href="#holiday" role="tab" aria-controls="holiday" aria-selected="true">Holiday</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="event-tab" data-toggle="tab" href="#event" role="tab" aria-controls="holiday" aria-selected="true">Events</a>
						</li>
					</ul>
				</div> -->
<!--			Add New Holiday-->
				<div class="tab-content perfeval-tab" id="myTabContent">
					<div class="tab-pane fade show active" id="perfeval" role="tabpanel" aria-labelledby="perfeval-tab">
						<div class="col-md-12 col-sm-12">
							<div class="row">
								<div class="col-md-3 col-sm-3 col-xs-12"></div>
								<div class="col-md-3 col-sm-3 col-xs-12"></div>
								<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="input-group input-group-sm">
										<div class="input-group-prepend">
											<span class="input-group-text" id="inputGroup-sizing-sm">Employees</span>
										</div>
										<select id="eeList" name="eeList" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
											
										</select>
									</div>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<div class="input-group input-group-sm">
										<div class="input-group-prepend">
											<span class="input-group-text" id="inputGroup-sizing-sm">Period</span>
										</div>
										<select id="periodList" name="periodList" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
											
										</select>
									</div>
								</div>
							</div>
						</div>
					<!-- <div class="form-group row">		 -->
						<div class="table-responsive dtcontainer">
							<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
								<table class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" id="perfevaldatatable" width="100%" cellspacing="0">
									<thead class="thead-dark">
										<tr>
											<th width="10%">Period</th>
											<th width="10%">Submitted Date</th>
											<th width="10%">Start Date</th>
											<th width="10%">End Date</th>
											<th width="40%">Description</th>
											<th width="5%">Region</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>	
							</div>		
						</div>				
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" readonly/>
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" readonly/>
<input type="hidden" id="ofc" name="ofc" value="<?php echo $ofc; ?>" readonly/>
<input type="hidden" id="sesid" name="sesid" value="" readonly/>
<input type="hidden" id="holidaygroup" name="holidaygroup" value="" readonly/>
<?php include_once('views/holidays/holiday-modal.php');?>