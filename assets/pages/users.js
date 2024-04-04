pagination = $("#pagination").pagination({maxVisible: 5});

pagination.on("changePage", async function (event, page) {
    let filters = {
        "role": role,
        "years": $("input[type=checkbox][id^='year']:checked").map((index, item) => $(item).attr("id").split("-")[1]).get(),
        "promos": $("#promosList").children().map((index, item) => $(item).find("input").val()).get(),
        "campus": $("#campusList").children().map((index, item) => $(item).find("input").val()).get(),
        "skills": $("#skillsList").children().map((index, item) => $(item).find("input").val()).get(),
    };
    Object.keys(filters).forEach(key => (filters[key] === null || filters[key].length === 0 || filters[key] == "") && delete filters[key]);
    filters = new URLSearchParams(filters).toString();

    const element = $("#users");
    setLoading(element);
    retrieve(element, $("#userTile"), `/api/users/${page}?${filters}`);
    pagination.setCount(Math.ceil((await fetch(`/api/count/users?${filters}`).then(response => response.json().then(data => data)))["count"] / 12));
});

pagination.changePage();

function resetFilters() {
    $("input[type=checkbox]").prop("checked", false);
    $("#promosField").val("");
    $("#promosList").empty();
    $("#campusField").val("");
    $("#campusList").empty();
    $("#skillsField").val("");
    $("#skillsList").empty();
}

function applyFilters() {
    $("#usersFilters").collapse("hide");
    pagination.changePage(1);
}