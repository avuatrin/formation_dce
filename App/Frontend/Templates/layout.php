<!DOCTYPE html>
<html>
  <head>
    <title>
      <?= isset($title) ? $title : 'Mon super site' ?>
    </title>
    
    <meta charset="utf-8" />
    
    <link rel="stylesheet" href="/css/Envision.css" type="text/css" />
  </head>
  
  <body>
    <div id="wrap">
      <header>
        <h1><a href="/">Mon super site</a></h1>
        <p>Comment ça, il n'y a presque rien ?</p>
      </header>
      
      <nav>

        <ul>
          <li><a href="/">Accueil</a></li>
          <li><a href="/mobile-detect.html">Detection</a></li>
          <?php if ($user->isAuthenticated() ) {
            if ($user->member()->type() == Entity\Member::TYPE_AUTHOR) { ?>
              <li><a href="/news-insert.html">Ecrire une news</a></li>
              <li><a href="/deconnexion.html">Disconnection </a></li>
            <?php } else if ($user->member()->type() == Entity\Member::TYPE_ADMINISTRATOR) { ?>
              <li><a href="/admin/">Admin</a></li>
              <li><a href="/admin/news-insert.html">Ajouter une news</a></li>
              <li><a href="/deconnexion.html">Disconnection</a></li>
            <?php }
          }else { ?>
            <li><a href="/connexion.html">Connection</a></li>
            <li><a href="/inscription.html">Inscription</a> </li>
          <?php } ?>
        </ul>
      </nav>
      
      <div id="content-wrap">
        <section id="main">
          <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>
          
          <?= $content ?>
        </section>
      </div>
    
      <footer><?=$user->isAuthenticated() ? 'Connected as : '.$_SESSION['member']->pseudo() : 'Not connected';?></footer>
    </div>
  </body>
</html>