pagination = $("#pagination").pagination({maxVisible: 5});

pagination.on("changePage", async function (event, page) {
    //TODO: Add name filter
    filters = "";

    const element = $("#internships");
    setLoading(element);
    retrieve(element, $("#internshipTile"), `/api/internships/${page}?${filters}`);
    pagination.setCount(Math.ceil((await fetch(`/api/count/internships?${filters}`).then(response => response.json().then(data => data)))["count"] / 12));
});

pagination.changePage();