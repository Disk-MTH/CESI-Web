function addSuggestion(id) {
    const content = $(`#${id}Field`);

    if (!content.val()) return;

    $(`#${id}List`).append(
        $("#suggestionItem")
            .html()
            .replaceAll("{id}", id)
            .replaceAll("{tileId}", Math.random().toString(36).substring(10))
            .replaceAll("{fieldId}", Math.random().toString(36).substring(10))
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
                        return data.map((item) => item["content"].toString());
                    });
                }
            }));
        },
        open: function () {
            $(".ui-autocomplete").css("width", $(`#${id}`).width() + "px");
            $(".ui-helper-hidden-accessible").remove();
        },
        appendTo: `#${id}List`,
        classes: {
            "ui-autocomplete": "card",
        },
        messages: {
            noResults: "",
            results: function () {
            }
        }
    });
}