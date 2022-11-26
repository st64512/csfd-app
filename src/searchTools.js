import { csfd } from 'node-csfd-api';


/*
// TEST
(async () => {
    const results = await csfd.movie(535121);
    console.log(results);
})();
*/

function solveObjectToString(object) {
    var objectStr = "";
    object.forEach((o) => {
        objectStr += ", " + o.name;
    })
    objectStr = objectStr.slice(2, objectStr.length);
    return objectStr;
}


function getStringFromArray(array) {
    return String(array);
}


function fillForm(data) {
    console.log('fillFOrm', data);
    const genres = getStringFromArray(data.genres);
    const actors = solveObjectToString(data.creators.actors);
    const directors = solveObjectToString(data.creators.directors);

    $('input[name="title"]').val(data.title);
    $('input[name="year"]').val(data.year);
    $('textarea[name="description"]').val(data.descriptions[0]);
    $('input[name="photo"]').val(data.photo);
    $('input[name="poster"]').val(data.poster);
    $('input[name="genres"]').val(genres);
    $('input[name="actors"]').val(actors);
    $('input[name="directors"]').val(directors);
}


$("#movie-search").keyup(
    function (event) {
        csfd.search(event.target.value)
            .then((search) => {
                $("#results").empty();
                search.movies.forEach( (movie) => {
                    var newElem = $("<li id='" + movie.id + "'>" + movie.title + " (" + movie.year + ")</li>");
                    newElem.on('click', (event) => {
                        $("#movie-search").val('');
                        $("#results").empty();
                        csfd.movie(event.target.id).then((selectedMovie) => {
                            fillForm(selectedMovie);
                        });
                    });
                    $("#results").append(newElem);
                });
            }).catch((er) => console.log(er));
    }
);