(async function () {
    const element = $("#internships");
    setLoading(element);

    pagination = $("#pagination").pagination({
        count: Math.ceil((await fetch("/internships?count=true").then(response => response.json().then(data => data)))["count"] / 12),
        maxVisible: 5,
    })

    pagination.on("changePage", function (event, page) {
        const filters = {
            "date": $("#dateDesc").is(':checked') ? "desc" : $("#dateAsc").is(':checked') ? "asc" : null,
            "rating": $("#ratingDesc").is(':checked') ? "desc" : $("#ratingAsc").is(':checked') ? "asc" : null,
            "office": $("#office").is(':checked') ? true : null,
            "hybrid": $("#hybrid").is(':checked') ? true : null,
            "remote": $("#remote").is(':checked') ? true : null,
            "skills": $("#skillsList").children().map((index, item) => $(item).text()).get(),
        };

        Object.keys(filters).forEach(key => filters[key] === null && delete filters[key]);
        console.log("filters: ", filters);

        setLoading(element);
        retrieve(element, $("#internship_tile"), `/internships/${page}`);
    }).trigger("changePage", 1);
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
});

function resetFilters() {
    $("input[type=radio]").prop('checked', false);
    $("input[type=checkbox]").prop('checked', false);
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

