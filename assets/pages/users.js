/*
(async function() {
    $("#pagination").pagination({
        count: Math.ceil((await fetch("/users?count=true").then(response => response.json().then(data => data)))["count"] / 12),
        maxVisible: 5,
    }).on("changePage", function (event, page) {
        const element = $("#students");
        setLoading(element);
        retrieve(element, $("#user_tile"), `/users/${page}`);
    }).trigger("changePage", 1);
})();*/

console.log("Hello world" + role);