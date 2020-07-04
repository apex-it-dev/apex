<?php 
  include_once("inc/includes.php");
  include_once("inc/sessions.php");
  include_once("inc/functions.php");
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

 <title><?php echo TITLE; ?></title> 

  <!-- Custom fonts for this template-->
 <?php include_once('inc/bs-css.php'); ?>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php include_once('inc/sidebar.php');?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

       
       <?php include_once('inc/header.php');?>

        <!-- Begin Page Content -->
		<div class="container-fluid">
        	<?php include('views/comp-ben.php');?>
		</div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <?php include_once('inc/footer.php');?>

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->
  <div id="preloader" style="display: none;">Please wait...........
        <div><img src="<?php echo IMAGES; ?>loader.svg" id="preloader_image"></div>
    </div>

  <?php include_once('inc/scrolltotop.php');?>

  <?php include_once('inc/logoutmodal.php')?>

  <?php include_once('inc/jquery.php')?>

  <script src="<?php echo VIEWS . "leaves.js"; ?>"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
