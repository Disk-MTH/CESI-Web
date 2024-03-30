(async function () {
    pagination = $("#pagination").pagination({
        count: Math.ceil((await fetch("/internships?count=true").then(response => response.json().then(data => data)))["count"] / 12),
        maxVisible: 5,
    })

    pagination.on("changePage", function (event, page) {
        const filters = {
            "date": $("#dateDesc").is(':checked') ? "DESC" : $("#dateAsc").is(':checked') ? "ASC" : null,
            "rating": $("#ratingDesc").is(':checked') ? "DESC" : $("#ratingAsc").is(':checked') ? "ASC" : null,
            "skills": $("#skillsList").children().map((index, item) => $(item).find("#filterItemContent").text()).get(),
        };

        Object.keys(filters).forEach(key => (filters[key] === null || filters[key].length === 0) && delete filters[key]);
        console.log(filters);

        const element = $("#internships");
        setLoading(element);
        retrieve(element, $("#internshipTile"), `/internships/${page}?${new URLSearchParams(filters).toString()}`);
    });

    pagination.changePage();
})();

$("#skillsField").autocomplete({
    source: async function (request, response) {
        response(await fectchSkills(request.term));
    },
    select: function (event, ui) {
        event.stopPropagation();
    },
    open: function () {
        $(".ui-autocomplete").css("width", $('#skills').width() + "px");
    },
    classes: {
        "ui-autocomplete": "card",
    },
    messages: {
        noResults: "",
        results: function () {
        },
    },
});

function resetFilters() {
    $("input[type=radio]").prop("checked", false);
    $("#skillsField").val("");
    $("#skillsList").empty();
}

function applyFilters() {
    $("#intershipFilters").collapse("hide");
    pagination.changePage();
}

async function fectchSkills(text) {
    return await fetch(`/skills/${text}`, {method: "GET"}).then(async (response) => {
        if (response.status === 200) {
            return await response.json().then((data) => {
                return data.map((item) => item["name"]);
            });
        }
    });
}