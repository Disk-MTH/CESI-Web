USE stagify;

INSERT INTO location (id, zipCode, city) VALUES (1, 1000, 'Bruxelles');
INSERT INTO location (id, zipCode, city) VALUES (2, 51100, 'Reims');
INSERT INTO location (id, zipCode, city) VALUES (3, 75000, 'Paris');
INSERT INTO location (id, zipCode, city) VALUES (4, 69000, 'Lyon');
INSERT INTO location (id, zipCode, city) VALUES (5, 13000, 'Marseille');

# password = *Azertyui1
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicturePath, description, login, passwordHash, deleted) VALUES (1, 1, 3, 'Julien', 'Turcot', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'julien.turcot@gmail.com', '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5', 0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicturePath, description, login, passwordHash, deleted) VALUES (2, 2, 2, 'Mathieu', 'Gillet', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'mathieu.gillet@gmail.com', '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5', 0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicturePath, description, login, passwordHash, deleted) VALUES (3, 3, 1, 'John', 'Doe', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'john.doe@gmail.com', '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5', 0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicturePath, description, login, passwordHash, deleted) VALUES (4, 4, 1, 'MTH', 'Disk', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'disk.mth@gmail.com', '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5', 0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicturePath, description, login, passwordHash, deleted) VALUES (5, 5, 1, 'Odd', 'Origin', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'odd.origin@gmail.com', '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5', 0);

INSERT INTO promo (id, year, type, school) VALUES (1, 2, 'Ingénieur', 'CESI');
INSERT INTO promo (id, year, type, school) VALUES (2, 3, 'Medecin', 'CESI');
INSERT INTO promo (id, year, type, school) VALUES (3, 1, 'Macon', 'CESI');
INSERT INTO promo (id, year, type, school) VALUES (4, 2, 'Boulanger', 'CESI');
INSERT INTO promo (id, year, type, school) VALUES (5, 3, 'Dev', 'CESI');

INSERT INTO user_promo (user_id, promo_id) VALUES (1, 1);
INSERT INTO user_promo (user_id, promo_id) VALUES (2, 2);
INSERT INTO user_promo (user_id, promo_id) VALUES (3, 3);
INSERT INTO user_promo (user_id, promo_id) VALUES (4, 4);
INSERT INTO user_promo (user_id, promo_id) VALUES (5, 5);

INSERT INTO activity_sector (id, name) VALUES (1, 'Informatique');
INSERT INTO activity_sector (id, name) VALUES (2, 'Médecine');
INSERT INTO activity_sector (id, name) VALUES (3, 'Boulangerie');
INSERT INTO activity_sector (id, name) VALUES (4, 'Maconnerie');
INSERT INTO activity_sector (id, name) VALUES (5, 'Dev');

INSERT INTO company (id, name, website, employeeCount, logoPath, deleted, activitySector_id) VALUES (1, 'Google', 'www.google.com', 100000, 'google.jpg', 0, 1);
INSERT INTO company (id, name, website, employeeCount, logoPath, deleted, activitySector_id) VALUES (2, 'Hopital', 'www.hopital.com', 1000, 'hopital.jpg', 0, 2);
INSERT INTO company (id, name, website, employeeCount, logoPath, deleted, activitySector_id) VALUES (3, 'Boulangerie', 'www.boulangerie.com', 10, 'boulangerie.jpg', 0, 3);
INSERT INTO company (id, name, website, employeeCount, logoPath, deleted, activitySector_id) VALUES (4, 'Maconnerie', 'www.maconnerie.com', 100, 'maconnerie.jpg', 0, 4);
INSERT INTO company (id, name, website, employeeCount, logoPath, deleted, activitySector_id) VALUES (5, 'Dev', 'www.dev.com', 1000, 'dev.jpg', 0, 5);

INSERT INTO company_location (company_id, location_id) VALUES (1, 1);
INSERT INTO company_location (company_id, location_id) VALUES (2, 2);
INSERT INTO company_location (company_id, location_id) VALUES (3, 3);
INSERT INTO company_location (company_id, location_id) VALUES (4, 4);
INSERT INTO company_location (company_id, location_id) VALUES (5, 5);

INSERT INTO internship_offer (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount, description, deleted, title) VALUES (1, 1, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Ingenieur logiciel H/F');
INSERT INTO internship_offer (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount, description, deleted, title) VALUES (2, 2, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Medecin H/F');
INSERT INTO internship_offer (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount, description, deleted, title) VALUES (3, 3, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Boulanger H/F');
INSERT INTO internship_offer (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount, description, deleted, title) VALUES (4, 4, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Macon H/F');
INSERT INTO internship_offer (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount, description, deleted, title) VALUES (5, 5, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Dev H/F');

INSERT INTO company_internshipoffer (company_id, internshipOffer_id) VALUES (1, 1);
INSERT INTO company_internshipoffer (company_id, internshipOffer_id) VALUES (2, 2);
INSERT INTO company_internshipoffer (company_id, internshipOffer_id) VALUES (3, 3);
INSERT INTO company_internshipoffer (company_id, internshipOffer_id) VALUES (4, 4);
INSERT INTO company_internshipoffer (company_id, internshipOffer_id) VALUES (5, 5);

INSERT INTO internship_candidate (id, user_id, cvPath, coverLetterPath, accepted, internshipOffer_id) VALUES (1, 1, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO internship_candidate (id, user_id, cvPath, coverLetterPath, accepted, internshipOffer_id) VALUES (2, 2, 'cv.pdf', 'coverLetter.pdf', 0, 2);
INSERT INTO internship_candidate (id, user_id, cvPath, coverLetterPath, accepted, internshipOffer_id) VALUES (3, 3, 'cv.pdf', 'coverLetter.pdf', 0, 3);
INSERT INTO internship_candidate (id, user_id, cvPath, coverLetterPath, accepted, internshipOffer_id) VALUES (4, 4, 'cv.pdf', 'coverLetter.pdf', 0, 4);
INSERT INTO internship_candidate (id, user_id, cvPath, coverLetterPath, accepted, internshipOffer_id) VALUES (5, 5, 'cv.pdf', 'coverLetter.pdf', 0, 5);

INSERT INTO promo_location (promo_id, location_id) VALUES (1, 1);
INSERT INTO promo_location (promo_id, location_id) VALUES (2, 2);
INSERT INTO promo_location (promo_id, location_id) VALUES (3, 3);
INSERT INTO promo_location (promo_id, location_id) VALUES (4, 4);
INSERT INTO promo_location (promo_id, location_id) VALUES (5, 5);

INSERT INTO rate(id, grade, description) VALUES (1, 5, 'Blabbering');
INSERT INTO rate(id, grade, description) VALUES (2, 4, 'Blabbering');
INSERT INTO rate(id, grade, description) VALUES (3, 3, 'Blabbering');
INSERT INTO rate(id, grade, description) VALUES (4, 2, 'Blabbering');
INSERT INTO rate(id, grade, description) VALUES (5, 1, 'Blabbering');

INSERT INTO session (id, user_id, lastActivity, token) VALUES (1, 1, '2020-01-01', 'token');
INSERT INTO session (id, user_id, lastActivity, token) VALUES (2, 2, '2020-01-01', 'token');
INSERT INTO session (id, user_id, lastActivity, token) VALUES (3, 3, '2020-01-01', 'token');
INSERT INTO session (id, user_id, lastActivity, token) VALUES (4, 4, '2020-01-01', 'token');
INSERT INTO session (id, user_id, lastActivity, token) VALUES (5, 5, '2020-01-01', 'token');

INSERT INTO skill(id, name) VALUES (1, 'Java');
INSERT INTO skill(id, name) VALUES (2, 'C++');
INSERT INTO skill(id, name) VALUES (3, 'Python');
INSERT INTO skill(id, name) VALUES (4, 'C#');
INSERT INTO skill(id, name) VALUES (5, 'HTML');

INSERT INTO user_internshipoffer(user_id, internshipoffer_id) VALUES (1, 1);
INSERT INTO user_internshipoffer(user_id, internshipoffer_id) VALUES (2, 2);
INSERT INTO user_internshipoffer(user_id, internshipoffer_id) VALUES (3, 3);
INSERT INTO user_internshipoffer(user_id, internshipoffer_id) VALUES (4, 4);
INSERT INTO user_internshipoffer(user_id, internshipoffer_id) VALUES (5, 5);

INSERT INTO user_rate(user_id, rate_id) VALUES (1, 1);
INSERT INTO user_rate(user_id, rate_id) VALUES (2, 2);
INSERT INTO user_rate(user_id, rate_id) VALUES (3, 3);
INSERT INTO user_rate(user_id, rate_id) VALUES (4, 4);
INSERT INTO user_rate(user_id, rate_id) VALUES (5, 5);

INSERT INTO user_skill(user_id, skill_id) VALUES (1, 1);
INSERT INTO user_skill(user_id, skill_id) VALUES (2, 2);
INSERT INTO user_skill(user_id, skill_id) VALUES (3, 3);
INSERT INTO user_skill(user_id, skill_id) VALUES (4, 4);
INSERT INTO user_skill(user_id, skill_id) VALUES (5, 5);

INSERT INTO internshipoffer_rate(internshipoffer_id, rate_id) VALUES (1, 1);
INSERT INTO internshipoffer_rate(internshipoffer_id, rate_id) VALUES (2, 2);
INSERT INTO internshipoffer_rate(internshipoffer_id, rate_id) VALUES (3, 3);
INSERT INTO internshipoffer_rate(internshipoffer_id, rate_id) VALUES (4, 4);
INSERT INTO internshipoffer_rate(internshipoffer_id, rate_id) VALUES (5, 5);

INSERT INTO internshipoffer_skill(internshipoffer_id, skill_id) VALUES (1, 1);
INSERT INTO internshipoffer_skill(internshipoffer_id, skill_id) VALUES (2, 2);
INSERT INTO internshipoffer_skill(internshipoffer_id, skill_id) VALUES (3, 3);
INSERT INTO internshipoffer_skill(internshipoffer_id, skill_id) VALUES (4, 4);
INSERT INTO internshipoffer_skill(internshipoffer_id, skill_id) VALUES (5, 5);

