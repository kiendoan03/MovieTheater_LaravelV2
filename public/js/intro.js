let intro = document.getElementById('container');
let home = document.querySelector('.home');

// intro.style.display = 'none';
// home.style.display = 'none';
window.addEventListener('DOMContentLoaded', () => {

    setTimeout(() => {
        intro.style.display = 'none';
        home.style.top = "0";
        home.style.zIndex = "1";
    }, 5250)

})