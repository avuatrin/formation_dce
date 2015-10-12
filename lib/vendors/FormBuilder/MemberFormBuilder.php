<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\ExistInDbValidator;
use \OCFram\EmailValidator;

class MemberFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => 'Pseudo',
            'name' => 'pseudo',
            'maxLength' => 20,
            'validators' => [
                new MaxLengthValidator('Le pseudo spécifié est trop long (20 caractères maximum)', 20),
                new NotNullValidator('Merci de spécifier un pseudo'),
                new ExistInDbValidator('Pseudo dejà existant',func_get_arg(0),'checkPseudoExist')
            ],
        ]))
            ->add(new StringField([
                'label' => 'Password',
                'name' => 'password',
                'type' => 'password',
                'maxLength' => 255,
                'validators' => [
                    new MaxLengthValidator('Le titre spécifié est trop long (255 caractères maximum)', 255),
                    new NotNullValidator('Merci de spécifier votre mot de passe'),
                ],
            ]))->add(new StringField([
                'label' => 'E-mail',
                'name' => 'email',
                'maxLength' => 150,
                'validators' => [
                    new MaxLengthValidator('Le titre spécifié est trop long (255 caractères maximum)', 255),
                    new EmailValidator('Merci d\'entrer un email valide'),
                ],
            ]))
            ->add(new TextField([
                'label' => 'Philosophy',
                'name' => 'philosophy',
                'rows' => 8,
                'cols' => 60,
                'validators' => []
            ]));
    }
}