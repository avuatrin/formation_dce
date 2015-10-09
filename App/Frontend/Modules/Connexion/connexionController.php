<?php
namespace App\Frontend\Modules\Connexion;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \OCFram\FormHandler;
use \FormBuilder\ConnexionFormBuilder;
use \Entity\Member;
use \Model\MembersManager;


class ConnexionController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        if ($request->method() == 'POST')
        {
            $member = new Member([
                'pseudo' => $request->postData('pseudo'),
                'password' => $request->postData('password')
            ]);
        }
        else
        {
            $member = new Member;
        }

        $formBuilder = new ConnexionFormBuilder($member);
        $formBuilder->build($this->managers->getManagerOf('Members'));

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Members'), $request);
        /**@var $memberManager MembersManager */
        $memberManager = $this->managers->getManagerOf('Members');
        if ($formHandler->verify())
        {
            $this->app->user()->setFlash('Vous etes désormais connécté');
            $this->app->user()->setAuthenticated( $memberManager->getUnique(($memberManager->connect($request->postData('pseudo'),$request->postData('password'))) ) );
            $this->app->httpResponse()->redirect('/');

        }

        $this->page->addVar('member', $member);
        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', 'Connexion');
    }

}