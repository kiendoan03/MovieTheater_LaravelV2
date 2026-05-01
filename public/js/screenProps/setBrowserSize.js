var win = window,
    doc = document,
    docElem = doc.documentElement,
    // body = doc.getElementsByTagName('body')[0],
    x = win.innerWidth || docElem.clientWidth,
    y = win.innerHeight || docElem.clientHeight;

document.querySelector('.full-screen-element').style.height = y + 'px';
document.querySelector('.fullscreen-height').style.height = y + 'px';

document.querySelector('.full-height').style.height = y + 'px';
document.querySelector('.full-height-ticket').style.height = y + 'px';
// document.querySelector('.full-screen-element').style.width = x + 'px';    
// alert(x + ' × ' + y);