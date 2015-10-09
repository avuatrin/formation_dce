<?php
namespace OCFram;

class ExistInDbValidator extends Validator{

    protected $manager,
            $field;

    public function __construct($errorMessage, $manager, $field)
    {
        parent::__construct($errorMessage);

        $this->setManager($manager);
        $this->setField($field);
    }

    public function isValid($value)
    {
        $method = $this->field;
        return !$this->manager->$method($value);
    }

    public function setManager($manager){ $this->manager = $manager; }

    public function setField($field){
        if(is_callable([$this->manager,$field]))
            $this->field = $field;
        else
            throw new \RuntimeException('L\'action "'.$field.'" n\'est pas définie sur ce manager');
    }

}