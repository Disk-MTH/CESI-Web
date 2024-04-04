pagination = $("#pagination").pagination({maxVisible: 5});

pagination.on("changePage", async function (event, page) {
    let filters = {
        "date": $("#dateDesc").is(":checked") ? "DESC" : $("#dateAsc").is(":checked") ? "ASC" : null,
        "rating": $("#ratingDesc").is(":checked") ? "DESC" : $("#ratingAsc").is(":checked") ? "ASC" : null,
        "skills": $("#skillsList").children().map((index, item) => $(item).find("input").val()).get().join(","),
        "keyword": $("#keyword").val(),
        "location": $("#location").val(),
    };
    Object.keys(filters).forEach(key => (filters[key] === null || filters[key].length === 0 || filters[key] === "") && delete filters[key]);
    filters = new URLSearchParams(filters).toString();
    console.log(filters);

    const element = $("#internships");
    setLoading(element);
    retrieve(element, $("#internshipTile"), `/api/internships/${page}?${filters}`);
    pagination.setCount(Math.ceil((await fetch(`/api/count/internships?${filters}`).then(response => response.json().then(data => data)))["count"] / 12));
});

pagination.changePage();

function resetFilters() {
    $("input[type=radio]").prop("checked", false);
    $("#skillsField").val("");
    $("#skillsList").empty();
}

function applyFilters() {
    $("#intershipsFilters").collapse("hide");
    pagination.changePage();
}