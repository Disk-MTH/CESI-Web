SELECT DISTINCT c.id, c.name, l.city, l.zipCode
FROM company c
         JOIN company_location cl ON c.id = cl.company_id
         JOIN location l ON cl.location_id = l.id;
