<?php
namespace App\Backend;
 
use \OCFram\Application;
use \Entity\Member;
 
class BackendApplication extends Application
{
  public function __construct()
  {
    parent::__construct();
 
    $this->name = 'Backend';
  }
 
  public function run()
  {
    if ($this->user->isAuthenticated() && $this->user->member()->type() == Member::TYPE_ADMINISTRATOR)
    {
      $controller = $this->getController();
    }
    else
    {
      $this->user()->setFlash('Vous n\'etes pas administrateur');
      $controller = new Modules\Connexion\ConnexionController($this, 'Connexion', 'index');
    }
 
    $controller->execute();
 
    $this->httpResponse->setPage($controller->page());
    $this->httpResponse->send();
  }
}