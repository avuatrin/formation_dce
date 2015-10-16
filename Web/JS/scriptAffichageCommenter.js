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
var _showOldCommentsDiv = $('div[id=showOldComments]');

$(function() {
    actualiserCommentaires();
    setInterval(actualiserCommentaires, 4000);
    affichageCommentaires();

    $('[id=buttonShowMoreComments]').click(function(){
            _afficherCommentaires = true;
            $(this).hide();
            loadMoreComments();
        }
    );
});
function actualiserCommentaires() {
    var news = $('p[id=idNews]')[0].innerHTML;
    if (_comment && news) {
        $.post('/getJSONComments.html', {news_id: news, comment_last_id: _comment},
            function (data) {
                $.each(data.form, function (key, value) {
                        $.each(value, function (key, value) {
                                var date = value.date.date;
                                var dateStr = date.toString();
                                _showCommentDiv.prepend('', '<fieldset class="comment"><p id="idComment" style="display:none;">'+value.id+'</p><legend>Poste par: <a href="/member-' + value.auteur + '.html">' + value.auteur + '</a> le ' + dateStr + '</legend><p>' + value.contenu + '</p></fieldset>');
                                if (_comment < value.id)
                                    _comment = value.id;

                        });
                    affichageCommentaires();
                })
            }, "json"
        );
    }
}

function affichageCommentaires() {
    var commentaires = $('[class=comment]');
    var i;
    if (_afficherCommentaires == false) {
        if (commentaires.length > 5) {
            for (i = 5; i < commentaires.length; i++) {
                commentaires[i].style.display = 'none';
            }
            $('[id=buttonShowMoreComments]').show();
        }
    }else{
        $('[id=buttonShowMoreComments]').hide();
        if (commentaires.length > 5) {
            for (i = 5; i < commentaires.length; i++) {
                commentaires[i].style.display = 'block';
            }
        }
    }
}

function loadMoreComments() {
    var news = $('p[id=idNews]')[0].innerHTML;
    var _oldest_comment = $('p[id=idComment]');
    if(_oldest_comment.length > 0)
        _oldest_comment = _oldest_comment[_oldest_comment.length -1].innerHTML;
    else
        _oldest_comment = 1;
    $.post('/getJSONComments.html', {news_id: news, comment_old_id: _oldest_comment},
        function (data) {
            $.each(data.form, function (key, value) {
                $.each(value, function (key, value) {
                    console.log(value);
                    var date = value.date.date;
                    var dateStr = date.toString();
                    _showOldCommentsDiv.append('', '<fieldset class="comment"><legend>Poste par: <a href="/member-' + value.auteur + '.html">' + value.auteur + '</a> le ' + dateStr + '</legend><p>' + value.contenu + '</p></fieldset>');
                });
            })
        }, "json"
    );
}