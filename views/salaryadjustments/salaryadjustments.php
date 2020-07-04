<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
	<div class="row">
			<div class="col-md-10"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Salary Adjustments</h3>
			 </div>
			 <div class="col-md-10"> 
				
			 </div>
		</div>
	</div>
	<div class="card-body"> 
		<div class="row">
			<div class="col-md-3 col-sm-3 col-xs-12" id="forsaladj-datefrom">
				<div class="input-group input-group-sm">
					<div class="input-group-prepend">
						<span class="input-group-text attendance_filter" id="inputGroup-sizing-sm">Date Froms</span>
					</div>
					<input id="saladj-datefrom" type="text" class="form-control form-control-sm" readonly value="<?php echo formatDate('D d M Y',TODAY);?>" style="background-color: white;" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
				</div>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-12" id="forsaladj-dateto">
				<div class="input-group input-group-sm">
					<div class="input-group-prepend">
						<span class="input-group-text attendance_filter" id="inputGroup-sizing-sm">Date Tos</span>
					</div>
					<input id="saladj-dateto" type="text" class="form-control form-control-sm" readonly value="<?php echo formatDate('D d M Y',TODAY);?>" style="background-color: white;" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
				</div>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-12" id="dataofclist" >
				<div class="input-group input-group-sm">
					<div class="input-group-prepend">
						<span class="input-group-text attendance_filter" id="inputGroup-sizing-sm">Office</span>
					</div>
					<select id="saladj-ofc" name="saladj-ofc" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm"></select>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-12" id="dataeelist" >
				<div class="input-group input-group-sm">
					<div class="input-group-prepend">
						<span class="input-group-text attendance_filter" for="txtee">Employees</span>
					</div>
					<select id="txtee" name="txtee" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm" style="display:inline-block;"></select>
				</div>
				
			</div>
			<div class="col-md-1 col-sm-1 col-xs-12" id="forbtnretrieve">
				<button type="button" id="btnRetrieve" class="btn btn-grad btn-danger btn-sm">
					<i class="fa fa-search" aria-hidden="true"></i>
				</button>
			</div>
		</div>
		<div style="padding-bottom:10px;"></div>
			<div class="table-responsive" width="101%">
				<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="/*width:98%;*/ table-layout:fixed;">
					<table id="salaryadjusmentsviewtbl" class="table table-sm table-bordered dataTable no-footer dt-responsive display nowrap" width="100%" cellspacing="0">
						<thead class="thead-dark">
							<tr style="cursor: pointer;">
								<th width="25%">Name</th>
								<th width="12%"><span id="frequencybasicpay">Adjustment Type</span></th>
								<th width="12%"><span id="frequencydeminimis">Semi-Monthly Deminimis</th>
								<th width="12%"><span id="frequencyrate">Semi-Monthly Rate</span></th>
							</tr>
						</thead>  
						<tbody style="font-size: 15px">
							
						</tbody>
						
					</table>
				</div>
			</div>
		
	</div>
</div>

<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" readonly/>
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" readonly/>
<input type="hidden" id="ofc" name="ofc" value="<?php echo $ofc; ?>" readonly/>
<input type="hidden" id="sesid" name="sesid" value="" readonly/>
<input type="hidden" id="holidaygroup" name="holidaygroup" value="" readonly/>
<input type="hidden" id="allofc" name="allofc" value="<?php echo hasAccess(2); ?>" readonly/>
<input type="hidden" id="txtldtls" name="txtldtls" value="" readonly/>
<input type="hidden" id="txtNoOfDays" name="txtNoOfDays" value="" readonly/>
<input type="hidden" id="txtDateFrom" name="txtDateFrom" value="<?php echo date("Y-m-d");?>" readonly/>
<input type="hidden" id="txtDateTo" name="txtDateTo" value="<?php echo date("Y-m-d");?>" readonly/>



