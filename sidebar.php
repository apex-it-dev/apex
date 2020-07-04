<!-- Sidebar -->
	<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

	  <!-- Sidebar - Brand -->
	  <div class="card-profile mb-sm-3"> 
	  <div class="card-avatar">
		<a href="index.php">
			<img src="<?php echo base_URL; ?>img/ees/<?php echo $_SESSION['ee']['avatar']; ?>">
		</a>
	  </div>
	  <div class="card-body text-center d-sm-none d-lg-block">
		<h6 class="card-category text-gray-400"><?php echo $eejt?></h6>
		<h4 class="card-title"><?php echo $eename?></h4>
		<p class="card-desription text-gray-200"><?php echo $ofc?></p>
	  </div>
	  <div class="sidebarbutton" style=" padding: 5px;padding-left: 15px;">
		  <button type="button" class="btn btn-danger btn-sm" style="padding-left:2px">Sign In</button>
		  <button type="button" class="btn btn-secondary btn-sm" style="padding-left:2px">Sign Out</button>
		  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#CallIn">Call In</button>
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
		  <a class="nav-link collapsed" href="directory.php" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          	<i class="fas fa-fw fa-address-book"></i>
		  	<span>Contact List</span>
          </a>
		  <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" style="">
			  <div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item" href="organogram.php">Organogram</a>
				<a class="collapse-item" href="directory.php">Directory</a>
			  </div>
        </div>
	  </li>
<!--
		<a class="nav-link" href="directory.php">
		  <i class="fas fa-fw fa-address-book"></i>
		  <span>Contact List</span></a>
-->
<!--
		  <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionSidebar" >
		  <div class="dropdown-container">
			<a href="directory.php">Organogram</a>
			<a href="directory.php">Directory</a>
		  </div>
		 </div>
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
		  <i class="fas fa-fw fa-address-book"></i>
		  <span>Leave and Attendance</span></a>
	  </li>
	  <li class="nav-item" style="display: none">
		<a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
		  <i class="fas fa-fw fa-cog"></i>
		  <span>Attendance</span>
		</a>
		<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" >
		  <div class="bg-white collapse-inner p-2 rounded text-center">
	<!--		<h6 class="collapse-header">Custom Components:</h6>-->
				   <table class="table table-sm table-hover">
					<thead class="">
					  <th>Day</th>
					  <th>In</th>
					  <th>Out</th>
					</thead>
					<tbody>
					  <tr>
						<td>Mon</td>
						<td>8:54</td>
						<td>6:15</td>
					  </tr>
					  <tr>
						<td>Tue</td>
						<td>8:36</td>
						<td>7:17</td>
					  </tr>
					  <tr>
						<td>Wed</td>
						<td class="text-danger">9:11</td>
						<td>6:23</td>
					  </tr>
					 <tr>
						<td>Thu</td>
						<td>8:41</td>
						<td>6:23</td>
					  </tr>
					 <tr>
						<td>Fri</td>
						<td>8:44</td>
						<td>8:11</td>
					  </tr>
					</tbody>
				  </table>
		  </div>
		</div>
	  </li>
	  <li class="nav-item" style="display: none">
		<a class="nav-link" href="comp-ben.php">
		  <i class="fas fa-fw fa-address-book"></i>
		  <span>Payroll</span></a>
	  </li>
	  <!-- Divider -->
	  <hr class="sidebar-divider">

	  <!-- Sidebar Toggler (Sidebar) -->
	  <div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	  </div>

	</ul>
<?php include_once('views/call-in-modal.php');?>


<!-- End of Sidebar -->