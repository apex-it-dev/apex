<!-- Dropdown - User Information -->
<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
  <?php if(isset($_SESSION['useraccess']['adminsys']['ADMINSYS']['hasadminsys'])){ ?>
  <a class="dropdown-item" href="<?php echo adminsys_URL; ?>">
    <i class="fas fa-tools fa-sm fa-fw mr-2 text-gray-800"></i>
    Adminsys
  </a>
  <div class="dropdown-divider"></div>
  <?php } ?>
  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePassword">
    <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-800"></i>
    Change Password
  </a>
   <div class="dropdown-divider"></div>
  <a class="dropdown-item" href="profile.php" style="display: none">
    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-800"></i>
    Profile
  </a>
  <a class="dropdown-item" href="#" style="display: none">
    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-800"></i>
    Settings
  </a>
  <a class="dropdown-item" href="#" style="display: none">
    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-800"></i>
    Activity Log
  </a>
  <!-- <div class="dropdown-divider"></div> -->
  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-800"></i>
    Logout
  </a>
</div>
