	<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

	  <!-- Sidebar - Brand -->
	  <div class="card-profile mb-sm-3"> 
	  <div class="card-avatar">
		<a href="profile.php">
			<img src="<?php echo $avatarpath; ?>">
		</a>
	  </div>
	  <div class="card-body text-center d-sm-none d-lg-block">
		<h6 class="card-category text-gray-400"><?php echo $eejt; ?></h6>
		<h4 class="card-title"><?php echo $eename; ?></h4>
		<p class="card-desription text-gray-200"><?php echo $ofcname; ?></p>
	  </div>
		<div class="sidebarbutton" style=" padding: 5px; text-align: center;">
			  <button title="Loading..." type="button" id="btnLoading" class="btn btn-info btn-sm btn-load" style="margin-bottom: 2px;" disabled>Please wait...</button>
			  <button title="Sign in" type="button" id="btnsbSignIn" class="btn btn-success btn-sm">Sign In</button>
			  <button title="Sign out" type="button" id="btnsbSignOut" class="btn btn-danger btn-sm">Sign Out</button>
			  <button title="Call in" type="button" id="btnsbCallin" class="btn btn-primary btn-sm">Call In</button>
			  <input type="hidden" id="txtsbuserid" name="txtsbuserid" value="<?php echo $userid;?>" />
			  <!-- <button type="button" class="btn btn-primary btn-sm">Call In</button> -->
		  </div>
	  </div>

	  <!-- Divider -->
	  <hr class="sidebar-divider my-0">

	  <!-- Nav Item - Dashboard -->
	  <li class="nav-item active" style="display: none">
		<a class="nav-link" href="index.php">
		  <i class="fas fa-fw fa-tachometer-alt"></i>
		  <span>Dashboard</span></a>
	  </li>
	  <li class="nav-item">
		<a class="nav-link" href="profile.php">
		  <i class="fas fa-fw fa-user"></i>
		  <span>Profile</span></a>
	  </li>
		<li class="nav-item">
			<a class="nav-link" href="organogram.php">
			<i class="fas fa-fw fa-sitemap"></i>
			<span>Organogram</span></a>
		</li>

	  <li class="nav-item">
		<a class="nav-link" href="directory.php">
		  <i class="fas fa-fw fa-address-book"></i>
		  <span>Employee Directory</span></a>
	  </li>
	  <!-- <li class="nav-item">
		  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          	<i class="fas fa-fw fa-address-book"></i>
		  	<span>Contact List</span>
          </a>
		  <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
			  <div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item" href="#">Organogram</a>
				<a class="collapse-item" href="directory.php">Employees Directory</a>
			  </div>
           </div>
	  </li> -->
<!--
		<a class="nav-link" href="directory.php">
		  <i class="fas fa-fw fa-address-book"></i>
		  <span>Contact List</span></a>
-->
	<!--
	  <li class="nav-item">
		<a class="nav-link" href="organogram.php">
		  <i class="fas fa-fw fa-users"></i>
		  <span>Organogram</span></a>
	  </li>
	-->
	  <li class="nav-item">
		<a class="nav-link" href="leaves.php">
		  <i class="fas fa-fw fa-file-signature"></i>
		  <span>Leave and Attendance</span></a>
	  </li>
	  <?php if(false){ ?>
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
			<i class="fas fa-fw fa-money-bill"></i>
			<span>Payroll</span>
			</a>
			<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<a class="collapse-item" href="payrollmanagement.php">View Payroll</a>
					<!-- <a class="collapse-item" href="payroll.php">File Payroll Dispute</a> -->
					<!-- <a class="collapse-item" href="payrollmanagement.php">Manage Payroll</a> -->
					<a class="collapse-item" href="loans.php">Loans</a>
					<!-- <a class="collapse-item" href="salaryadjustments.php">Salary Adjusments</a> -->
					<!-- <a class="collapse-item" href="paymentmiscellaneous.php">Miscellaneous</a> -->
					<!-- <a class="collapse-item" href="payrollmanagement.php">Payroll Settings</a> -->
				</div>
			</div>
		</li>
	  <?php }?>
	  <?php if(false){ ?>
		<li class="nav-item">
			<a class="nav-link" href="recruitment.php">
			<i class="fas fa-fw fa-user-plus"></i>
			<span>Recruitment</span></a>
		</li>
	  <?php }?>
	<?php 
		$employee = FALSE;
		$leave = FALSE;
		if(isset($useraccess['REPORTSEEMGT'])) 			if(count($useraccess['REPORTSEEMGT']) > 0) 			$employee = TRUE;
		if(isset($useraccess['REPORTSLEAVEMGT'])) 		if(count($useraccess['REPORTSLEAVEMGT']) > 0) 		$leave = TRUE;
		if($employee || $leave) {
	?>
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
			<i class="fas fa-wf fa-chart-bar"></i>
				<span>Reports</span>
			</a>
			<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<?php if($employee){ ?>		<a class="collapse-item" href="reportsofemployee.php">Employee</a>		<?php } ?>
					<?php if($leave){ ?>		<a class="collapse-item" href="reportsforleave.php">Leave</a>			<?php } ?>
					<!-- <?php if($abaini == 'raoc') { ?> <a class="collapse-item" href="reportsforattendance.php">Attendance</a> <?php } ?> -->
				</div>
			</div>
		</li>
	<?php } ?>
	<?php 
		$employee = FALSE;
		$holiday = FALSE;
		$workingday = FALSE;
		if(isset($useraccess['EEMGT'])) 			if(count($useraccess['EEMGT']) > 0) 			$employee = TRUE;
		if(isset($useraccess['HOLIDAYMGT'])) 		if(count($useraccess['HOLIDAYMGT']) > 0) 		$holiday = TRUE;
		if(isset($useraccess['WORKINGDAYSMGT'])) 	if(count($useraccess['WORKINGDAYSMGT']) > 0) 	$workingday = TRUE;
		if($employee || $holiday || $workingday) {
	?>
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			<i class="fas fa-wf fa-user-cog"></i>
				<span>Admin Settings</span>
			</a>
			<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<?php if($employee){ ?>		<a class="collapse-item" href="employees.php">Employees</a>			<?php } ?>
					<?php if($holiday){ ?>		<a class="collapse-item" href="holidays.php">Holidays</a>			<?php } ?>
					<?php if($workingday){ ?>	<a class="collapse-item" href="workingdays.php">Working Days</a>	<?php } ?>
				</div>
			</div>
		</li>
	<?php } ?>
	  <!-- Divider -->
	  <hr class="sidebar-divider">

	  <!-- Sidebar Toggler (Sidebar) -->
	  <div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	  </div>

	</ul>
<!-- End of Sidebar