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

function toggleText(event, id) {
    const element = document.getElementById(id);
    const icon = document.getElementById(id + "-icon");

    if (element.type === "password") {
        element.type = "text";
        icon.src = "/assets/svg/misc/visibility_off.svg";

    } else {
        element.type = "password";
        icon.src = "/assets/svg/misc/visibility_on.svg";
    }
    event.stopPropagation();
}

function toggleWishMark(event, id) {
    const icon = document.getElementById(id);

    if (icon.src.includes("wish_empty")) {
        icon.src = "/assets/svg/misc/wish_full.svg";
    } else {
        icon.src = "/assets/svg/misc/wish_empty.svg";
    }
    event.stopPropagation();
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
    event.stopPropagation();
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
    event.stopPropagation();
}

function setLoadingStatus(content) {
    content.html(
        `<div class="col text-center my-5">
            <div class="spinner-border text-primary"></div>
        </div>`
    );
}

function retrieve(element, endpoint, page, yCount, columns) {
    setLoadingStatus(element);

    columns = columns.map((column) => jQuery(column));

    fetch(`${endpoint}${page}?count=${columns.length * yCount}`, {method: "GET"}).then((response) => {
        if (response.status === 200) {
            response.json().then((data) => {
                let row = 0;
                data.forEach((item) => {
                    if (row === yCount - 1) {
                        row = 0;
                    }

                    columns[row].append(
                        `<div class="card shadow mb-3 bg-gradient-card cursor-pointer user-select-none"
                             onclick="navigateTo(event, '/')">
                            <div class="card-body">
                                <div class="row align-items-center text-nowrap">
                                    <div class="col">
                                        <h6 class="card-title">${item["title"]}</h6>
                                        <p class="text-lightGrey ms-3">&middot ${item["salary"]} $/mois</p>
                                    </div>
                                    <div class="col text-end">
                                        <img class="img-fluid" src="/assets/svg/misc/wish_empty.svg" alt="wish_mark" id="wish" onclick="toggleWishMark(event, 'wish')">
                                    </div>
                                </div>
                                <div class="mb-3"></div>
                                <div class="row row-cols-2">
                                    <div class="col-3 col-lg-4 col-xl-2 w-auto">
                                        <img class="img-fluid bg-deepSeaBlue bg-opacity-25 py-3 px-3 rounded-3" src="${item["company_logo"]}"
                                             alt="company_logo">
                                    </div>
                                    <div class="col">
                                        <h6 class="card-title">${item["company_name"]}</h6>
                                        <p class="text-lightGrey">
                                            <img class="img-fluid" src="/assets/svg/tile/map_pin.svg" alt="mapPin">
                                            ${item["location"]}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>`
                    );
                    row++;
                });
            });

            //set content of element to columns
            element.empty();
            columns.forEach((column) => element.append(column));
        } else {
            //TODO: handle error
            console.error("Failed to fetch data from " + endpoint);
        }
    });
}