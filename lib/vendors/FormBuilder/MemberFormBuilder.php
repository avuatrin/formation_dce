<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\ExistInDbValidator;

class MemberFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => 'Pseudo',
            'name' => 'pseudo',
            'maxLength' => 20,
            'validators' => [
                new MaxLengthValidator('Le pseudo sp�cifi� est trop long (20 caract�res maximum)', 20),
                new NotNullValidator('Merci de sp�cifier un pseudo'),
                new ExistInDbValidator('Pseudo dej� existant',func_get_arg(0),'checkPseudoExist')
            ],
        ]))
            ->add(new StringField([
                'label' => 'Password',
                'name' => 'password',
                'type' => 'password',
                'maxLength' => 255,
                'validators' => [
                    new MaxLengthValidator('Le titre sp�cifi� est trop long (255 caract�res maximum)', 255),
                    new NotNullValidator('Merci de sp�cifier votre mot de passe'),
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