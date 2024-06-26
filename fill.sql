USE stagify;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE location;
TRUNCATE TABLE user;
TRUNCATE TABLE promo;
TRUNCATE TABLE user_promo;
TRUNCATE TABLE activity_sector;
TRUNCATE TABLE company;
TRUNCATE TABLE company_location;
TRUNCATE TABLE internship;
TRUNCATE TABLE company_internship;
TRUNCATE TABLE application;
TRUNCATE TABLE promo_location;
TRUNCATE TABLE rate;
TRUNCATE TABLE session;
TRUNCATE TABLE skill;
TRUNCATE TABLE user_internship;
TRUNCATE TABLE user_rate;
TRUNCATE TABLE user_skill;
TRUNCATE TABLE internship_rate;
TRUNCATE TABLE internship_skill;
TRUNCATE TABLE internship_promo;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO location (id, zipCode, city)
VALUES (1, 1000, 'Bruxelles');
INSERT INTO location (id, zipCode, city)
VALUES (2, 51100, 'Reims');
INSERT INTO location (id, zipCode, city)
VALUES (3, 75000, 'Paris');
INSERT INTO location (id, zipCode, city)
VALUES (4, 69000, 'Lyon');
INSERT INTO location (id, zipCode, city)
VALUES (5, 13000, 'Marseille');

# password = *Azertyui1
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (1, 1, 3, 'Julien', 'Turcot', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering',
        'julien.turcot@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (2, 2, 2, 'Mathieu', 'Gillet', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering',
        'mathieu.gillet@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (3, 3, 1, 'John', 'Doe', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering',
        'john.doe@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (4, 4, 1, 'MTH', 'Disk', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering',
        'disk.mth@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (5, 5, 1, 'Odd', 'Origin', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering',
        'odd.origin@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (6, 1, 3, 'user', '1', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.1@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (7, 2, 2, 'user', '2', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.2@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (8, 3, 1, 'user', '3', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.3@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (9, 4, 1, 'user', '4', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.4@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (10, 5, 1, 'user', '5', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.5@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (11, 1, 3, 'user', '6', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.6@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (12, 2, 2, 'user', '7', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.7@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (13, 3, 1, 'user', '8', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.8@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (14, 4, 1, 'user', '9', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.9@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (15, 1, 3, 'user', '10', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.10@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (16, 2, 2, 'user', '11', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.11@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (17, 3, 1, 'user', '12', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.12@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (18, 4, 1, 'user', '13', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.13@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (19, 1, 3, 'user', '14', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.14@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (20, 2, 2, 'user', '15', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.15@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (21, 3, 1, 'user', '16', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.16@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (22, 4, 1, 'user', '17', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.17@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (23, 1, 3, 'user', '18', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.18@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (24, 2, 2, 'user', '19', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.19@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);
INSERT INTO user (id, location_id, role, firstName, lastName, profilePicture, description, login, passwordHash,
                  deleted)
VALUES (25, 3, 1, 'user', '20', 'http://stagify.fr/assets/illustrations/profile.svg', 'Blabbering', 'user.20@gmail.com',
        '4cc5bf4e0c968f62e03d06aa26f184589364bdb55bde4607f50fe5bed8e05ba687486fd69027a2f7af1690b3cb961ef63d1d000f453c3655edf2d0e2b6ed51d5',
        0);


INSERT INTO promo (id, year, type, school)
VALUES (1, 2, 'Ingénieur', 'CESI');
INSERT INTO promo (id, year, type, school)
VALUES (2, 3, 'Medecin', 'CESI');
INSERT INTO promo (id, year, type, school)
VALUES (3, 1, 'Maçon', 'CESI');
INSERT INTO promo (id, year, type, school)
VALUES (4, 2, 'Boulanger', 'CESI');
INSERT INTO promo (id, year, type, school)
VALUES (5, 3, 'Dev', 'CESI');

INSERT INTO user_promo (user_id, promo_id)
VALUES (1, 1);
INSERT INTO user_promo (user_id, promo_id)
VALUES (2, 2);
INSERT INTO user_promo (user_id, promo_id)
VALUES (3, 3);
INSERT INTO user_promo (user_id, promo_id)
VALUES (4, 4);
INSERT INTO user_promo (user_id, promo_id)
VALUES (5, 5);
INSERT INTO user_promo (user_id, promo_id)
VALUES (6, 1);
INSERT INTO user_promo (user_id, promo_id)
VALUES (7, 2);
INSERT INTO user_promo (user_id, promo_id)
VALUES (8, 3);
INSERT INTO user_promo (user_id, promo_id)
VALUES (9, 4);
INSERT INTO user_promo (user_id, promo_id)
VALUES (10, 5);
INSERT INTO user_promo (user_id, promo_id)
VALUES (11, 1);
INSERT INTO user_promo (user_id, promo_id)
VALUES (12, 2);
INSERT INTO user_promo (user_id, promo_id)
VALUES (13, 3);
INSERT INTO user_promo (user_id, promo_id)
VALUES (14, 4);
INSERT INTO user_promo (user_id, promo_id)
VALUES (15, 5);
INSERT INTO user_promo (user_id, promo_id)
VALUES (16, 1);
INSERT INTO user_promo (user_id, promo_id)
VALUES (17, 2);
INSERT INTO user_promo (user_id, promo_id)
VALUES (18, 3);
INSERT INTO user_promo (user_id, promo_id)
VALUES (19, 4);
INSERT INTO user_promo (user_id, promo_id)
VALUES (20, 5);
INSERT INTO user_promo (user_id, promo_id)
VALUES (21, 1);
INSERT INTO user_promo (user_id, promo_id)
VALUES (22, 2);
INSERT INTO user_promo (user_id, promo_id)
VALUES (23, 3);
INSERT INTO user_promo (user_id, promo_id)
VALUES (24, 4);
INSERT INTO user_promo (user_id, promo_id)
VALUES (25, 5);

INSERT INTO activity_sector (id, name)
VALUES (1, 'Informatique');
INSERT INTO activity_sector (id, name)
VALUES (2, 'Médecine');
INSERT INTO activity_sector (id, name)
VALUES (3, 'Boulangerie');
INSERT INTO activity_sector (id, name)
VALUES (4, 'Maconnerie');
INSERT INTO activity_sector (id, name)
VALUES (5, 'Dev');
INSERT INTO activity_sector (id, name)
VALUES (6, 'Vente');
INSERT INTO activity_sector (id, name)
VALUES (7, 'Marketing');
INSERT INTO activity_sector (id, name)
VALUES (8, 'Communication');
INSERT INTO activity_sector (id, name)
VALUES (9, 'Finance');
INSERT INTO activity_sector (id, name)
VALUES (10, 'Ressources Humaines');

INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (1, 'Google', 'www.google.com', 100000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 1);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (2, 'Hopital', 'www.hopital.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 2);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (3, 'Boulangerie', 'www.boulangerie.com', 10, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 3);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (4, 'Maconnerie', 'www.maconnerie.com', 100, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 4);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (5, 'Dev', 'www.dev.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 5);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (6, 'Vente', 'www.vente.com', 10000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 6);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (7, 'Marketing', 'www.marketing.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 7);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (8, 'Communication', 'www.communication.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0,
        8);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (9, 'Finance', 'www.finance.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 9);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (10, 'Ressources Humaines', 'www.ressourceshumaines.com', 1000,
        'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 10);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (11, 'Google2', 'www.google.com', 100000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 1);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (12, 'Hopital2', 'www.hopital.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 2);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (13, 'Boulangerie2', 'www.boulangerie.com', 10, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 3);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (14, 'Maconnerie2', 'www.maconnerie.com', 100, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 4);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (15, 'Dev2', 'www.dev.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 5);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (16, 'Vente2', 'www.vente.com', 10000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 6);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (17, 'Marketing2', 'www.marketing.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 7);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (18, 'Communication2', 'www.communication.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg',
        0, 8);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (19, 'Finance2', 'www.finance.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 9);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (20, 'Ressources Humaines2', 'www.ressourceshumaines.com', 1000,
        'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 10);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (21, 'Google3', 'www.google.com', 100000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 1);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (22, 'Hopital3', 'www.hopital.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 2);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (23, 'Boulangerie3', 'www.boulangerie.com', 10, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 3);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (24, 'Maconnerie3', 'www.maconnerie.com', 100, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 4);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (25, 'Dev3', 'www.dev.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 5);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (26, 'Vente3', 'www.vente.com', 10000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 6);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (27, 'Marketing3', 'www.marketing.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 7);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (28, 'Communication3', 'www.communication.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg',
        0, 8);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (29, 'Finance3', 'www.finance.com', 1000, 'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 9);
INSERT INTO company (id, name, website, employeeCount, logoPicture, deleted, activitySector_id)
VALUES (30, 'Ressources Humaines3', 'www.ressourceshumaines.com', 1000,
        'http://stagify.fr/assets/illustrations/company_logo.svg', 0, 10);


INSERT INTO company_location (company_id, location_id)
VALUES (1, 1);
INSERT INTO company_location (company_id, location_id)
VALUES (2, 2);
INSERT INTO company_location (company_id, location_id)
VALUES (3, 3);
INSERT INTO company_location (company_id, location_id)
VALUES (4, 4);
INSERT INTO company_location (company_id, location_id)
VALUES (5, 5);
INSERT INTO company_location (company_id, location_id)
VALUES (6, 1);
INSERT INTO company_location (company_id, location_id)
VALUES (7, 2);
INSERT INTO company_location (company_id, location_id)
VALUES (8, 3);
INSERT INTO company_location (company_id, location_id)
VALUES (9, 4);
INSERT INTO company_location (company_id, location_id)
VALUES (10, 5);
INSERT INTO company_location (company_id, location_id)
VALUES (11, 1);
INSERT INTO company_location (company_id, location_id)
VALUES (12, 2);
INSERT INTO company_location (company_id, location_id)
VALUES (13, 3);
INSERT INTO company_location (company_id, location_id)
VALUES (14, 4);
INSERT INTO company_location (company_id, location_id)
VALUES (15, 5);
INSERT INTO company_location (company_id, location_id)
VALUES (16, 1);
INSERT INTO company_location (company_id, location_id)
VALUES (17, 2);
INSERT INTO company_location (company_id, location_id)
VALUES (18, 3);
INSERT INTO company_location (company_id, location_id)
VALUES (19, 4);
INSERT INTO company_location (company_id, location_id)
VALUES (20, 5);
INSERT INTO company_location (company_id, location_id)
VALUES (21, 1);
INSERT INTO company_location (company_id, location_id)
VALUES (22, 2);
INSERT INTO company_location (company_id, location_id)
VALUES (23, 3);
INSERT INTO company_location (company_id, location_id)
VALUES (24, 4);
INSERT INTO company_location (company_id, location_id)
VALUES (25, 5);
INSERT INTO company_location (company_id, location_id)
VALUES (26, 1);
INSERT INTO company_location (company_id, location_id)
VALUES (27, 2);
INSERT INTO company_location (company_id, location_id)
VALUES (28, 3);
INSERT INTO company_location (company_id, location_id)
VALUES (29, 4);
INSERT INTO company_location (company_id, location_id)
VALUES (30, 5);
INSERT INTO company_location (company_id, location_id)
VALUES (1, 2);
INSERT INTO company_location (company_id, location_id)
VALUES (1, 3);
INSERT INTO company_location (company_id, location_id)
VALUES (1, 4);
INSERT INTO company_location (company_id, location_id)
VALUES (1, 5);

INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (1, 1, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Ingenieur logiciel H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (2, 2, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Medecin H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (3, 3, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Boulanger H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (4, 4, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Macon H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (5, 5, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Dev H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (6, 1, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Vendeur');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (7, 2, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Marketeur');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (8, 3, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Communication H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (9, 4, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Finance H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (10, 5, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Ressources Humaines H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (11, 1, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Boucher ');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (12, 2, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Chirurgien H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (13, 3, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Patisserie H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (14, 4, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Charpentier H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (15, 5, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Developpeur Web H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (16, 1, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Vendeur H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (17, 2, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Marketeur H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (18, 3, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Communication H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (19, 4, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Finance H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (20, 5, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Ressources Humaines H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (21, 1, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Boucher H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (22, 2, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Chirurgien H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (23, 3, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Patisserie H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (24, 4, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Charpentier H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (25, 5, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Developpeur Web H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (26, 1, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Vendeur H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (27, 2, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Marketeur H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (28, 3, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Fabricant Nike');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (29, 4, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Finance H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (30, 5, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'faire le café');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (31, 1, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Boucher H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (32, 2, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Chirurgien H/F');
INSERT INTO internship (id, location_id, startDate, endDate, durationDays, lowSalary, highSalary, placeCount,
                        description, deleted, title)
VALUES (33, 3, '2020-01-01', '2020-01-31', 30, 1000, 2000, 10, 'Blabbering', 0, 'Patisserie H/F');

INSERT INTO company_internship (company_id, internship_id)
VALUES (1, 1);
INSERT INTO company_internship (company_id, internship_id)
VALUES (2, 2);
INSERT INTO company_internship (company_id, internship_id)
VALUES (3, 3);
INSERT INTO company_internship (company_id, internship_id)
VALUES (4, 4);
INSERT INTO company_internship (company_id, internship_id)
VALUES (5, 5);
INSERT INTO company_internship (company_id, internship_id)
VALUES (6, 6);
INSERT INTO company_internship (company_id, internship_id)
VALUES (7, 7);
INSERT INTO company_internship (company_id, internship_id)
VALUES (8, 8);
INSERT INTO company_internship (company_id, internship_id)
VALUES (1, 9);
INSERT INTO company_internship (company_id, internship_id)
VALUES (1, 10);
INSERT INTO company_internship (company_id, internship_id)
VALUES (1, 11);
INSERT INTO company_internship (company_id, internship_id)
VALUES (1, 12);
INSERT INTO company_internship (company_id, internship_id)
VALUES (1, 13);
INSERT INTO company_internship (company_id, internship_id)
VALUES (1, 14);
INSERT INTO company_internship (company_id, internship_id)
VALUES (1, 15);
INSERT INTO company_internship (company_id, internship_id)
VALUES (1, 16);
INSERT INTO company_internship (company_id, internship_id)
VALUES (1, 17);
INSERT INTO company_internship (company_id, internship_id)
VALUES (2, 18);
INSERT INTO company_internship (company_id, internship_id)
VALUES (2, 19);
INSERT INTO company_internship (company_id, internship_id)
VALUES (2, 20);
INSERT INTO company_internship (company_id, internship_id)
VALUES (2, 21);
INSERT INTO company_internship (company_id, internship_id)
VALUES (2, 22);
INSERT INTO company_internship (company_id, internship_id)
VALUES (2, 23);
INSERT INTO company_internship (company_id, internship_id)
VALUES (2, 24);
INSERT INTO company_internship (company_id, internship_id)
VALUES (2, 25);
INSERT INTO company_internship (company_id, internship_id)
VALUES (3, 26);
INSERT INTO company_internship (company_id, internship_id)
VALUES (3, 27);
INSERT INTO company_internship (company_id, internship_id)
VALUES (3, 28);
INSERT INTO company_internship (company_id, internship_id)
VALUES (3, 29);
INSERT INTO company_internship (company_id, internship_id)
VALUES (3, 30);
INSERT INTO company_internship (company_id, internship_id)
VALUES (3, 31);
INSERT INTO company_internship (company_id, internship_id)
VALUES (4, 32);
INSERT INTO company_internship (company_id, internship_id)
VALUES (4, 33);


INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (1, 1, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (2, 2, 'cv.pdf', 'coverLetter.pdf', 0, 2);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (3, 3, 'cv.pdf', 'coverLetter.pdf', 0, 3);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (4, 4, 'cv.pdf', 'coverLetter.pdf', 0, 4);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (5, 5, 'cv.pdf', 'coverLetter.pdf', 0, 5);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (6, 1, 'cv.pdf', 'coverLetter.pdf', 0, 6);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (7, 2, 'cv.pdf', 'coverLetter.pdf', 0, 7);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (8, 3, 'cv.pdf', 'coverLetter.pdf', 0, 8);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (9, 4, 'cv.pdf', 'coverLetter.pdf', 0, 9);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (10, 5, 'cv.pdf', 'coverLetter.pdf', 0, 10);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (11, 1, 'cv.pdf', 'coverLetter.pdf', 0, 11);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (12, 2, 'cv.pdf', 'coverLetter.pdf', 0, 12);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (13, 3, 'cv.pdf', 'coverLetter.pdf', 0, 13);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (14, 4, 'cv.pdf', 'coverLetter.pdf', 0, 14);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (15, 8, 'cv.pdf', 'coverLetter.pdf', 0, 15);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (16, 9, 'cv.pdf', 'coverLetter.pdf', 0, 16);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (17, 10, 'cv.pdf', 'coverLetter.pdf', 0, 17);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (18, 11, 'cv.pdf', 'coverLetter.pdf', 0, 18);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (19, 7, 'cv.pdf', 'coverLetter.pdf', 0, 19);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (20, 6, 'cv.pdf', 'coverLetter.pdf', 0, 20);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (21, 9, 'cv.pdf', 'coverLetter.pdf', 0, 21);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (22, 10, 'cv.pdf', 'coverLetter.pdf', 0, 22);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (23, 15, 'cv.pdf', 'coverLetter.pdf', 0, 23);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (24, 16, 'cv.pdf', 'coverLetter.pdf', 0, 24);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (25, 17, 'cv.pdf', 'coverLetter.pdf', 0, 25);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (26, 18, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (27, 19, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (28, 20, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (29, 21, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (30, 22, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (31, 23, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (32, 24, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (33, 25, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (34, 12, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (35, 13, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (36, 14, 'cv.pdf', 'coverLetter.pdf', 0, 1);
INSERT INTO application (id, user_id, cvFile, coverLetterFile, accepted, internship_id)
VALUES (37, 15, 'cv.pdf', 'coverLetter.pdf', 0, 1);

INSERT INTO promo_location (promo_id, location_id)
VALUES (1, 1);
INSERT INTO promo_location (promo_id, location_id)
VALUES (2, 2);
INSERT INTO promo_location (promo_id, location_id)
VALUES (3, 3);
INSERT INTO promo_location (promo_id, location_id)
VALUES (4, 4);
INSERT INTO promo_location (promo_id, location_id)
VALUES (5, 5);

INSERT INTO rate(id, grade, description)
VALUES (1, 5, 'Blabbering');
INSERT INTO rate(id, grade, description)
VALUES (2, 4, 'Blabbering');
INSERT INTO rate(id, grade, description)
VALUES (3, 3, 'Blabbering');
INSERT INTO rate(id, grade, description)
VALUES (4, 2, 'Blabbering');
INSERT INTO rate(id, grade, description)
VALUES (5, 1, 'Blabbering');
INSERT INTO rate(id, grade, description)
VALUES (6, 5, 'Blabbering');
INSERT INTO rate(id, grade, description)
VALUES (7, 4, 'Blabbering');
INSERT INTO rate(id, grade, description)
VALUES (8, 3, 'Blabbering');
INSERT INTO rate(id, grade, description)
VALUES (9, 2, 'Blabbering');
INSERT INTO rate(id, grade, description)
VALUES (10, 1, 'Blabbering');

INSERT INTO skill(id, name)
VALUES (1, 'Java');
INSERT INTO skill(id, name)
VALUES (2, 'C++');
INSERT INTO skill(id, name)
VALUES (3, 'Python');
INSERT INTO skill(id, name)
VALUES (4, 'C#');
INSERT INTO skill(id, name)
VALUES (5, 'HTML');
INSERT INTO skill(id, name)
VALUES (6, 'CSS');
INSERT INTO skill(id, name)
VALUES (7, 'Javascript');
INSERT INTO skill(id, name)
VALUES (8, 'PHP');
INSERT INTO skill(id, name)
VALUES (9, 'SQL');
INSERT INTO skill(id, name)
VALUES (10, 'NoSQL');

INSERT INTO user_internship(user_id, internship_id)
VALUES (1, 1);
INSERT INTO user_internship(user_id, internship_id)
VALUES (2, 2);
INSERT INTO user_internship(user_id, internship_id)
VALUES (3, 3);
INSERT INTO user_internship(user_id, internship_id)
VALUES (4, 4);
INSERT INTO user_internship(user_id, internship_id)
VALUES (5, 5);
INSERT INTO user_internship(user_id, internship_id)
VALUES (6, 6);
INSERT INTO user_internship(user_id, internship_id)
VALUES (7, 7);
INSERT INTO user_internship(user_id, internship_id)
VALUES (8, 8);
INSERT INTO user_internship(user_id, internship_id)
VALUES (9, 9);
INSERT INTO user_internship(user_id, internship_id)
VALUES (10, 10);
INSERT INTO user_internship(user_id, internship_id)
VALUES (11, 11);
INSERT INTO user_internship(user_id, internship_id)
VALUES (12, 1);
INSERT INTO user_internship(user_id, internship_id)
VALUES (13, 1);
INSERT INTO user_internship(user_id, internship_id)
VALUES (14, 1);
INSERT INTO user_internship(user_id, internship_id)
VALUES (15, 1);
INSERT INTO user_internship(user_id, internship_id)
VALUES (16, 1);
INSERT INTO user_internship(user_id, internship_id)
VALUES (17, 1);
INSERT INTO user_internship(user_id, internship_id)
VALUES (18, 1);
INSERT INTO user_internship(user_id, internship_id)
VALUES (18, 2);
INSERT INTO user_internship(user_id, internship_id)
VALUES (18, 3);
INSERT INTO user_internship(user_id, internship_id)
VALUES (18, 4);
INSERT INTO user_internship(user_id, internship_id)
VALUES (18, 5);
INSERT INTO user_internship(user_id, internship_id)
VALUES (18, 6);
INSERT INTO user_internship(user_id, internship_id)
VALUES (18, 7);

INSERT INTO user_rate(user_id, rate_id)
VALUES (1, 1);
INSERT INTO user_rate(user_id, rate_id)
VALUES (2, 2);
INSERT INTO user_rate(user_id, rate_id)
VALUES (3, 3);
INSERT INTO user_rate(user_id, rate_id)
VALUES (4, 4);
INSERT INTO user_rate(user_id, rate_id)
VALUES (5, 5);
INSERT INTO user_rate(user_id, rate_id)
VALUES (6, 6);
INSERT INTO user_rate(user_id, rate_id)
VALUES (7, 7);
INSERT INTO user_rate(user_id, rate_id)
VALUES (8, 8);
INSERT INTO user_rate(user_id, rate_id)
VALUES (9, 9);
INSERT INTO user_rate(user_id, rate_id)
VALUES (10, 10);

INSERT INTO user_skill(user_id, skill_id)
VALUES (1, 1);
INSERT INTO user_skill(user_id, skill_id)
VALUES (2, 2);
INSERT INTO user_skill(user_id, skill_id)
VALUES (3, 3);
INSERT INTO user_skill(user_id, skill_id)
VALUES (4, 4);
INSERT INTO user_skill(user_id, skill_id)
VALUES (5, 5);
INSERT INTO user_skill(user_id, skill_id)
VALUES (6, 6);
INSERT INTO user_skill(user_id, skill_id)
VALUES (7, 7);
INSERT INTO user_skill(user_id, skill_id)
VALUES (8, 8);
INSERT INTO user_skill(user_id, skill_id)
VALUES (9, 9);
INSERT INTO user_skill(user_id, skill_id)
VALUES (10, 10);
INSERT INTO user_skill(user_id, skill_id)
VALUES (10, 1);
INSERT INTO user_skill(user_id, skill_id)
VALUES (10, 2);
INSERT INTO user_skill(user_id, skill_id)
VALUES (10, 3);
INSERT INTO user_skill(user_id, skill_id)
VALUES (10, 4);
INSERT INTO user_skill(user_id, skill_id)
VALUES (10, 5);
INSERT INTO user_skill(user_id, skill_id)
VALUES (1, 2);
INSERT INTO user_skill(user_id, skill_id)
VALUES (3, 2);
INSERT INTO user_skill(user_id, skill_id)
VALUES (4, 2);
INSERT INTO user_skill(user_id, skill_id)
VALUES (5, 2);
INSERT INTO user_skill(user_id, skill_id)
VALUES (6, 2);


INSERT INTO internship_rate(internship_id, rate_id)
VALUES (1, 1);
INSERT INTO internship_rate(internship_id, rate_id)
VALUES (2, 2);
INSERT INTO internship_rate(internship_id, rate_id)
VALUES (3, 3);
INSERT INTO internship_rate(internship_id, rate_id)
VALUES (4, 4);
INSERT INTO internship_rate(internship_id, rate_id)
VALUES (5, 5);
INSERT INTO internship_rate(internship_id, rate_id)
VALUES (6, 6);
INSERT INTO internship_rate(internship_id, rate_id)
VALUES (7, 7);
INSERT INTO internship_rate(internship_id, rate_id)
VALUES (8, 8);
INSERT INTO internship_rate(internship_id, rate_id)
VALUES (9, 9);
INSERT INTO internship_rate(internship_id, rate_id)
VALUES (10, 10);

INSERT INTO internship_skill(internship_id, skill_id)
VALUES (1, 1);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (2, 2);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (3, 3);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (4, 4);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (5, 5);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (6, 6);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (7, 7);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (8, 8);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (9, 9);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (1, 10);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (1, 2);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (1, 3);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (1, 4);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (1, 5);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (2, 6);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (3, 6);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (4, 6);
INSERT INTO internship_skill(internship_id, skill_id)
VALUES (5, 6);

INSERT INTO internship_promo(internship_id, promo_id)
VALUES (1, 1);
INSERT INTO internship_promo(internship_id, promo_id)
VALUES (2, 2);
INSERT INTO internship_promo(internship_id, promo_id)
VALUES (3, 3);
INSERT INTO internship_promo(internship_id, promo_id)
VALUES (4, 4);
INSERT INTO internship_promo(internship_id, promo_id)
VALUES (5, 5);
INSERT INTO internship_promo(internship_id, promo_id)
VALUES (6, 1);
INSERT INTO internship_promo(internship_id, promo_id)
VALUES (7, 2);
INSERT INTO internship_promo(internship_id, promo_id)
VALUES (1, 3);
INSERT INTO internship_promo(internship_id, promo_id)
VALUES (1, 4);
INSERT INTO internship_promo(internship_id, promo_id)
VALUES (1, 5);
INSERT INTO internship_promo(internship_id, promo_id)
VALUES (2, 1);

