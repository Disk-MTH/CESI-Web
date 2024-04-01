pagination = $("#pagination").pagination({maxVisible: 5});

pagination.on("changePage", async function (event, page) {
    /*const filters = {
        "date": $("#dateDesc").is(":checked") ? "DESC" : $("#dateAsc").is(":checked") ? "ASC" : null,
        "rating": $("#ratingDesc").is(":checked") ? "DESC" : $("#ratingAsc").is(":checked") ? "ASC" : null,
        "skills": $("#skillsList").children().map((index, item) => $(item).find("#filterItemContent").text()).get(),
    };

    Object.keys(filters).forEach(key => (filters[key] === null || filters[key].length === 0) && delete filters[key]);*/

    let filters = {
        "role": role
    };
    filters = new URLSearchParams(filters).toString();

    const element = $("#users");
    setLoading(element);
    retrieve(element, $("#userTile"), `/api/users/${page}?${filters}`);
    pagination.setCount(Math.ceil((await fetch(`/api/count/users?${filters}`).then(response => response.json().then(data => data)))["count"] / 12));
});

pagination.changePage();