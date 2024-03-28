(async function () {
    pagination = $("#pagination").pagination({
        count: Math.ceil((await fetch("/companies?count=true").then(response => response.json().then(data => data)))["count"] / 12),
        maxVisible: 5,
    })

    pagination.on("changePage", function (event, page) {
        /*const filters = {
            "date": $("#dateDesc").is(':checked') ? "DESC" : $("#dateAsc").is(':checked') ? "ASC" : null,
            "rating": $("#ratingDesc").is(':checked') ? "DESC" : $("#ratingAsc").is(':checked') ? "ASC" : null,
            "skills": $("#skillsList").children().map((index, item) => $(item).find("#filterItemContent").text()).get(),
        };

        Object.keys(filters).forEach(key => (filters[key] === null || filters[key].length === 0) && delete filters[key]);
        console.log("filters: ", filters);*/

        const filters = {};
        //TODO: Implement filters for companies

        const element = $("#companies");
        setLoading(element);
        retrieve(element, $("#companyTile"), `/companies/${page}?${new URLSearchParams(filters).toString()}`);
    });

    pagination.changePage();
})();

function resetFilters() {
    $("input[type=radio]").prop("checked", false);
}

function applyFilters() {
    $("#intershipFilters").collapse("hide");
    pagination.changePage();
}