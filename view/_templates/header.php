<!DOCTYPE html>
<html lang="en">
<head class="head">
    <meta charset="utf-8">
    <title>Incident Tracking</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet"
    	href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"
    	rel="stylesheet">
    <link rel="stylesheet"
    	href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css"
    	integrity="sha512-0V10q+b1Iumz67sVDL8LPFZEEavo6H/nBSyghr7mm9JEQkOAm91HNoZQRvQdjennBb/oEuW+8oZHVpIKq+d25g=="
    	crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
    	href="//<?php echo URL_DOMAIN?>/assets/fontawesome/5.8.1/css/all.min.css">
    <link rel="stylesheet"
    	href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css"
    	integrity="sha512-0V10q+b1Iumz67sVDL8LPFZEEavo6H/nBSyghr7mm9JEQkOAm91HNoZQRvQdjennBb/oEuW+8oZHVpIKq+d25g=="
    	crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    
    <!-- Main CSS Sheet -->
    <link href="<?php echo URL; ?>css/style.css?ver=<?php echo JS_VERSION?>" rel="stylesheet">
    
    <!--  NEEDED FOR ICONS  -->
    <script src="https://kit.fontawesome.com/a5a01e0e99.js" crossorigin="anonymous"></script>
</head>
    <div class='top_banner'>
    	<div id='top_header' class="container">
    		<img id='logo' src='<?php echo URL; ?>img/logo_pit_blue.png' alt='Jefferson County Police Department' width="175"></img>
        	<div id='title'><h1>Jefferson County Incident Tracker</h1></div>
        	<div id="time"></div><script id="time-top"> 
				var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'};
				var datetime = new Date().toLocaleDateString("en-US", options); 
            	document.getElementById("time").textContent = datetime;</script>
            <div class='navigation' id='navigation'>
            	<a href="<?php echo URL; ?>Home" id='home_tab' title='INPUT' onclick='set_current_tab(this.id);'>Input</a>
           	 	<a href="<?php echo URL; ?>Approve" id='approve_tab' title='APPROVE' onclick='set_current_tab(this.id);'>Approve</a>
           	 	<a href="<?php echo URL; ?>Display/table" id='display_tab' title='DISPLAY' onclick='set_current_tab(this.id);'>Display</a>
           	 	<a href="<?php echo URL; ?>Help" id='help_tab' title='HELP' onclick='set_current_tab(this.id);'><i class="fa-solid fa-circle-info fa-lg"></i></a>
           	 	<a href="<?php echo URL; ?>Login/logoutUser" title='LOGOUT'><i class="fa-solid fa-person-walking-arrow-right fa-lg"></i></a>
            </div>
    	</div>
    </div>
    <br/>
</html>