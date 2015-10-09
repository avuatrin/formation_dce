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
                new MaxLengthValidator('Le pseudo sp�cifi� est trop long (50 caract�res maximum)', 50),
                new NotNullValidator('Merci de sp�cifier le pseudo')
            ],
        ]);

        $this->form->add($pseudoField)
            ->add(new StringField([
                'label' => 'Password',
                'name' => 'password',
                'type' => 'password',
                'maxLength' => 50,
                'validators' => [
                    new NotNullValidator('Merci de sp�cifier votre mot de passe'),
                    new ConnexionValidator('Le mot de passe ne correspond pas au pseudo',func_get_arg(0), $this->form->entity()->pseudo())
                ],
            ]));
    }
}