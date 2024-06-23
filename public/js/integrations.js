function getFromCities() {
    let fromCities = [];
    $.ajax({
        type: "GET",
        url: "/api/cities",
        async: false,
        success: function (data) {
            fromCities = data;
        }
    });
    return fromCities;
}
