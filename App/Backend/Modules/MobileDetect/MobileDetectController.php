<?php
namespace App\Backend\Modules\MobileDetect;
 
use \OCFram\BackController;
use \OCFram\HTTPRequest;
 
class MobileDetectController extends BackController
{
  public function executeIndex(HTTPRequest $request)
  {

   //require __DIR__.'/../../../../lib/vendor/autoload.php';
   $this->page->addVar('title', 'Detection of your device');

    $detect = new Mobile_Detect;
    // Any mobile device (phones or tablets).
    if ( $detect->isMobile() ) {
      $device = "not a mobile";
    }else
      $device = "not mobile";

    $this->page->addVar('device', $device);
  }
}