old = JSON.parse(old.replaceAll("&quot;", '"'));
for (let value in old) {
    if (!value.startsWith("suggestion-locations_")) continue;
    $("#zipCode").val(old[value].split(" ")[0]);
    $("#city").val(old[value].split(" ")[1]);
    addLocation();
}

function addLocation() {
    const zipCode = $("#zipCode");
    const city = $("#city");

    if (!zipCode.val() || !parseInt(zipCode.val()) || !city.val()) return;

    $("#locations").append(
        $("#suggestionItem")
            .html()
            .replaceAll("{id}", "locations")
            .replaceAll("{tileId}", Math.random().toString(36).substring(10))
            .replaceAll("{fieldId}", Math.random().toString(36).substring(10))
            .replace("{content}", `${zipCode.val()} ${city.val()}`)
    );

    zipCode.val("");
    city.val("");
}