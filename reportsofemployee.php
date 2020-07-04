<?php 
  include_once("inc/includes.php");
  include_once("inc/sessions.php");
  
  $menuid = 'REPORTSEEMGT';
  if(isset($useraccess[$menuid])){
    $accessitems = (object) $useraccess[$menuid];
  } else {
    header("Location: " . getModule() . ".404");
  }

  $views = VIEWS . 'reports/employee';
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
    <?php include_once('inc/bs-css.php'); ?>
    <?php srcInit('vendor/datatables/buttons.dataTables-collection.min.css'); ?>
    <?php srcInit("$views/../main.css"); ?>
    <?php srcInit('vendor/tail.select/tail.select-default.min.css'); ?>
    <?php srcInit('vendor/datatables/colReorder.dataTables.min.css'); ?>

    <?php srcInit('vendor/daterangepicker/daterangepicker.css'); ?>

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
            <?php include_once("$views/main.php")?>
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
    <?php include_once('inc/logoutmodal.php')?>
    <?php include_once('inc/jquery.php')?>

    <?php srcInit('vendor/chart.js/Chart.min.js'); ?>
    <?php srcInit('vendor/moment.js/moment.js'); ?>

    <?php srcInit('vendor/datatables/jquery.dataTables.min.js'); ?>
    <?php srcInit('vendor/datatables/dataTables.bootstrap4.min.js'); ?>
    <?php srcInit('vendor/datatables/dataTables.buttons.min.js'); ?>
    <?php //srcInit('vendor/datatables/buttons.bootstrap4.min.js'); ?>
    <?php srcInit('vendor/datatables/buttons.print.min.js'); ?>
    <?php srcInit('vendor/datatables/pdfmake.min.js'); ?>
    <?php srcInit('vendor/datatables/vfs_fonts.js'); ?>
    <?php srcInit('vendor/datatables/jszip.min.js'); ?>
    <?php srcInit('vendor/datatables/buttons.html5.min.js'); ?>
    <?php srcInit('vendor/datatables/buttons.colVis.min.js'); ?>
    <?php srcInit('vendor/datatables/dataTables.colReorder.min.js'); ?>
    <?php srcInit('vendor/tail.select/tail.select-full.min.js'); ?>

    <?php srcInit('vendor/daterangepicker/daterangepicker.min.js'); ?>

    
    
    <?php srcInit("$views/js/_reports.js"); ?>
    <?php srcInit("$views/js/_timesheet.js"); ?>
    <?php srcInit("$views/js/main.js"); ?>



  </body>

</html>