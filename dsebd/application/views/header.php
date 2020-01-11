<!doctype html>
<html>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="refresh" content="180">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/tableStyle.css">

</head>
<body>
	
	<div id="menubar">
	  <div id="welcome">
	    <h1><a href="<?php echo base_url();?>">Zaman Analysis</a></h1>
	  </div><!--close welcome-->
      <div id="menu_items">
	    <ul id="menu">
          <li <?php if($page_no == 0) echo "class='current'"; ?> ><a href="<?php echo base_url();?>index.php/daily">Daily Analysis</a></li>
          <li <?php if($page_no == 1) echo "class='current'"; ?> ><a href="<?php echo base_url();?>index.php/hourly">Hourly Analysis</a></li>
          <li <?php if($page_no == 2) echo "class='current'"; ?> ><a href="<?php echo base_url();?>index.php/details">Details Information</a></li>
          <!--<li <?php if($page_no == 3) echo "class='current'"; ?> ><a href="#">About</a></li>-->
		  <li <?php if($page_no == 4) echo "class='current'"; ?> ><a href="<?php echo base_url();?>index.php/logout">Logout</a></li>
        </ul>
      </div><!--close menu-->
    </div><!--close menubar-->