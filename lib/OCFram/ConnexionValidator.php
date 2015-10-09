<?php
namespace OCFram;

class ConnexionValidator extends Validator
{
    protected $pseudo,
            $manager;

    public function __construct($errorMessage, $manager, $pseudo)
    {
        parent::__construct($errorMessage);

        $this->setPseudo($pseudo);
        $this->setManager($manager);
    }

    public function isValid($value)
    {
        return $this->manager->connect($this->pseudo, $value);
    }

    public function setPseudo($pseudo) {  $this->pseudo = $pseudo;  }

    public function setManager($manager){ $this->manager = $manager; }
}