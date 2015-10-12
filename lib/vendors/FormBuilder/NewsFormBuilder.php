<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\SelectField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class NewsFormBuilder extends FormBuilder
{
  public function build($admin = false)
  {
      if ($admin){
          $this->form->add(new SelectField([
              'label' => 'Auteur',
              'name' => 'auteur',
              'options' => func_get_arg(1)->getList(func_get_arg(2)),
              'validators' => [
                  new NotNullValidator('Merci de spécifier un auteur')
              ],
          ]));
        }
      $this->form->add(new StringField([
        'label' => 'Titre',
        'name' => 'titre',
        'maxLength' => 100,
        'validators' => [
          new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
          new NotNullValidator('Merci de spécifier le titre de la news'),
        ],
       ]))
       ->add(new TextField([
        'label' => 'Contenu',
        'name' => 'contenu',
        'rows' => 8,
        'cols' => 60,
        'validators' => [
          new NotNullValidator('Merci de spécifier le contenu de la news'),
        ],
       ]));
  }
}