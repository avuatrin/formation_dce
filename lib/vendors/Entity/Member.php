<?php
namespace Entity;

use \OCFram\Entity;

class Member extends Entity
{
    protected $id,
        $pseudo,
        $password,
        $philosophy,
        $type;

    const TYPE_ADMINISTRATOR = 1;
    const TYPE_AUTHOR = 2;

    const PASSWORD_INVALIDE = 12;
    const TYPE_INVALIDE = 13;
    const PSEUDO_INVALIDE = 11;
    const PSEUDO_TAKEN = 14;

    public function isValid(){
        return !(empty($this->pseudo) || empty($this->password));
    }

    public function setPseudo($pseudo){
        if (!is_string($pseudo) || empty($pseudo)) {
            $this->erreurs[] = self::PSEUDO_INVALIDE;
        }
        $this->pseudo = $pseudo;
    }

    public function setPassword($password){
        if (!is_string($password) || empty($password)) {
            $this->erreurs[] = self::PASSWORD_INVALIDE;
        }
        $this->password = $password;
    }

    public function setType($type){
        if ($type == self::TYPE_ADMINISTRATOR OR $type == self::TYPE_AUTHOR ) {
            $this->erreurs[] = self::TYPE_INVALIDE;
            $this->type = self::TYPE_AUTHOR;
        }
        $this->$type = (int) $type;
    }

    public function setId($id){ $this->id = (int) $id; }

    public function setPhilosophy( $philosophy){$this->philosophy = $philosophy; }

    public function pseudo()  {  return $this->pseudo;  }

    public function type() {  return (int) $this->type;   }

    public function philosophy() {   return $this->philosophy;  }

    public function password(){  return $this->password;   }

    public function id(){ return $this->id; }

}