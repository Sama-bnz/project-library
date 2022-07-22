

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

const burgerButton = document.querySelector(".nav-toggler")
const navigation = document.querySelector(".secondNav")

burgerButton.addEventListener("click", toggleNav)

function toggleNav(){
    console.log('test')
    burgerButton.classList.toggle("active")
    navigation.classList.toggle("active")
}




// //Je check si le mode light sakura est activé dans le local storage,
// //si oui je l'active avec le CSS
// if (localStorage.getItem('pinkActivated') === 'true') {
//     body.classList.add('pink-activated');
// }
//
//
// const lightToggle = document.querySelector('.js-kawaï-toggle');
//
// lightToggle.addEventListener('click', function (){
//     //Si le mode light sakura est activé je le désactive lorsque je click
//     if (body.classList.contains('pink-activated')) {
//         body.classList.remove('pink-activated');
//
//         //Je supp le mode light sakura du local storage
//         localStorage.removeItem('pinkActivated');
//
//         //Si le mode light sakura n'est pas activé alors je l'active
//     } else {
//         body.classList.add('pink-activated');
//     }
//     localStorage.setItem('pinkActivated',"true");
// });
//
//
//
//
//
//
// if (localStorage.getItem('heroActivated') === 'true') {
//     body.classList.add('hero-activated');
// }
//
//
// const heroToggle = document.querySelector('.js-hero-toggle');
//
// heroToggle.addEventListener('click', function (){
//     //Si le mode naruto est activé je le désactive lorsque je click
//     if (body.classList.contains('hero-activated')) {
//         body.classList.remove('hero-activated');
//
//         //Je supp le mode naruto du local storage
//         localStorage.removeItem('heroActivated');
//
//         //Si le mode naruto n'est pas activé alors je l'active
//     } else {
//         body.classList.add('hero-activated');
//     }
//     localStorage.setItem('heroActivated',"true");
// });