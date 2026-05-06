let offset = 6;

    const btn = document.getElementById('loadMoreBtn');
    const noMoreText = document.getElementById('noMoreText');

    btn.addEventListener('click', function () {

        fetch(`/load-more-upcoming?offset=${offset}`)
            .then(res => res.json())
            .then(movies => {

                if (movies.length === 0) {
                    btn.style.display = 'none';
                    noMoreText.style.display = 'block';
                    return;
                }

                let html = '';

                movies.forEach(movie => {

                    let img = movie.thumbnail.startsWith('http')
                        ? movie.thumbnail
                        : '/storage/img/movie_thumbnail/' + movie.thumbnail;

                    html += `
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card upcoming-card">
                                <div class="thumb-wrapper">
                                    <img src="${img}">
                                </div>
                            </div>
                        </div>
                    `;
                });

                document.getElementById('upcoming-list')
                    .insertAdjacentHTML('beforeend', html);

                offset += movies.length;
            });
    });