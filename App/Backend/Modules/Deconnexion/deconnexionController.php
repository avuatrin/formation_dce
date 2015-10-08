<?php
namespace App\Backend\Modules\Deconnexion;
 
use \OCFram\BackController;
use \OCFram\HTTPRequest;
 
class DeconnexionController extends BackController
{
  public function executeDeconnexion(HTTPRequest $request)
  {
      $this->app->user()->setAuthenticated(false);
      session_destroy();
      $this->app->httpResponse()->redirect('/');
  }
}