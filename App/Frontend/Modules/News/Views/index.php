<?php
foreach ($listeNews as $news)
{
  ?>
  <h2><a href="<?=$this->app->router()->getUrl('show','News', [$news['id']])?>"><?= htmlspecialchars($news['titre']) ?></a></h2>
  <p><?= htmlspecialchars(nl2br($news['contenu'])); ?></p>
  <?php
}