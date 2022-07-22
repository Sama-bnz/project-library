

const body = document.querySelector('.js-body');

//Je check si le mode dark sasuke est activé dans le local storage,
//si oui je l'active avec le CSS
if (localStorage.getItem('nightActivated') === 'true') {
    body.classList.add('night-activated');
}


const nightToggle = document.querySelector('.js-night-toggle');

nightToggle.addEventListener('click', function (){
    //Si le mode dark sasuke est activé je le désactive lorsque je click
    if (body.classList.contains('night-activated')) {
        body.classList.remove('night-activated');

        //Je supp le mode darksasuke du local storage
        localStorage.removeItem('nightActivated');

        //Si le mode dark sasuke n'est pas activé alors je l'active
    } else {
        body.classList.add('night-activated');
    }
    localStorage.setItem('nightActivated',"true");
});