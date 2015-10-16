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

function getLastComment() {
    _$last_comment = $('.comment:first');
    return _$last_comment.attr('data-id');
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
    $.post(url, parameters,
        function (data) {
            console.log(data);
            $.each(data, function (key, value) {
                if (key < 5) {
                    var string2add = '<fieldset class="comment" data-id="' + value.id + '">' +
                        '<legend>Poste par: <a href="/member-' + value.auteur + '.html">' + value.auteur + '</a> le ' + value.date;
                        if(getPseudoUser() == value.auteur){
                            string2add += ' - <a href="/comment-update'+ value.id +'.html" >Modifier</a>'+
                                ' | <a href="/comment-delete'+ value.id +'.html" >Supprimer</a>';
                        }
                    string2add += '</legend>' +
                            '<p>' + value.contenu + '</p>' +
                            '</fieldset>';
                    if (getLastComment() < value.id) { //on demande les nouveaux commentaires
                        setTimeout( function() {
                            getShowNewCommentDiv().prepend('', string2add)
                            }, 100 * key);
                    } else {
                        setTimeout( function(){
                            getShowOldCommentDiv().append('', string2add) }
                            , 100*key )
                        }
                        getButtonShowMore().hide();
                    }
                else
                    getButtonShowMore().show();
            });
        }, "json"
    );
}

/*----------Appels au fonctions---------------*/

//setInterval(loadNewComments, 4000);

getButtonShowMore().click(function(){
        loadOldComments();
    }
);