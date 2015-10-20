var _$displayed = false;

function expand(){
    var cache = document.getElementById('cache'),
        bouton = document.getElementById('displayComment');

    if(!_$displayed) {
        cache.style.display = "block";
        bouton.firstElementChild.innerHTML = "Cacher";
        _$displayed = true;
    }else{
        cache.style.display = "none";
        bouton.firstElementChild.innerHTML = "Commenter";
        _$displayed = false;
    }
}


/*--------------------getters-------------------------*/
var _$news = null;
var _$oldest_comment = null;
var _$last_comment = null;
var _$showNewCommentDiv = null;
var _$showOldCommentDiv = null;
var _$button_show_more = null;
var _$pseudo_user = null;
var _$lock = false;

function getLastComment() {
    _$last_comment = $('.comment:first');
    return _$last_comment.attr('data-id');
}

function lockIt() {
    return _$lock = true;
}

function unlockIt() {
    return _$lock = false;
}

function getDisplayedNewsId() {
    if (_$news == null)
        _$news = $('#newsTitle');
    return _$news.attr('data-id');
}

function getShowNewCommentDiv(){
    if (_$showNewCommentDiv == null)
        _$showNewCommentDiv = $('#showNewComments');
    return _$showNewCommentDiv;
}

function getShowOldCommentDiv(){
    if (_$showOldCommentDiv == null)
        _$showOldCommentDiv = $('#showOldComments');
    return _$showOldCommentDiv;
}

function getOldestComment(){
    _$oldest_comment = $('.comment:last');
    return _$oldest_comment.attr('data-id');
}

function getButtonShowMore(){
    _$button_show_more = $('#buttonShowMoreComments');
    return _$button_show_more;
}

function getPseudoUser(){
    if(_$pseudo_user == null)
        _$pseudo_user = $('footer');
    return _$pseudo_user.attr('data-user-name');
}

/*---------------gestion des commentaires------------*/
function loadOldComments() {
    var news = getDisplayedNewsId();
    var oldest_comment = getOldestComment();
    var parameters = {news_id: news, comment_old_id: oldest_comment};

    loadMoreComments('/getOldComments.html', parameters);
}

function loadNewComments() {
    var news = getDisplayedNewsId();
    var last_comment = getLastComment();
    var parameters = {news_id: news, comment_last_id: last_comment};

    loadMoreComments('/getNewComments.html',parameters);
}


function loadMoreComments(url, parameters) {
    if(!_$lock) {
        lockIt();
        $.post(url, parameters,
            function (data) {
                $.each(data, function (key, value) {
                    if (key < 5) {

                        var member = $('<a></a>').attr('href', '/member-' + value.auteur + '.html').html(value.auteur);

                        if (getPseudoUser() == value.auteur) {
                            var modifier = $('<a></a>').attr('href', '/comment-update-' + value.id + '.html').html(' - Modifier | ');
                            var supprimer = $('<a></a>').attr('href', '/comment-delete' + value.id + '.html').html('Supprimer');
                        } else{
                            modifier = "";
                            supprimer = "";
                        }
                        var content = $('<p></p>').html(value.contenu);

                        var string2add = $('<fieldset></fieldset>').addClass('comment').attr('data-id',value.id).
                            append( ($('<legend></legend>') ).
                                        append('Posté par ',member).
                                        append(' le '+value.date).
                                        append(modifier).
                                        append(supprimer) ,
                                    (content) );


                        if (getLastComment() < value.id) { //on demande les nouveaux commentaires
                            setTimeout(function () {
                                getShowNewCommentDiv().prepend('', string2add);
                            }, 100 * key);
                        } else {
                            setTimeout(function () {
                                    getShowOldCommentDiv().append('', string2add);
                                }
                                , 100 * key)
                        }
                        getButtonShowMore().hide();
                    }
                    else
                        getButtonShowMore().show();
                });
            }, "json"
        );
        unlockIt();
    }

}

/*----------Appels au fonctions---------------*/

if(getDisplayedNewsId())
    setInterval(loadNewComments, 4000);

getButtonShowMore().click(function(){
        loadOldComments();
    }
);