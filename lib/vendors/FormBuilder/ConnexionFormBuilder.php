<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\ConnexionValidator;

class ConnexionFormBuilder extends FormBuilder
{
    public function build()
    {
        $pseudoField = new StringField([
            'label' => 'Pseudo',
            'name' => 'pseudo',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('Le pseudo spécifié est trop long (50 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier le pseudo')
            ],
        ]);

        $this->form->add($pseudoField)
            ->add(new StringField([
                'label' => 'Password',
                'name' => 'password',
                'type' => 'password',
                'maxLength' => 50,
                'validators' => [
                    new NotNullValidator('Merci de spécifier votre mot de passe'),
                    new ConnexionValidator('Le mot de passe ne correspond pas au pseudo',func_get_arg(0), $this->form->entity()->pseudo())
                ],
            ]));
    }
}