<?php
namespace App\Frontend\Modules\MobileDetect;
 
use \OCFram\BackController;
use \OCFram\HTTPRequest;
 
class MobileDetectController extends BackController
{
  public function executeIndex(HTTPRequest $request)
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
  }
}