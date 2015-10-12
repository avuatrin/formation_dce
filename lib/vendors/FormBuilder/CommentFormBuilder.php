<?php
namespace FormBuilder;

use OCFram\ExistInDbValidator;
use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\SelectField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\EmailValidator;

class CommentFormBuilder extends FormBuilder
{
  public function build($auth=false)
  {
    if(!$auth)
        $this->form->add(new StringField([
              'label' => 'Auteur',
              'name' => 'auteur',
              'maxLength' => 50,
              'validators' => [
                  new MaxLengthValidator('L\'auteur spécifié est trop long (50 caractères maximum)', 50),
                  new NotNullValidator('Merci de spécifier l\'auteur du commentaire'),
                  new ExistInDbValidator('Pseudo dejà existant',func_get_arg(1),'checkPseudoExist')
              ]
        ]))->add(new StringField([
            'label' => 'E-mail',
            'name' => 'email',
            'maxLength' => 150,
            'validators' => [
                new MaxLengthValidator('L\'auteur spécifié est trop long (150 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier votre email'),
                new EmailValidator('Cet email est invalide')
            ]
        ]));
    else{
      $this->form->add(new SelectField([
          'label' => 'Auteur',
          'name' => 'auteur',
          'options' => func_get_arg(1)->getList(func_get_arg(2)),
          'validators' => [
              new NotNullValidator('Merci de spécifier un auteur')
          ],
      ]));
  }
      $this->form->add(new TextField([
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