<?php
	    // Include and instantiate the class.

    require "../lib/vendor/autoload.php";
    $detect = new Mobile_Detect;
    
    $device = "Your device is a ";
    if ( $detect->isMobile() && !$detect->isTablet() ) {
     	$device .= "mobile";
    }else if( $detect->isMobile() ){
        $device .= "tablet";
    }else{
        $device .= "computer";
    }

    echo $device;