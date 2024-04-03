pagination = $("#pagination").pagination({maxVisible: 5});

pagination.on("changePage", async function (event, page) {
    let filters = {
        "rating": $("#ratingDesc").is(":checked") ? "DESC" : $("#ratingAsc").is(":checked") ? "ASC" : null,
        "internshipsCount": $("#internshipsCountDesc").is(":checked") ? "DESC" : $("#internshipsCountAsc").is(":checked") ? "ASC" : null,
        "employeesCount": $("input[type=radio][id^='employeesCount']").filter(":checked").attr("id") ? $("input[type=radio][id^='employeesCount']").filter(":checked").attr("id").split("-")[1] : null,
    };
    Object.keys(filters).forEach(key => (filters[key] === null || filters[key].length === 0 || filters[key] == "") && delete filters[key]);
    filters = new URLSearchParams(filters).toString();

    const element = $("#companies");
    setLoading(element);
    retrieve(element, $("#companyTile"), `/api/companies/${page}?${filters}`);
    pagination.setCount(Math.ceil((await fetch(`/api/count/companies?${filters}`).then(response => response.json().then(data => data)))["count"] / 12));
});

pagination.changePage();

function resetFilters() {
    $("input[type=radio]").prop("checked", false);
}

function applyFilters() {
    $("#companiesFilters").collapse("hide");
    pagination.changePage();
}