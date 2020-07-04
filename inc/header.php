 <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <!-- <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form> -->

          <!-- Mobile: Topbar Navbar -->
          <ul class="navbar-nav ml-auto d-xl-none d-lg-none d-md-none d-sm-none d-flex">
            <li class="nav-item dropdown no-arrow" id="headernav" style="font-size:25px;">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-chevron-circle-left rotate-menu" style="transform: rotate(-90deg);"></i>
                <!-- onclick="//$('.rotate-menu').toggleClass('down');" -->
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown_mobile">
                <a class="dropdown-item" href="<?php echo portal_URL;?>">
                  <i class="fas fa-book fa-fw"></i> Abbreviation
                </a>
                <?php $headeraccess = getAccessItems('SUPLIST','otapps'); if(isset($headeraccess->hasuplist)){ ?>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?php echo portal_URL;?>suppliers.php">
                    <i class="fas fa-clipboard-list fa-fw"></i> Supplier
                  </a>
                <?php }?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo portal_URL;?>request.php">
                  <i class="fas fa-user-edit fa-fw"></i> Requests
                </a>
              </div>
            </li>

            
            <?php
              include('hermesheader.php');
            ?>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <!-- <span class="mr-2 d-none d-lg-inline text-gray-600 small">Valerie Luna</span> -->
                <img class="img-profile rounded-circle" src="<?php echo $avatarpath; ?>">
              </a>
              <!-- Dropdown - User Information -->
              <?php include('inc/menuprofile.php');?>
            </li>
          </ul>


          <!-- Desktop: Topbar Navbar -->
          <ul class="navbar-nav ml-auto d-xl-flex d-lg-flex d-md-flex d-sm-flex d-none">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <!-- <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a> -->
              <!-- Dropdown - Messages -->
              <!-- <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li> -->

            <?php include_once('inc/notifcationalerts.php');?>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <!-- <span class="mr-2 d-none d-lg-inline text-gray-600 small">Valerie Luna</span> -->
                <img class="img-profile rounded-circle" src="<?php echo $avatarpath; ?>">
              </a>
              <!-- Dropdown - User Information -->
              <?php include('inc/menuprofile.php');?>
            </li>

          </ul>

        </nav>
<!-- End of Topbar -->