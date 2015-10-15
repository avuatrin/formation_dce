<?php
namespace FormBuilder;

use \OCFram\StringField;
use \OCFram\Field;
use \OCFram\NotNullValidator;

class NewCommentsFormBuilder
{
    protected $fields = [],
                $values = [];

    public function __construct($values){
        foreach ($values as $name=>$value){
            $this->values[$name] = $value;
        }
    }

    public function build()
    {
        $this->add(new StringField([
            'label' => 'News',
            'name' => 'news_id',
            'type' => 'number',
            'validators' => [
                new NotNullValidator('Merci de spécifier une news'),
            ],
        ]))->add(new StringField([
            'label'=>'Commentaire',
            'name'=>'comment_id_last',
            'type' => 'number',
            'validators'=>[
                new NotNullValidator('Merci de spécifier un commentaire'),
            ]
        ]));
    }

    public function createView(){
        $view = '';
        // On génère un par un les champs du formulaire.
        foreach ($this->fields as $field)
        {
            $view .= $field->buildWidget().'<br />';
        }

        return $view;
    }

    public function createJson(){
        $JSON = [];
        foreach ($this->fields as $field)
        {
            /** @var Field $field */
            $field->isValid();
            $JSON['form'][$field->name()]= $field->buildJSON();
        }
        return $JSON;
    }

    public function isValid(){
        $valid = true;
        // On vérifie que tous les champs sont valides.
        foreach ($this->fields as $field)
        {
            if (!$field->isValid())
            {
                $valid = false;
            }
        }
        return $valid;
    }

    public function add(Field $field){
        $attr = $field->name();
        $field->setValue($this->values[$attr]); // On assigne la valeur correspondante au champ.

        $this->fields[] = $field; // On ajoute le champ passé en argument à la liste des champs.
        return $this;
    }

    public function process($manager, $function){
        /** @var \Entity\Comment $comment */
        $JSON['form'] =[];
        foreach($resultats = $manager->$function($this->values['comment_id_last'], $this->values['news_id']) as $comment){
            array_push($JSON['form'], array('comment'=>array('auteur'=>$comment->auteur(), 'contenu'=>$comment->contenu(), 'date'=>$comment->date(), 'id'=>$comment->id() ) ) );
        }
        return $JSON;
    }


}