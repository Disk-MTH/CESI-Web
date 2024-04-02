const element = $("#internships");
setLoading(element);
retrieve(element, $("#internshipTile"), "/api/internships/1");