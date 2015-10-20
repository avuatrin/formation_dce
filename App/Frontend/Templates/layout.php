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
        <p>Ca commence à se remplir !</p>
      </header>
      
      <nav>
          <ul>
            <?php if(isset($menu))
                foreach($menu as $link){
                  echo "<li> <a href='" . $link['uri'] . "'>" . $link['name'] . "</a></li>";
                }
            else
              echo '<ul> <li><a href="" onclick="history.go(-1)">Retour</a></li></ul>';
            ?>
          </ul>
      </nav>
      
      <div id="content-wrap">
        <section id="main">
          <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>
          
          <?= $content ?>
        </section>
      </div>
    
      <footer data-user-name="<?=$user->isAuthenticated() ? $_SESSION['member']->pseudo() : '';?>">
        <?=$user->isAuthenticated() ? 'Connected as : '.$_SESSION['member']->pseudo() : 'Not connected';?>
      </footer>
    </div>
  </body>
  <?= isset($scripts) ? $scripts : '' ?>
</html>