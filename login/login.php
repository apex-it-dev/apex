<?php 
  include_once("../inc/includes.php");
  include_once("../api/models/database.php");
  if(isset($_POST['log']) && !empty($_POST['log']) && $_POST['log'] == 1){

    if(isset($_POST['abaini']) && !empty($_POST['abaini']) && isset($_POST['abaemail']) && !empty($_POST['abaemail'])){
      // print_r($_POST);
      // exit();
      include_once("../api/models/abauser_model.php");
      session_start();
      $userid = $_POST['userid'];

      $usr = new abaUserModel;
      $user = $usr->getUser($userid)['rows'][0];
      $access = $usr->getUserAccess($userid)['rows'];
      $access_bydept = $usr->getUserAccess($user['department'])['rows'];
      $usr->closeDB();
      unset($usr);
      // print_r($access);
      // exit();
      $abaini   = $user['abaini'];
      $abaemail = $user['workemail'];
      $name     = $user['fname'] .' '. $user['lname'];
      $jobtitle = $user['designationname'];
      $ofc      = $user['office'];
      $ofcname  = $user['officename'];
      // $avatar   = $user['avatarorig'];
      $dept     = $user['department'];
      $deptdesc = $user['deptdesc'];
      $rank     = $user['positiongrade'];
      $rankdesc = $user['rankdesc'];
      $pos      = $user['designation'];


      $avatartmp = $user['avatarorig'];
      $avatar = $avatartmp == "" || $avatartmp == null ? 'default.svg' : (file_exists('../img/ees/' . explode('?', $avatartmp)[0]) ? $avatartmp : 'default.svg');

      // user details
      $_SESSION['ee'] = array("abaini"    => $abaini,
                              "abaUser"   => $abaini,
                              "abaemail"  => $abaemail,
                              "userid"    => $userid,
                              "name"      => $name,
                              "jobtitle"  => $jobtitle,
                              "ofc"       => $ofc,
                              "ofcname"   => $ofcname,
                              "avatar"    => $avatar,
                              "avatarpath"=> "img/ees/$avatar",
                              "rank"      => $rank,
                              "rankdesc"  => $rankdesc,
                              "dept"      => $dept,
                              "deptdesc"  => $deptdesc,
                              "pos"       => $pos);

      $getaccess_ee = getAccess($access);
      $useraccess_ee = $getaccess_ee['access'];
      $appnames_ee = $getaccess_ee['appnames'];

      $getaccess_dept = getAccess($access_bydept);
      $useraccess_dept = $getaccess_dept['access'];
      $appnames_dept = $getaccess_dept['appnames'];

      $finalaccess_ee = genObject($appnames_ee, $useraccess_ee);
      $finalaccess_dept = genObject($appnames_dept, $useraccess_dept);

      
      foreach($finalaccess_dept as $keyapp=>$eachapp) {
        foreach($eachapp as $keymenu=>$eachmenu) {
          if(!isset($finalaccess_ee[$keyapp][$keymenu]))
            $finalaccess_ee[$keyapp][$keymenu] = $finalaccess_dept[$keyapp][$keymenu];
        }
      }


      $useraccess = array();
      foreach($finalaccess_ee as $keyapp=>$eachapp) {
        foreach($eachapp as $keymenu=>$eachmenu) {
          foreach ($eachmenu as $keythis=>$thismenu) {
            if($thismenu == 1 || (gettype($thismenu) === 'array' && $thismenu['status'] == 1)) { 
              $useraccess[$keyapp][$keymenu][$keythis] = $thismenu;
            }
          }
        }
      }
      

      $_SESSION['useraccess'] = $useraccess;  
      

      $redir = base_URL."profile.php";
      if(isset($_GET['r'])) $redir = base64_decode($_GET['r']);
      header("Location: $redir");
      exit();
    }
    header("Location: login.php");
    exit();
  }


  function getAccess($access) {
    $useraccess_ee = array();
    $appnames = array();
    $counter = array();
    foreach ($access as $key => $eachaccess) {
      $appname = strtolower($eachaccess['module']);
      $appnames[] = $appname;
      if(!isset($counter[$appname])) $counter[$appname] = 0;
      $useraccess_ee[$appname][$counter[$appname]]['menuid'] = $eachaccess['menuid'];
      $useraccess_ee[$appname][$counter[$appname]]['accessname'] = $eachaccess['accessname'];
      $useraccess_ee[$appname][$counter[$appname]]['status'] = $eachaccess['status'];
      $useraccess_ee[$appname][$counter[$appname]]['foreignkey'] = $eachaccess['foreignkey'];
      $counter[$appname]++;
    }
    return array('access' => $useraccess_ee, 'appnames' => $appnames);
  }

  function genObject($appnames, $useraccess_db) {
    $appnames = array_unique($appnames);
    $useraccess = array();
    foreach ($appnames as $eachapp) {
      $menuids = array_unique(array_map(function($eachaccess) {
        return $eachaccess['menuid'];
      }, $useraccess_db[$eachapp]));

      foreach ($menuids as $eachid) {
        foreach ($useraccess_db[$eachapp] as $eachitem) {
          if($eachitem['menuid'] == $eachid && ($eachitem['status'] == 1 || $eachitem['status'] == 0)) {
            $foreignkey = $eachitem['foreignkey'] != '' ? array('foreignkey' => $eachitem['foreignkey'], 'status' => $eachitem['status']) : $eachitem['status'];
            $useraccess[$eachapp][$eachid][$eachitem['accessname']] = $foreignkey; 
          }
        }
      }
    }
    return $useraccess;
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo FAVICO; ?>" type="image/ico" />

    <title><?php echo TITLE; ?></title>

    <?php include_once('bootstrap.php'); ?>
  <link href="https://fonts.googleapis.com/css?family=Montserrat|Varela+Round&display=swap" rel="stylesheet">
  <?php srcInit('css/style-for-login.css'); ?>
  </head>

  <body class="login">
    <div>

      <div class="login_wrapper">
        <div class="intro_text">
              <h1>ACE System</h1>
            <h2>Login to start</h2> 
        </div>
        <div class="animate form login_form">
          <section class="login_content">
            <form method="Post" id="frmLogin" name="frmLogin">
             <span id="divLogin">
                <div class="row">
                  <div class="col-lg-12 col-sm-12 col-xs-12">
                    <input type="text" class="form-control" placeholder="Username (abaini: i.e. pmhe)" required="" id="txtUsername" name="txtUsername" />
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 col-sm-12 col-xs-12">
                    <input type="password" class="form-control" placeholder="Password" required="" id="txtPassword" name="txtPassword" />
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 col-sm-12 col-xs-12 text-center ">
                    <input type="button" id="btnLogin" class="btn btn-danger" name="btnLogin" value="Login" />
                    <!-- <button class="btn btn-danger" id="btnLogin" name="btnLogin">Log in</button> -->
                  </div>
                  <!-- <div class="col-lg-6 col-sm-6 col-xs-12 text-left mt-5"> -->
                    <a class="reset_pass pull-left" href="#" onClick="gotoForgotPW();" style="margin-left:1em;">Forgot Password</a>
                  <!-- </div> -->
                  <!-- <div class="col-lg-6 col-sm-6 col-xs-12 text-right mt-5"> -->
                    <a class="reset_pass pull-right" href="<?php echo hermes_URL;?>portal" style="margin-right:1em;">Abbreviation</a>
                  <!-- </div> -->
                </div>
              </span>
              <span id="divForgotPW" style="display: none;">
                <div class="row">
                  <div class="col-lg-12 col-sm-12 col-xs-12">
                    <input type="text" class="form-control" placeholder="Email Address" required="" id="txtEmailAddr" name="txtEmailAddr" />
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 col-sm-12 col-xs-12 text-center ">
                    <input type="button" id="btnForgot" class="btn btn-danger" name="btnForgot" value="Send Password" style="width: 100%;" />
                    <!-- <button class="btn btn-danger" id="btnLogin" name="btnLogin">Log in</button> -->
                  </div>
                  <div class="col-lg-12 col-sm-12 col-xs-12 text-left mt-5">
                    <a class="reset_pass" href="#" onClick="return gotoLogin();">Go Back to Login</a>
                  </div>
                </div>
              </span>
              <div class="clearfix"></div>

              <div class="separator">

                <div>
                  <img class="login_logo" src="images/logo.png">
                  <p>©2017 All Rights Reserved. Privacy and Terms</p>
                </div>
              </div>
              <input type="hidden" id="abaini" name="abaini" value="" />
              <input type="hidden" id="abaemail" name="abaemail" value="" />
              <input type="hidden" id="userid" name="userid" value="" />
              <input type="hidden" id="eename" name="eename" value="" />
              <input type="hidden" id="eejobtitle" name="eejobtitle" value="" />
              <input type="hidden" id="ofc" name="ofc" value="" />
              <input type="hidden" id="log" name="log" value="1" />
              <input type="hidden" id="pw" name="pw" value="" />
              <input type="hidden" id="avatar" name="avatar" value="" />
              <input type="hidden" id="dept" name="dept" value="" />
              <input type="hidden" id="rank" name="rank" value="" />
              <input type="hidden" id="pos" name="pos" value="" />
            </form>

          </section>
        </div>
      </div>
    </div>
    <?php include_once('../inc/loader.php');?>
    <?php include_once('jquery.php'); ?>
    <?php srcInit('login.js'); ?>
  </body>
</html>