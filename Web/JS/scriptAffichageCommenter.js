var _displayed = false;

function expand(){
    var cache = document.getElementById('cache'),
        bouton = document.getElementById('displayComment');
    console.log(bouton);

    if(!_displayed) {
        cache.style.display = "block";
        bouton.firstElementChild.innerHTML = "Cacher";
        _displayed = true;
    }else{
        cache.style.display = "none";
        bouton.firstElementChild.innerHTML = "Commenter";
        _displayed = false;
    }
}



var _afficherCommentaires =false;
var _comment = $('p[id=idComment]');
if(_comment.length > 0)
    _comment = _comment[0].innerHTML;
else
    _comment = 1;
var _showCommentDiv = $('div[id=showNewComments]');

$(function() {
    actualiserCommentaires();
    setInterval(actualiserCommentaires, 4000);

    $('[id=buttonShowMoreComments]').click(function(){
            if(_afficherCommentaires == true){
                this.innerHTML = 'Afficher plus de commentaires';
            }else{
                this.innerHTML =  'Cacher les commentaires';
            }
            _afficherCommentaires = !_afficherCommentaires;
            affichageCommentaires();
        }
    );
});
function actualiserCommentaires() {
    var news = $('p[id=idNews]')[0].innerHTML;
    if (_comment && news) {
        $.post('/getNewComments.html', {news_id: news, comment_last_id: _comment},
            function (data) {
                $.each(data.form, function (key, value) {
                        $.each(value, function (key, value) {
                                var date = value.date.date;
                                var dateStr = date.toString();
                                _showCommentDiv.prepend('', '<fieldset class="comment"><legend>Poste par: <a href="/member-' + value.auteur + '.html">' + value.auteur + '</a> le ' + dateStr + '</legend><p>' + value.contenu + '</p></fieldset>');
                                if (_comment < value.id)
                                    _comment = value.id;
                        });
                })
            }, "json"
        );
        //On affiche que les 5 derniers
    }
    affichageCommentaires();
}

function affichageCommentaires() {
    var commentaires = $('fieldset[class=comment]');
    var i;
    if (_afficherCommentaires == false) {
        if (commentaires.length > 5) {
            for (i = 5; i < commentaires.length; i++) {
                commentaires[i].style.display = 'none';
            }
        }
    }else{
        for (i = 5; i < commentaires.length; i++) {
            commentaires[i].style.display = 'block';
        }
    }
}

