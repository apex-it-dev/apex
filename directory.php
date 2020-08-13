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
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="<?php echo FAVICO; ?>" type="image/ico" />

   <title><?php echo TITLE; ?></title> 

  <!-- Custom fonts for this template-->
 <?php include_once('inc/bs-css.php'); ?>

	<!--	orgchart css-->
	<!-- <link rel="stylesheet" href="vendor/treant-js/Treant.css">
	<link rel="stylesheet" href="css/org-chart.css"> -->
  <!-- <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->
  <?php srcInit('vendor/datatables/dataTables.bootstrap4.min.css'); ?>

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
           <?php include_once('views/contact-list.php');?>
		</div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <?php include_once('inc/footer.php');?>

    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->
    <?php include_once('inc/loader.php');?>

  <?php include_once('inc/scrolltotop.php');?>

  <?php include_once('inc/logoutmodal.php')?>

  <?php include_once('inc/jquery.php')?>
	
  <!--orgchart js-->

  <!-- <script src="vendor/treant-js/Treant.js"></script>
  <script src="vendor/treant-js/vendor/raphael.js"></script>
  <script src="build/org-chart.js"></script> -->

  <!-- <script src="vendor/datatables/jquery.dataTables.min.js"></script>
   <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
   <script src="js/demo/datatables-demo.js"></script> -->
  
  <!-- <script src="<?php //echo VIEWS . "contactlist.js"; ?>"></script> -->

  

  <?php srcInit('vendor/moment.js/moment.js'); ?>
  <?php srcInit('vendor/datatables/jquery.dataTables.min.js'); ?>
  <?php srcInit('vendor/datatables/dataTables.bootstrap4.min.js'); ?>
  <?php srcInit(VIEWS . 'contactlist.js'); ?>
	
 <!-- Page level plugins -->
	<!-- <script>
		new Treant( chart_config );
	</script> -->

</body>

</html>
