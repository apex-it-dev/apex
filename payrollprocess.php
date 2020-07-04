<?php 
  include_once("inc/includes.php");
  include_once("inc/sessions.php");
  // print_r($_SESSION);
  // exit();
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo FAVICO; ?>" type="image/ico" />

   <title><?php echo TITLE; ?></title> 


    <!-- Custom fonts for this template-->
    <!-- <link rel="stylesheet" type="text/css" href="vendor/datatables/fixedColumns.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/datatables/fixedHeader.dataTables.min.css"> -->
    <?php include_once('inc/bs-css.php'); ?>
    
  </head>

  <body id="page-top">
    
    <!-- Page Wrapper -->
    <div id="wrapper">
      <!-- <div class="sidebar-primary"> -->
      <?php include_once('inc/sidebar.php');?>
    <!-- </div> -->
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

          <!-- Topbar -->
          <?php include_once('inc/header.php');?>

          <!-- Begin Page Content -->
  		    <div class="container-fluid">
            <?php include_once('views/payroll/payrollprocess.php')?> 
  		    </div>
          <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <?php include_once('inc/footer.php');?>
      </div>
      <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    <?php include_once('inc/loader.php');?>

    <?php include_once('inc/scrolltotop.php');?>

    <?php include_once('inc/logoutmodal.php');?>

    <?php include_once('inc/jquery.php');?>

    
    <!-- <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="vendor/transpose.js/transpose.js"></script> -->

    <?php 
      srcInit('vendor/moment.js/moment.js'); 
      srcInit('vendor/datatables/jquery.dataTables.min.js'); 
      srcInit('vendor/datatables/dataTables.bootstrap4.min.js'); 
      srcInit('vendor/datatables/sum().js'); 
      srcInit('vendor/transpose.js/transpose.js'); 
      // srcInit('vendor/datatables/dataTables.fixedColumns.min.js'); 
      // srcInit('vendor/datatables/dataTables.fixedHeader.min.js'); 
      srcInit(VIEWS . '/payroll/payroll.js'); 
      srcInit(VIEWS . '/payroll/multistep.js'); 
    ?>


  </body>

</html>
