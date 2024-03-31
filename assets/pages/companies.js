(async function () {
    pagination = $("#pagination").pagination({
        count: Math.ceil((await fetch("/companies?count=true").then(response => response.json().then(data => data)))["count"] / 12),
        maxVisible: 5,
    })

    pagination.on("changePage", function (event, page) {
        const filters = {
            "rating": $("#ratingDesc").is(":checked") ? "DESC" : $("#ratingAsc").is(":checked") ? "ASC" : null,
            "internshipsCount": $("#internshipsCountDesc").is(":checked") ? "DESC" : $("#internshipsCountAsc").is(":checked") ? "ASC" : null,
            "internsCount": $("#internsCountDesc").is(":checked") ? "DESC" : $("#internsCountAsc").is(":checked") ? "ASC" : null,
            "employeesCountLow": getEmployeesCount(true),
            "employeesCountHigh": getEmployeesCount(false),
        };

        Object.keys(filters).forEach(key => (filters[key] === null || filters[key].length === 0) && delete filters[key]);

        const element = $("#companies");
        setLoading(element);
        retrieve(element, $("#companyTile"), `/companies/${page}?${new URLSearchParams(filters).toString()}`);
    });

    pagination.changePage();
})();

function getEmployeesCount(low) {
    const checkedBoxes = $("input[type=checkbox][id^='employeesCount']:checked");
    if (checkedBoxes.length === 0) return null;

    let value;

    if (low) {
        checkedBoxes.sort((a, b) => {
            const aCount = $(a).attr("id").split("@")[1].split("_")[0];
            const bCount = $(b).attr("id").split("@")[1].split("_")[0];
            return aCount - bCount;
        });

        value = $(checkedBoxes[0]).attr("id").split("@")[1].split("_")[0];
    } else {
        checkedBoxes.sort((a, b) => {
            const aCount = $(a).attr("id").split("@")[1].split("_")[1];
            const bCount = $(b).attr("id").split("@")[1].split("_")[1];
            return aCount - bCount;
        });

        const firstBoxValue = $(checkedBoxes[0]).attr("id").split("@")[1].split("_")[1];
        value = firstBoxValue === "0" ? firstBoxValue : $(checkedBoxes.last()).attr("id").split("@")[1].split("_")[1];
    }

    return value === "0" ? null : value;
}

function resetFilters() {
    $("input[type=radio]").prop("checked", false);
    $("input[type=checkbox]").prop("checked", false);
}

function applyFilters() {
    $("#intershipFilters").collapse("hide");
    pagination.changePage();
}