<?php
namespace App\Frontend\Modules\MobileDetect;
 
use \OCFram\BackController;
use \App\Frontend\AppController;
 
class MobileDetectController extends BackController
{
  use AppController;

  public function executeIndex()
  {

    $detect = new \Mobile_Detect;
    
    $device = "Your device is a ";
    if ( $detect->isMobile() && !$detect->isTablet() ) {
      $device .= "mobile";
    }else if( $detect->isMobile() ){
        $device .= "tablet";
    }else{
        $device .= "computer";
    }

    $this->page->addVar('device', $device);
    $this->run() ;
  }
}