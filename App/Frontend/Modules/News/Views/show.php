<p id="idNews"style="display: none;"><?=$news['id']?></p>
<p>Par <em><?= $news['auteur'] ?></em>, le <?= $news['dateAjout']->format('d/m/Y à H\hi') ?>
  <?php if ($user->isAuthenticated() ) {
    if (($user->member()->type() == Entity\Member::TYPE_AUTHOR && $user->member()->pseudo() == $news['auteur'])
        || ($user->member()->type() == Entity\Member::TYPE_ADMINISTRATOR)  ) { ?> -
      <a href="<?= $user->member()->type() == Entity\Member::TYPE_ADMINISTRATOR ? '/admin' :''; ?>/news-update-<?= $news['id'] ?>.html">Modifier</a> |
      <a href="<?= $user->member()->type() == Entity\Member::TYPE_ADMINISTRATOR ? '/admin' :''; ?>/news-delete-<?= $news['id'] ?>.html">Supprimer</a>
    <?php } } ?>

</p>
<h2><?= htmlspecialchars($news['titre']) ?></h2>
<p><?= nl2br(htmlspecialchars($news['contenu'])) ?></p>
 
<?php if ($news['dateAjout'] != $news['dateModif']) { ?>
  <p style="text-align: right;"><small><em>Modifiée le <?= $news['dateModif']->format('d/m/Y à H\hi') ?></em></small></p>
<?php } ?>
<br/>
<p> <strong>TAGS </strong>
<?php foreach($news->tagsArray() as $tag) {
    echo " - <a href=/tag-".htmlspecialchars($tag).".html>".htmlspecialchars($tag)."</a>";
}?>
</p>
<br/>
<div id="showNewComments"></div>


<?php
if (empty($comments))
{
?>
<p>Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>
<?php
}
 
foreach ($comments as $comment) {
  ?>
  <fieldset class="comment">
    <legend>
      <p id="idComment" style="display: none;"><?= $comment['id'] ?></p>
      Posté par
      <?php if (!$comment['email']) { ?>
        <a href="/member-<?= htmlspecialchars($comment['auteur']) ?>.html"><?= htmlspecialchars($comment['auteur']) ?></a>
      <?php } else { ?>
        <strong><?= htmlspecialchars($comment['auteur']) . " - " . htmlspecialchars($comment['email']); ?></strong>
      <?php } ?>

      le <?= $comment['date']->format('d/m/Y à H\hi') ?>
      <?php if ($user->isAuthenticated()) {
        if (($user->member()->type() == Entity\Member::TYPE_AUTHOR && $user->member()->pseudo() == $comment['auteur'])
            || ($user->member()->type() == Entity\Member::TYPE_ADMINISTRATOR)
        ) { ?> -
          <a href="<?= $user->member()->type() == Entity\Member::TYPE_ADMINISTRATOR ? '/admin/' : ''; ?>comment-update-<?= $comment['id'] ?>.html">Modifier</a> |
          <a href="<?= $user->member()->type() == Entity\Member::TYPE_ADMINISTRATOR ? '/admin/' : ''; ?>comment-delete-<?= $comment['id'] ?>.html">Supprimer</a>
        <?php }
      } ?>
    </legend>
    <p><?= nl2br(htmlspecialchars($comment['contenu'])) ?></p>
  </fieldset>
  <?php
}
?>
<div id="showOldComments"></div>
<p style="text-align:center;"><button id="buttonShowMoreComments">Voir tous les commentaires</button></p>
<p style="text-align:center;" id="displayComment"><button onclick="expand()" >Commenter</button></p>
<div id="cache">
  <?php require 'insertComment.php';?>
</div>

<script type="text/javascript" src="/JS/scriptAffichageCommenter.js"></script>