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

