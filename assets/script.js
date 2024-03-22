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

function retrieve(content, endpoint, page, yCount, xCount) {
 /*   content.html(
        `<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`
    );*/

    console.log(content);
    console.log(xCount + " " + yCount);

    /*for (let i = 0; i < count; i++) {
        fetch(`/jobs/${page}?count=${count}&tile=${i}`, {method: "GET"}).then((response) => {
            if (response.status === 200) {
                response.text().then((data) => {
                    //append the data to the content depending on the size of the screen. One column for mobile, two for tablet and four for desktop
                    /!*if (window.matchMedia(`(min-width: 992px)`).matches) {
                        content.innerHTML += `<div class="col-3">${data}</div>`;
                    } else if (window.matchMedia(`(min-width: 768px)`).matches) {
                        content.innerHTML += `<div class="col-6">${data}</div>`;
                    } else {
                        content.innerHTML += `<div class="col-12">${data}</div>`;
                    }*!/

                    if (count === 12) {
                        let desktop = document.getElementById("desktop");
                        console.log(desktop);
                    } else if (count === 8) {
                        let tablet = document.getElementById("tablet");
                        console.log(tablet);
                    } else {
                        let mobile = document.getElementById("mobile");
                        mobile.innerHTML += data;
                    }
                });
            } else {
                content.innerHTML +=
                    `<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
                        <h1 class="text-danger">Error ${response.status}</h1>
                    </div>`;
            }
        });
    }*/
}