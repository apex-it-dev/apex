<!-- Nav Item - Abvt -->
<li class="nav-item mx-1"  data-toggle="tooltip" data-placement="bottom" title="abbreviation ">
	<a class="nav-link" href="<?php echo portal_URL;?>">
		<i class="fas fa-book fa-fw">
		</i>
	</a>
</li>



<!-- Nav Item - Requests -->
<li class="nav-item dropdown no-arrow mx-1"  data-toggle="tooltip" data-placement="bottom" title="Requests">
	<a class="nav-link dropdown-toggle" href="#" id="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<i class="fas fa-user-edit fa-fw">
<!--			Requests-->
		</i>
	</a>
	
	<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
    <h6 class="dropdown-header">
      Requests
    </h6>
    <a class="dropdown-item d-flex align-items-center" href="<?php echo portal_URL;?>request.php">
<!--
      <div class="mr-3">
        <div class="icon-circle bg-danger">
          <i class="fas fa-book text-white"></i>
        </div>
      </div>
-->
      <div>
        <span class="font-weight-bold">Abbreviation</span>
      </div>
    </a>
    <a class="dropdown-item d-flex align-items-center" href="<?php echo portal_URL;?>sp_folder_request.php">
      <div>
        <span class="font-weight-bold">Sharepoint Folder</span>
      </div>
    </a>
    <a class="dropdown-item d-flex align-items-center" href="<?php echo portal_URL;?>marketing_request.php">
      <div>
        <span class="font-weight-bold">Marketing</span>
      </div>
    </a>
  </div>
</li>

<?php $headeraccess = getAccessItems('SUPLIST','otapps'); if(isset($headeraccess->hasuplist)){ ?>
<li class="nav-item mx-1"  data-toggle="tooltip" data-placement="bottom" title="Supplier List ">
	<a class="nav-link" href="<?php echo portal_URL;?>suppliers.php">
  <i class="fas fa-clipboard-list fa-fw">
		</i>
	</a>
</li>
<?php } ?>

<!-- Nav Item - HERMES -->	
<?php
  include('hermesheader.php');
?>

