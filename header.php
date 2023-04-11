<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Frankenstein</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!-- End plugin css for this page -->
    <link src="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="assets/css/main.css" type="text/css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="text/css"/>
  </head>
  <?php
  if(isset($_GET['logout']) && $_GET['logout'] == true)
  {
    User::logout();
  }
  ?>
  <body>
    <div id="preloader" class="flex-center-all">
      <span class="loader"></span>
    </div>
    <div id="systemMessageContainer"></div>