SELECT DISTINCT c.id, c.name, l.city, l.zipCode, io.id, io.title, COUNT(r.id) AS numberOfReviews
FROM company c
         JOIN company_internship cio ON c.id = cio.company_id
         LEFT JOIN company_location cl ON c.id = cl.company_id
         LEFT JOIN location l ON cl.location_id = l.id
         LEFT JOIN internship io ON cio.internship_id = io.id
         LEFT JOIN internship_rate ir ON io.id = ir.internship_id
         LEFT JOIN rate r ON ir.rate_id = r.id
GROUP BY c.id, c.name, l.city, l.zipCode, io.title, io.id;

SELECT DISTINCT c.id, c.name, l.zipCode, l.city, COUNT(io.id) as numberOfInternships
FROM company c
         JOIN company_location cl ON c.id = cl.company_id
         JOIN location l ON cl.location_id = l.id
         LEFT JOIN company_internship cio ON c.id = cio.company_id
         LEFT JOIN internship io ON cio.internship_id = io.id
WHERE io.location_id = l.id
GROUP BY c.id, c.name, l.zipCode, l.city;

SELECT DISTINCT c.id, c.name, l.zipCode, l.city, COUNT(io.id) as numberOfInternships
FROM company c
         JOIN company_location cl ON c.id = cl.company_id
         JOIN location l ON cl.location_id = l.id
         LEFT JOIN company_internship cio ON c.id = cio.company_id
         LEFT JOIN internship io ON cio.internship_id = io.id AND io.location_id = l.id
GROUP BY c.id, c.name, l.zipCode, l.city;

SELECT DISTINCT c.id,
                c.name,
                l.zipCode,
                l.city,
                COUNT(io.id) as numberOfInternships,
                COUNT(r.id)  as numberOfReviews,
                AVG(r.grade) as averageGrade
FROM company c
         JOIN company_location cl ON c.id = cl.company_id
         JOIN location l ON cl.location_id = l.id
         LEFT JOIN company_internship cio ON c.id = cio.company_id
         LEFT JOIN internship io ON cio.internship_id = io.id AND io.location_id = l.id
         LEFT JOIN internship_rate ir ON io.id = ir.internship_id
         LEFT JOIN rate r ON ir.rate_id = r.id
GROUP BY c.id, c.name, l.zipCode, l.city;



SELECT c.id,
       c.name,
       l.zipCode,
       l.city,
       c.employeeCount,
       (SELECT COUNT(io.id) FROM company_internship cio
                                     LEFT JOIN internship io ON cio.internship_id = io.id AND io.location_id = l.id
        WHERE c.id = cio.company_id) as numberOfInternships,
       (SELECT COUNT(r.id) FROM company_internship cio
                                    LEFT JOIN internship io ON cio.internship_id = io.id AND io.location_id = l.id
                                    LEFT JOIN internship_rate ir ON io.id = ir.internship_id
                                    LEFT JOIN rate r ON ir.rate_id = r.id
        WHERE c.id = cio.company_id) as numberOfReviews,
       (SELECT AVG(r.grade) FROM company_internship cio
                                     LEFT JOIN internship io ON cio.internship_id = io.id AND io.location_id = l.id
                                     LEFT JOIN internship_rate ir ON io.id = ir.internship_id
                                     LEFT JOIN rate r ON ir.rate_id = r.id
        WHERE c.id = cio.company_id) as averageGrade
FROM company c
         JOIN company_location cl ON c.id = cl.company_id
         JOIN location l ON cl.location_id = l.id;




SELECT c.id,
       c.name,
       l.zipCode,
       l.city,
       (SELECT COUNT(io.id) FROM company_internship cio
                                     LEFT JOIN internship io ON cio.internship_id = io.id AND io.location_id = l.id
        WHERE c.id = cio.company_id) as numberOfInternships,
       (SELECT COUNT(r.id) FROM company_internship cio
                                    LEFT JOIN internship io ON cio.internship_id = io.id AND io.location_id = l.id
                                    LEFT JOIN internship_rate ir ON io.id = ir.internship_id
                                    LEFT JOIN rate r ON ir.rate_id = r.id
        WHERE c.id = cio.company_id) as numberOfReviews,
       (SELECT AVG(r.grade) FROM company_internship cio
                                     LEFT JOIN internship io ON cio.internship_id = io.id AND io.location_id = l.id
                                     LEFT JOIN internship_rate ir ON io.id = ir.internship_id
                                     LEFT JOIN rate r ON ir.rate_id = r.id
        WHERE c.id = cio.company_id) as averageGrade,
       (SELECT COUNT(a.id) FROM application a
                                    LEFT JOIN internship io ON a.internship_id = io.id
        WHERE c.id = io.company_id) as numberOfApplications
FROM company c
         JOIN company_location cl ON c.id = cl.company_id
         JOIN location l ON cl.location_id = l.id;




SELECT DISTINCT c.id, c.name, l.city, l.zipCode, io.title, io.id
FROM company c
         JOIN company_internshipoffer cio ON c.id = cio.company_id
         JOIN internship_offer io ON cio.internshipoffer_id = io.id
         JOIN location l ON io.location_id = l.id


SELECT u.id,
       u.firstName,
       u.lastName,
       u.role,
       l.city,
       l.zipCode,
       p.id,
       p.year,
       p.school,
       p.type
FROM user u
         JOIN location l ON u.location_id = l.id
         JOIN promo_location pl ON l.id = pl.location_id
         JOIN promo p ON pl.promo_id = p.id;


SELECT DISTINCT c.id,
                c.name,
                l.city,
                l.zipCode,
                c.logoPath,
                cio.id,
                cio.title,
                (SELECT COUNT(ir.id) FROM rate ir WHERE ir.internship = cio.id) AS numberOfReviews
FROM company c
         INNER JOIN c.internships cio
         INNER JOIN cio.location l
GROUP BY c.id, c.name, l.city, l.zipCode, c.logoPath, cio.title, cio.id