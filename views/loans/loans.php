<?php
	if(!hasAccess(0)){
		echo '<script> window.location = "'. base_URL .'"; </script>';
		exit();
	}
?>
<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<div class="col-6"> 
				<h3 class="m-0 font-weight-bold text-gray-600">Loans</h3>
			 </div>
			 <div class="col-6 text-right"> 
			 	<button type="button" class="btn btn-danger btn-sm" id="addNewLoan" title="Add employee loan"><i class="fa fa-plus" style="font-size:14px"></i></button>
			 </div>
		</div>
	</div>
	<div class="card-body"> 
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div id="custom_search">
					<div class="input-group mb-1 input-group-sm col-sm-12 col-lg-4 col-md-4 float-right">
						<div class="input-group-prepend">
							<span class="input-group-text" style="background-color: #5a5c69; color: white; min-width: 60px;">Search</span>
						</div>
						<input type="text" class="form-control" id="loan_ee_list_search" title="Search">
					</div>
				</div>
				<div id="office_filter">
					<div class="input-group mb-1 input-group-sm col-sm-12 col-lg-3 col-md-3 float-right">
						<div class="input-group-prepend">
							<span class="input-group-text" style="background-color: #5a5c69; color: white; min-width: 60px;">Office</span>
						</div>
						<select class="form-control" id="officeList" title="Office filter">
							<option selected>All offices</option>
						</select>
					</div>
				</div>
				<div class="table-responsive">
					<table id="loan_ee_list" class="table table-sm table-bordered no-footer table-hover display nowrap">
						<thead class="thead-dark"></thead>
						<tbody></tbody>
					</table>	
				</div>	
			</div>
		</div>
	</div>
</div>

<input type="hidden" id="abaini" name="abaini" value="<?php echo $abaini; ?>" readonly/>
<input type="hidden" id="userid" name="userid" value="<?php echo $userid; ?>" readonly/>
<input type="hidden" id="ofc" name="ofc" value="<?php echo $ofc; ?>" readonly/>
<input type="hidden" id="sesid" name="sesid" value="" readonly/>
<input type="hidden" id="allofc" name="allofc" value="<?php echo hasAccess(2); ?>" readonly/>

<?php 
	include_once('views/loans/loan-of-ee.php');
	include_once('views/loans/add-loan.php');

	// $arr=array(array("name"=>"dName"),array("name"=>"bName"),array("name"=>"aName"),array("name"=>"eName"),array("name"=>"cName"));

	// usort($arr, function($a,$b) {
	// 		return ($a["name"] < $b["name"]);
	// 	});
	// print_r($arr);
?>

