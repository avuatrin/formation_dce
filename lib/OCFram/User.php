<?php
namespace OCFram;

use \Entity\Member;

session_start();
 
class User extends ApplicationComponent
{

  public function getAttribute($attr)
  {
    return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
  }
 
  public function getFlash()
  {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
 
    return $flash;
  }
 
  public function hasFlash()
  {
    return isset($_SESSION['flash']);
  }
 
  public function isAuthenticated()
  {
    if ( isset($_SESSION['auth']) )
      return $_SESSION['auth'] ;
  }
 
  public function setAttribute($attr, $value)
  {
    $_SESSION[$attr] = $value;
  }

    /**Connecte un utilisateur
     * @param $member Member membre à connecter
     */
  public function setAuthenticated($member = null)
  {
      if ($member == null) {
          unset ($_SESSION['auth']);
          unset($_SESSION['member']);
      } else {
          $_SESSION['auth'] = true;
          $this->setMember($member);
        }
  }
 
  public function setFlash($value)
  {
    $_SESSION['flash'] = $value;
  }

  public function setMember($member){
      $_SESSION['member'] = $member;
  }

  public function member(){return $_SESSION['member']; }
}