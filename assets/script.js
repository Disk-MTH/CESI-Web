window.addEventListener("load", function () {
    if ("serviceWorker" in navigator) {
        navigator.serviceWorker.register("/service-worker.js").then(function (registration) {
            console.log("Service Worker registered with scope:", registration.scope);
        }).catch(function (error) {
            console.log("Service Worker registration failed:", error);
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

/*
  phpmyadmin:
    container_name: stagify-phpmyadmin
    image: phpmyadmin/phpmyadmin
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.apache-secure.rule=Host(`stagify.fr`)"
      - "traefik.http.routers.apache-secure.entrypoints=websecure"
      - "traefik.http.routers.apache-secure.tls.certresolver=myresolver"
    depends_on:
      - mysql
    environment:
      PMA_HOST: mysql
      PMA_USER: ${DB_USER}
      PMA_PASSWORD: ${DB_PASSWORD}
    networks:
      - stagify-network
 */