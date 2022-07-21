


const nightToggle = document.querySelector('.js-night-toggle');
const body = document.querySelector('.js-body');

nightToggle.addEventListener('click', function (){

    if (body.classList.contains('night-activated')) {
        body.classList.remove('night-activated');
    } else {
        body.classList.add('night-activated');
    }
});