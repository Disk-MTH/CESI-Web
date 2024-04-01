function addSuggestion(id) {
    const content = $(`#${id}Field`);

    $(`#${id}List`).append(
        $("#suggestionItem")
            .html()
            .replaceAll("{id}", Math.random().toString(36).substring(10))
            .replace("{content}", content.val())
    );

    content.val("");
}

function removeSuggestion(id) {
    $(`#${id}`).remove();
}

function suggestion(id) {
    $(`#${id}Field`).autocomplete({
        source: async function (request, response) {
            response(await fetch(`/api/suggestions/${id}/${request.term}`, {method: "GET"}).then(async (response) => {
                if (response.status === 200) {
                    return await response.json().then((data) => {
                        return data.map((item) => item["name"]);
                    });
                }
            }));
        },
        open: function () {
            $(".ui-helper-hidden-accessible").remove();
        },
        appendTo: `#${id}List`,
        classes: {
            "ui-autocomplete": "card",
        },
    });
}