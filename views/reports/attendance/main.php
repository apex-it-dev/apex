<?php 
	if(!hasAccess(0)){
		echo '<script> window.location = "'. base_URL .'"; </script>';
		exit();
	}
	$page = 'page';
?>
<div class="card shadow mb-4">
	<div class="card-header py-3 border-bottom-danger">
		<div class="row">
			<h3 class="m-0 ml-3 font-weight-bold text-gray-600">Leave Report</h3>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<!--Employee Summary-->
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane reports-tab fade show active" id="reports" role="tabpanel" aria-labelledby="reports-tab">
						<?php include_once("$page/_reports.php"); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php unset($page); ?>

<?php //include_once('views/reportsgenerator/eesummaryModal.php');?>
<?php //include_once('views/reportsgenerator/eesummaryModal1.php');?>
<?php //include_once('views/reportsgenerator/eesummaryModal2.php');?>
<?php //include_once('views/reportsgenerator/eetimesheetmodal.php');?>
<?php //include_once('views/reportsgenerator/eetimesheetmodal1.php');?>
<?php //include_once('views/reportsgenerator/eetimesheetmodal2.php');?>