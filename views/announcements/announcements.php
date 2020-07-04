<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Holidays</h3>
			 </div>
			<div class="col-md-2">
				<button id="btnAddHoliday" type="reset" class="btn btn-sm btn-danger float-right">&nbsp;<i class="fa fa-plus" style="font-size:14px"></i>&nbsp;Add Holiday&nbsp;&nbsp;</button>
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
				<div class="tab-content holiday-tab" id="myTabContent">
					<!-- <div class="tab-pane fade show active" id="holiday" role="tabpanel" aria-labelledby="holiday-tab">
						<div class="row">
							<div class="col-lg-6">
								<h3>Holiday List</h3>
							</div>
							<div class="col-lg-6" id="addrowbtn" align="right">
								<button id="btnAddHoliday" type="reset" class="btn btn-sm btn-primary float-right">&nbsp;<i class="fa fa-plus" style="font-size:14px"></i>&nbsp;ADD NEW&nbsp;&nbsp;</button>
							</div>
						</div> -->
					<!-- <div class="form-group row">		 -->
						<div class="table-responsive dtcontainer">
							<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
								<table class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" id="holidayListDataTable" width="100%" cellspacing="0">
									<thead class="thead-dark">
										<tr>
											<th width="25%">Title</th>
											<th width="10%">Office</th>
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
					<!-- </div> -->
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
<?php include_once('views/announcements/announcements-modal.php');?>