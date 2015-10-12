<?php
namespace FormBuilder;

use OCFram\ExistInDbValidator;
use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\SelectField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class CommentFormBuilder extends FormBuilder
{
  public function build()
  {
    $this->form->/*add(new StringField([
        'label' => 'Auteur',
        'name' => 'auteur',
        'maxLength' => 50,
        'validators' => [
          new MaxLengthValidator('L\'auteur spécifié est trop long (50 caractères maximum)', 50),
          new NotNullValidator('Merci de spécifier l\'auteur du commentaire'),
        ],
       ]))->*/add(new SelectField([
        'label' => 'Auteur',
        'name' => 'auteur',
        'options' => func_get_arg(0)->getList(func_get_arg(1)),
        'validators' => [],
    ]))
       ->add(new TextField([
        'label' => 'Contenu',
        'name' => 'contenu',
        'rows' => 7,
        'cols' => 50,
        'validators' => [
          new NotNullValidator('Merci de spécifier votre commentaire'),
        ],
       ]));
  }
}