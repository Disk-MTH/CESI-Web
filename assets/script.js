window.addEventListener("load", function () {
    if ("serviceWorker" in navigator) {
        navigator.serviceWorker.register("/assets/service-worker.js", {scope: "/"}).catch(function (error) {
            console.error("Service Worker registration failed with " + error);
        });
    }
});

function navigateTo(event, url) {
    window.location.assign(url);
    event.stopPropagation();
}

function toggleWish(event, id) {
    const element = $(`#wish-${id}`);
    fetch(`/api/users/toggle_wish/${id}`, {method: "GET"}).then(response => {
        if (response.status === 200) {
            response.json().then(data => {
                if (data["success"]) element.toggleClass("fa-bookmark fa-bookmark-o");
            });
        }
    });
    event.stopPropagation();
}

function setLoading(content) {
    content.html($("#loading").html());
}

function setError(content) {
    content.html($("#error").html());
}

function retrieve(element, template, endpoint, then) {
    fetch(endpoint, {method: "GET"}).then(response => {
        if (response.status === 200) {
            response.json().then(data => {
                element.empty();
                data.forEach((item) => {
                    element.append(
                        template.html().replace(/{(\w+)}/g, function (match, key) {
                            return item[key] !== undefined && item[key] !== null ? item[key] : "N/A";
                        })
                    );
                });

                if (element.children().length === 0) setError(element);
                else if (then !== undefined) then();
            });
        } else {
            setError(element);
        }
    });
}