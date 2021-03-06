<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Working Days</h3>
			 </div>
			<div class="col-md-2">
				<?php if(isset($accessitems->cansave)){ ?>
					<button id="btnAddWorkingday" type="reset" class="btn btn-sm btn-danger float-right">&nbsp;<i class="fa fa-plus" style="font-size:14px"></i>&nbsp;Add Working Days&nbsp;&nbsp;</button>
					<!-- <button id="btnBack" type="reset" class="btn btn-sm btn-danger float-right" style="background-color: #b11c21;"><i class="fas fa-arrow-left"></i> BACK</button> -->
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="card-body"> 
		<div class="row"> 
			<div class="col-md-12">  
				<!-- <div class="profile-head">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="workingday-tab" data-toggle="tab" href="#workingday" role="tab" aria-controls="workingday" aria-selected="true">Workingday</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="event-tab" data-toggle="tab" href="#event" role="tab" aria-controls="workingday" aria-selected="true">Events</a>
						</li>
					</ul>
				</div> -->
<!--			Add New Workingday-->
				<div class="tab-content workingday-tab" id="myTabContent">
					<!-- <div class="tab-pane fade show active" id="workingday" role="tabpanel" aria-labelledby="workingday-tab">
						<div class="row">
							<div class="col-lg-6">
								<h3>Workingday List</h3>
							</div>
							<div class="col-lg-6" id="addrowbtn" align="right">
								<button id="btnAddWorkingday" type="reset" class="btn btn-sm btn-primary float-right">&nbsp;<i class="fa fa-plus" style="font-size:14px"></i>&nbsp;ADD NEW&nbsp;&nbsp;</button>
							</div>
						</div> -->
					<!-- <div class="form-group row">		 -->
						<div class="table-responsive dtcontainer">
							<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
								<table class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" id="workingdayListDataTable" width="100%" cellspacing="0">
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


<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" disabled readonly/>
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" disabled readonly/>
<input type="hidden" id="ofc" name="ofc" value="<?php echo $ofc; ?>" disabled readonly/>
<input type="hidden" id="sesid" name="sesid" value="" disabled readonly/>
<input type="hidden" id="workingdaygroup" name="workingdaygroup" value="" disabled readonly/>
<?php
	$output = '';
	if(isset($accessitems)){
		foreach ($accessitems as $item) {
			if(isset($item['foreignkey']))
				if(strpos($item['foreignkey'], 'SO') !== FALSE) 
					$output .= $item['foreignkey'] .',';
		}
		$output = rtrim($output, ',');
	}
?>
<input type="hidden" id="viewofc" name="viewofc" value="<?php echo $output; unset($output); ?>" disabled readonly/>
<input type="hidden" id="canupdate" name="canupdate" value="<?php echo isset($accessitems->canupdate); ?>" disabled readonly/>
<?php include_once('views/workingday/workingday-modal.php');?>