<h2>Ajouter un commentaire</h2>
<form action="<?= 'commenter-'.$newsId.'.html'; ?>" method="post">
  <p>
    <?= $form ?>
    
    <input type="submit" value="Commenter" />
  </p>
</form>