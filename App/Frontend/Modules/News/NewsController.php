<?php
namespace App\Frontend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \Model\CommentsManager;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \OCFram\FormHandler;
use \Model\NewsManager;
use \Entity\News;
use \OCFram\Link;
use \OCFram\Page;
use \App\Frontend\AppController;
use \Entity\Member;
 
class NewsController extends BackController
{
    use AppController;

    public function executeError(){
        $this->run();
        $this->page->addVar('title', 'Oups...');
        $this->page->addVar('user', $this->app()->user());
    }

    public function executeIndex(HTTPRequest $request)
    {
    $this->run() ;
    $nombreNews = $this->app->config()->get('nombre_news');

    $nombreCaracteres = $this->app->config()->get('nombre_caracteres');

    // On ajoute une définition pour le titre.
    $this->page->addVar('title', 'Liste des '.$nombreNews.' dernières news');

    // On récupère le manager des news.
    $manager = $this->managers->getManagerOf('News');

    $listeNews = $manager->getList(0, $nombreNews);

    foreach ($listeNews as $news)
    {
      if (strlen($news->contenu()) > $nombreCaracteres)
      {
        $debut = substr($news->contenu(), 0, $nombreCaracteres);
        $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

        $news->setContenu($debut);
      }
    }

    // On ajoute la variable $listeNews à la vue.
    $this->page()->addVar('user', $this->app()->user());
    $this->page->addVar('listeNews', $listeNews);
    }

    public function executeShow(HTTPRequest $request)
    {
    $this->page()->addVar('user', $this->app()->user());
    $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));
    $nombreCommentaires = $this->app->config()->get('nombre_commentaires');

    if (empty($news))
    {
        $this->generateOtherPage('error', 'La news n°'.$request->getData('id').' n\'existe pas');
        return;
    }

    $this->processComment($request, 'id');

    $this->page->addVar('title', $news->titre());
    $this->page->addVar('news', $news);
    $this->page->addVar('comments', $this->managers->getManagerOf('Comments')->getListOf($news->id(),0, $nombreCommentaires)  );
    $this->run() ;
    }

    public function executeInsertComment(HTTPRequest $request)
    {
    $this->processComment($request, 'news');

    $this->page->addVar('title', 'Ajout d\'un commentaire');
    }

    public function processComment(HTTPRequest$request, $page){

      // Si le formulaire a été envoyé.
      if ($request->method() == 'POST') {
          $comment = new Comment([
              'news' => $request->getData($page),
              'auteur' => $request->postData('auteur'),
              'email' => $request->postData('email'),
              'contenu' => $request->postData('contenu')
          ]);
      } else {
          $comment = new Comment;
      }

      if (!$this->managers->getManagerOf('News')->getUnique($request->getData($page))) {
          if ($request->method() == 'POST')
              $this->app()->user()->setFlash('La news a été supprimée pendant que vous la commentiez, désolé !');
          var_dump('yoko ono');
          $this->generateOtherPage('error', 'Le commentaire n°'.$request->getData($page).' n\'existe pas');
          return;
      }

      $formBuilder = new CommentFormBuilder($comment);
      $formBuilder->build($this->app()->user()->isAuthenticated(), $this->managers->getManagerOf('Members') , $this->app()->user()->isAuthenticated() ? $this->app()->user()->member()->id() : null);

      $form = $formBuilder->form();

      $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

      if ($formHandler->process())
      {
          $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');

          $this->app->httpResponse()->redirect($this->app->router()->getUrl('show','News', [$request->getData($page)]) );
      }

        $this->page->addVar('comment', $comment);
        $this->page->addVar('newsId', $request->getData($page));
        $this->page->addVar('form', $form->createView());
        $this->page()->addVar('user', $this->app()->user());
    }

    public function executeUpdate(HTTPRequest $request){
    $this->processForm($request);

    $this->page->addVar('title', 'Ajout d\'une news');
    }

    public function processForm(HTTPRequest $request)
    {
    if(!$this->app()->user()->isAuthenticated()){
        $this->app->user()->setFlash('Connectez vous ou inscrivez vous pour accéder à cette section');
        $this->app->httpResponse()->redirect('/');
    }
    if ($request->method() == 'POST')
    {
      $news = new News([
          'auteur' => $this->app()->user()->member()->id(),
          'titre' => $request->postData('titre'),
          'contenu' => $request->postData('contenu'),
          'tags' => $request->postData('tags')
      ]);

      if ($request->getExists('id'))
      {
        $news->setId($request->getData('id'));
      }
    }
    else {
      // L'identifiant de la news est transmis si on veut la modifier
      if ($request->getExists('id')) {
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'), true);
        if($news == NULL) {
            $this->generateOtherPage('error', 'La news n°' . $request->getData('id') . ' n\'existe pas');
            return;
        }
      } else {
        $news = new News;
      }
    }

    $formBuilder = new NewsFormBuilder($news);
    $formBuilder->build();

    $form = $formBuilder->form();

    $formHandler = new FormHandler($form, $this->managers->getManagerOf('News'), $request);

    if ($formHandler->process())
    {
      $this->app->user()->setFlash($news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');

      $this->app->httpResponse()->redirect('/');
    }

        $this->managers->getManagerOf('News')->saveTags($news);
        $this->page->addVar('form', $form->createView() );
        $this->page()->addVar('user', $this->app()->user());
    }

    public function executeUpdateComment(HTTPRequest $request)
    {
        if(!$this->app()->user()->isAuthenticated()){
            $this->app->user()->setFlash('Connectez vous ou inscrivez vous pour accéder à cette section');
            $this->app->httpResponse()->redirect('/');
        }

        $this->page->addVar('title', 'Modification de votre commentaire');

        if ($request->method() == 'POST')
        {
            $comment = new Comment([
                'id' => $request->getData('id'),
                    'auteur' => $request->postData('auteur'),
                'contenu' => $request->postData('contenu')
            ]);
        }
        else
        {
            $comment = $this->managers->getManagerOf('Comments')->get($request->getData('id'));
            if($comment == NULL) {
                $this->generateOtherPage('error', 'Le commentaire n°'.$request->getData('id').' n\'existe pas');
                return;
            }
        }

        if($comment->auteur() != $this->app()->user()->member()->id()){
            $this->app->user()->setFlash('Ce n\'est pas votre commentaire');
            $this->app->httpResponse()->redirect('/');
        }

        $formBuilder = new CommentFormBuilder($comment);

        $formBuilder->build(true,$this->managers->getManagerOf('Members') , $this->app()->user()->member()->id());

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

        if ($formHandler->process())
        {
            $this->app->user()->setFlash('Le commentaire a bien été modifié');

            $this->app->httpResponse()->redirect('/');
        }

        $this->page->addVar('form', $form->createView());
    }

    public function executeDeleteComment(HTTPRequest $request)
    {
        if(!$this->app()->user()->isAuthenticated()){
            $this->app->user()->setFlash('Connectez vous ou inscrivez vous pour réaliser cette action');
            $this->app->httpResponse()->redirect('/');
        }
        /** @var CommentsManager $CommentManager */
        $CommentManager = $this->managers->getManagerOf('Comments');
        if($request->getData('id') == NULL ) {
            $this->generateOtherPage('error', 'Vous devez préciser un identifiant de commentaire');
            return;
        }
        if( $this->app()->user()->isAuthenticated() )
            $CommentManager->delete( $request->getData('id'), $this->app()->user()->member()->id() );

        $this->app->user()->setFlash('Le commentaire a bien été supprimé !');

        $this->app->httpResponse()->redirect('.');
    }

    public function executeInsert(HTTPRequest $request)
    {
    $this->setCustomMenu([new Link([
        'name' => 'Donner des idées',
        'uri' => 'http://www.wikipedia.com',
        'access' => [
            Member::TYPE_ADMINISTRATOR,
            Member::NOT_CONNECTED,
            Member::TYPE_AUTHOR,
        ],
    ]), (new Link([
        'name' => 'Accueil',
        'uri' => '/',
        'access' => [
            Member::TYPE_ADMINISTRATOR,
            Member::NOT_CONNECTED,
            Member::TYPE_AUTHOR,
        ],
    ]))]);
    $this->run();
    $this->processForm($request);

    $this->page->addVar('title', 'Ajout d\'une news');
    }

    public function executeDelete(HTTPRequest $request)
    {

        /** @var NewsManager $NewsManager */
        $NewsManager = $this->managers->getManagerOf('News');
        $News = $NewsManager->getUnique($request->getData('id'),false);
        if ($News === null) {
            $this->generateOtherPage('error', 'Le commentaire n°' . $request->getData('id') . ' n\'existe pas');
            return;
        }

        if(!$this->app()->user()->isAuthenticated()){
            $this->app->user()->setFlash('Ce n\'est pas votre news');
            $this->app->httpResponse()->redirect('/');
        }

        /** @var CommentsManager $CommentManager */
        $CommentManager = $this->managers->getManagerOf('Comments');
        $CommentManager->deleteFromNews($News->id());
        $NewsManager->delete($News->id());

        $this->app->user()->setFlash('La news a bien été supprimée !');

        $this->app->httpResponse()->redirect('.');
    }

    public function executeTestInsertComment(HTTPRequest $request)
    {
        if ($request->method() == 'POST') {
            $comment = new Comment([
                'news' => $request->getData('news'),
                'auteur' => $request->postData('auteur'),
                'email' => $request->postData('email'),
                'contenu' => $request->postData('contenu')
            ]);
        } else {
            $comment = new Comment;
        }

        if (!$this->managers->getManagerOf('News')->getUnique($request->getData('news'))) {
            if ($request->method() == 'POST')
                $this->app()->user()->setFlash('La news a été supprimée pendant que vous la commentiez, désolé !');
            $this->generateOtherPage('error', 'La news n°'.$request->getData('news').' n\'existe pas');
            return;
        }

        $formBuilder = new CommentFormBuilder($comment);
        $formBuilder->build($this->app()->user()->isAuthenticated(), $this->managers->getManagerOf('Members') , $this->app()->user()->isAuthenticated() ? $this->app()->user()->member()->id() : null);

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);
        echo json_encode($formHandler->processJSON());
        exit();

    }

    public function executeTagShow(HTTPRequest $request) {
    $nombreNews = $this->app->config()->get('nombre_news');
    $nombreCaracteres = $this->app->config()->get('nombre_caracteres');

    // On ajoute une définition pour le titre.
    $this->page->addVar('title', 'Liste des news parlant de ' . $request->getData('tag'));

    // On récupère le manager des news.
    $manager = $this->managers->getManagerOf('News');

    $listeNews = $manager->getListByTag($request->getData('tag'), 0, $nombreNews);

    foreach ($listeNews as $news) {
        if (strlen($news->contenu()) > $nombreCaracteres) {
            $debut = substr($news->contenu(), 0, $nombreCaracteres);
            $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

            $news->setContenu($debut);
        }
    }

    // On ajoute la variable $listeNews à la vue.
    $this->page->addVar('listeNews', $listeNews);
    $this->run() ;
    }

    public function executeGetOldComments(HTTPRequest $request){
        $this->processJSONComment(
            $this->managers->getManagerOf('Comments')->getOldComments(
                $request->postData('comment_old_id'),
                $request->postData('news_id'),
                $nombreCommentairesAffiche = $this->app->config()->get('nombre_commentaires')
            )
        );
    }

    public function executeGetNewComments(HTTPRequest $request){
        $this->processJSONComment(
            $this->managers->getManagerOf('Comments')->getNewComments(
                $request->postData('comment_last_id'),
                $request->postData('news_id')
            )
        );
    }

    protected function processJSONComment($comments){
        $this->page()->setReturnType(Page::CONTENT_JSON);
        $this->page()->addVar('json',json_encode($comments) );
    }

}