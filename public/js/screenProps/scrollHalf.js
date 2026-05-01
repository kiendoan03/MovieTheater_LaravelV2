const overviewPage = document.getElementById("movie__overview").getBoundingClientRect().top + window.scrollY;
const detailedPage = document.getElementById("movie__full--detail").getBoundingClientRect().top + window.scrollY;
const BookTickerPage = document.getElementById("book__ticket").getBoundingClientRect().top + window.scrollY;


function toOverviewPage() {
    window.scroll({
        top: overviewPage,
        behavior: 'smooth'
    });
}

function toDetailedPage() {
    window.scroll({
        top: detailedPage,
        behavior: 'smooth'
    });
}

function toBookTickerPage() {
    window.scroll({
        top: BookTickerPage,
        behavior: 'smooth'
    });
}