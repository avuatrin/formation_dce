<?php
namespace App\Backend\Modules\News;
 
use Model\CommentsManager;
use Model\NewsManager;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\News;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \OCFram\FormHandler;
 
class NewsController extends BackController
{
  public function executeDelete(HTTPRequest $request)
  {
    $newsId = $request->getData('id');

    /** @var NewsManager $NewsManager */
    $NewsManager = $this->managers->getManagerOf('News');
    $News = $NewsManager->getUnique($newsId);
    if ($News === null)
      $this->app()->httpResponse()->redirect404();



    /** @var CommentsManager $CommentManager */
    $CommentManager = $this->managers->getManagerOf('Comments');
    $CommentManager->deleteFromNews($newsId);
    $NewsManager->delete($newsId);
 
    $this->app->user()->setFlash('La news a bien été supprimée !');
 
    $this->app->httpResponse()->redirect('.');
  }
 
  public function executeDeleteComment(HTTPRequest $request)
  {
    /** @var CommentsManager $CommentManager */
    $CommentManager = $this->managers->getManagerOf('Comments');
    if($request->getData('id') == NULL )
      $this->app()->httpResponse()->redirect404();
    $CommentManager->delete($request->getData('id'));
 
    $this->app->user()->setFlash('Le commentaire a bien été supprimé !');
 
    $this->app->httpResponse()->redirect('.');
  }
 
  public function executeIndex(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Gestion des news');
 
    $manager = $this->managers->getManagerOf('News');
 
    $this->page->addVar('listeNews', $manager->getList());
    $this->page->addVar('nombreNews', $manager->count());
  }
 
  public function executeInsert(HTTPRequest $request)
  {
    $this->processForm($request);
 
    $this->page->addVar('title', 'Ajout d\'une news');
  }
 
  public function executeUpdate(HTTPRequest $request)
  {
    $this->processForm($request);
 
    $this->page->addVar('title', 'Modification d\'une news');
  }
 
  public function executeUpdateComment(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Modification d\'un commentaire');
 
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
      if($comment == NULL)
        $this->app()->httpResponse()->redirect404();
    }
 
    $formBuilder = new CommentFormBuilder($comment);
    $formBuilder->build(true, $this->managers->getManagerOf('Members'), $this->app()->user()->member()->id());
 
    $form = $formBuilder->form();
 
    $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);
 
    if ($formHandler->process())
    {
      $this->app->user()->setFlash('Le commentaire a bien été modifié');
 
      $this->app->httpResponse()->redirect('/admin/');
    }
 
    $this->page->addVar('form', $form->createView());
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
        if($news == NULL)
          $this->app()->httpResponse()->redirect404();
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
  }
}