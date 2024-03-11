function toggleText(id) {
    const element = document.getElementById(id);
    const icon = document.getElementById(id + "-icon");

    if (element.type === "password") {
        element.type = "text";
        icon.src = "/assets/svg/misc/visibility_off.svg";

    } else {
        element.type = "password";
        icon.src = "/assets/svg/misc/visibility_on.svg";
    }
}

function toggleWishMark(event, id) {
    const icon= document.getElementById(id);

    if (icon.src.includes("wish_empty")) {
        icon.src = "/assets/svg/misc/wish_full.svg";
    } else {
        icon.src = "/assets/svg/misc/wish_empty.svg";
    }
    event.stopPropagation();
}

function navigateTo(event, url) {
    window.location.assign(url);
    event.stopPropagation();
}
