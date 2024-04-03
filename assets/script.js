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

function toggleWishMark(event, id) {
    const icon = document.getElementById(id);

    if (icon.src.includes("wish_empty")) {
        icon.src = "/assets/svg/misc/wish_full.svg";
    } else {
        icon.src = "/assets/svg/misc/wish_empty.svg";
    }
}

function toggleStar(event, id, fill) {
    const split = id.split("@");
    for (let i = 0; i <= split[split.length - 1]; i++) {
        const icon = document.getElementById(split[0] + "@" + i);

        if (fill) {
            icon.src = "/assets/svg/tile/star_full.svg";
        } else {
            icon.src = "/assets/svg/tile/star_empty.svg";
        }
    }
}

function clickStar(event, id) {
    const split = id.split("@");
    let alreadyRated = true;

    for (let i = 0; i < 5; i++) {
        const icon = document.getElementById(split[0] + "@" + i);

        if (icon.onmouseenter != null || icon.onmouseleave != null) {
            icon.onmouseenter = null;
            icon.onmouseleave = null;
            alreadyRated = false;
        }
    }

    if (alreadyRated) {
        for (let i = 0; i < 5; i++) {
            const icon = document.getElementById(split[0] + "@" + i);

            toggleStar(event, icon.id, false);

            icon.onmouseenter = function (event) {
                toggleStar(event, icon.id, true)
            };
            icon.onmouseleave = function (event) {
                toggleStar(event, icon.id, false)
            };
        }
    }
}

function setLoading(content) {
    content.html($("#loading").html());
}

function setError(content) {
    content.html($("#error").html());
}

function retrieve(element, template, endpoint) {
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
            });
        } else {
            setError(element);
        }
    });
}