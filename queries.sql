SELECT DISTINCT c.id, c.name, l.city, l.zipCode, io.id, io.title, COUNT(r.id) AS numberOfReviews
FROM company c
         JOIN company_internship cio ON c.id = cio.company_id
         JOIN internship io ON cio.internship_id = io.id
         JOIN location l ON io.location_id = l.id
         LEFT JOIN internship_rate ir ON io.id = ir.internship_id
         LEFT JOIN rate r ON ir.rate_id = r.id
GROUP BY c.id, c.name, l.city, l.zipCode, io.title, io.id;

#

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