<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<h2>Ajouter un commentaire</h2>
<form action="<?= 'commenter-'.$newsId.'.html'; ?>" method="post">
  <p>
    <?= $form ?>
    
    <input type="submit" value="Commenter" />
  </p>
</form>
<script>

  $(function() {

      $('form').submit(function (event) {
            var auteur = $('input[name=auteur], select[name=auteur]').val();
            var email = $('input[name=email]').val();
            var contenu = $('textarea[name=contenu]').val();
            $.post('/test-insert-comment-<?=$newsId?>.html', {auteur: auteur, email: email, contenu: contenu},
                function (data) {
                    var hasErrors = false;
                  $.each(data.form,function(key,value) {
                    console.log(key);
                    console.log(value);
                    if (value.error !== null) {
                        $("[name=error" + key + "]").html(value.error);
                        $('label[name=' + key + ']').addClass('falseField');
                        hasErrors = hasErrors || true;
                    }else{
                        $("[name=error" + key + "]").html('');
                        $('label[name=' + key + ']').removeClass('falseField');
                    }
                  })
                if (!hasErrors) {
                    $('form').unbind('submit');
                    $('form').trigger('submit');
                };
                },"json"
            );
            event.preventDefault();
          }
      );

});
</script>