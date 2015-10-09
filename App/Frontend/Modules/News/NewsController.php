<?php
namespace App\Frontend\Modules\News;
 
use Entity\Member;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
 
class NewsController extends BackController
{
  public function executeIndex(HTTPRequest $request)
  {
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
    $this->page->addVar('listeNews', $listeNews);
  }
 
  public function executeShow(HTTPRequest $request)
  {
    $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));

    if (empty($news))
    {
      $this->app->httpResponse()->redirect404();
    }

    $this->page->addVar('title', $news->titre());
    $this->page->addVar('news', $news);
    $this->page->addVar('comments', $this->managers->getManagerOf('Comments')->getListOf($news->id()));
  }
 
  public function executeInsertComment(HTTPRequest $request)
  {
    // Si le formulaire a été envoyé.
    if ($request->method() == 'POST') {
      $comment = new Comment([
          'news' => $request->getData('news'),
          'auteur' => $request->postData('auteur'),
          'contenu' => $request->postData('contenu')
      ]);
    } else {
      $comment = new Comment;
    }


    if (!$this->managers->getManagerOf('News')->getUnique($request->getData('news'))) {
        if ($request->method() == 'POST')
          $this->app()->user()->setFlash('La news a été supprimée pendant que vous la commentiez, désolé !');
        $this->app()->httpResponse()->redirect404();
    }

    $formBuilder = new CommentFormBuilder($comment);
    $formBuilder->build();
 
    $form = $formBuilder->form();

    $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

    if ($formHandler->process())
    {
      $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');
 
      $this->app->httpResponse()->redirect('news-'.$request->getData('news').'.html');
    }
 
    $this->page->addVar('comment', $comment);
    $this->page->addVar('form', $form->createView());
    $this->page->addVar('title', 'Ajout d\'un commentaire');
  }

  public function executeWrite(HTTPRequest $request){
    $this->processForm($request);

    $this->page->addVar('title', 'Ajout d\'une news');
  }

  public function processForm(HTTPRequest $request)
  {
    if ($request->method() == 'POST')
    {
      $news = new News([
          'auteur' => $request->postData('auteur'),
          'titre' => $request->postData('titre'),
          'contenu' => $request->postData('contenu')
      ]);

      if ($request->getExists('id'))
      {
        $news->setId($request->getData('id'));
      }
    }
    else {
      // L'identifiant de la news est transmis si on veut la modifier
      if ($request->getExists('id')) {
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));
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

      $this->app->httpResponse()->redirect('/admin/');
    }

    $this->page->addVar('form', $form->createView());
  }

    public function executeUpdateComment(HTTPRequest $request)
    {
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
            if($comment == NULL)
                $this->app()->httpResponse()->redirect404();
        }

        $formBuilder = new CommentFormBuilder($comment, $this->managers->getManagerOf('Members'), $this->app()->user()->member()->type() == Member::TYPE_ADMINISTRATOR);
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

        if ($formHandler->process())
        {
            $this->app->user()->setFlash('Le commentaire a bien été modifié');

            $this->app->httpResponse()->redirect('/admin/');
        }

        $this->page->addVar('form', $form->createView());
    }
}