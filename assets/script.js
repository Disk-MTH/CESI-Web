function toggleText(element) {
    const icon = document.getElementById("icon");

    if (element.type === "password") {
        element.type = "text";
        icon.src = "/assets/svg/misc/visibility_off.svg";

    } else {
        element.type = "password";
        icon.src = "/assets/svg/misc/visibility_on.svg";
    }
}