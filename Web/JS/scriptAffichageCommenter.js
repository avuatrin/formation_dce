var displayed = false;

function expand(){
    var cache = document.getElementById('cache'),
        bouton = document.getElementById('displayComment');

    if(!displayed) {
        cache.style.display = "block";
        bouton.firstElementChild.innerHTML = "Cacher";
    }else{
        cache.style.display = "none";
        bouton.firstElementChild.innerHTML = "Commenter";
    }
    displayed = !displayed;

}

$(function() {
    var comment = $('p[id=idComment]')[0].innerHTML;
    var _showCommentDiv = $('div[id=showNewComments]');
    setInterval(function (){
        var news = $('p[id=idNews]')[0].innerHTML;
        if(comment && news){
            $.post('/getNewComments.html', {news_id: news, comment_last_id: comment},
                function (data) {
                    $.each(data.form,function(key,value) {
                        $.each(value,function(key,value) {
                            console.log(value);
                            var date = Date(value.date.date);
                            var dateStr = date.toString();
                            _showCommentDiv.append('','<fieldset><legend>Poste par: <a href="/member-'+value.auteur+'.html">'+value.auteur+'</a> le '+dateStr+'</legend><p>'+value.contenu+'</p></fieldset>');
                            comment = value.id;
                        })
                    })
                }, "json"
            );
        }
    }, 4000
    );

});