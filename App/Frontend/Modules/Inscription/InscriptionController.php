<?php
namespace App\Frontend\Modules\Inscription;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \OCFram\FormHandler;
use \Entity\Member;
use \FormBuilder\MemberFormBuilder;

class InscriptionController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        if ($request->method() == 'POST')
        {
            $member = new Member([
                'pseudo' => $request->postData('pseudo'),
                'password' => $request->postData('password'),
                'email' => $request->postData('email'),
                'philosophy' => $request->postData('philosophy'),
                'type' => Member::TYPE_AUTHOR
            ]);
        }
        else
        {
            $member = new Member;
        }
        $membersManager = $this->managers->getManagerOf('Members');

        $formBuilder = new MemberFormBuilder($member);
        $formBuilder->build($this->managers->getManagerOf('Members'));

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $membersManager, $request);

        if ($formHandler->process())
        {
            $this->app->user()->setFlash('Merci de votre inscription !');
            $this->app->httpResponse()->redirect('/');

        }

        $this->page->addVar('member', $member);
        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', 'Ajout d\'un membre');
    }

}