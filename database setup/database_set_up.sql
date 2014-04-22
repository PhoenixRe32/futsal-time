CREATE DATABASE IF NOT EXISTS futsalti_futsal_thoi
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

USE futsalti_futsal_thoi;

GRANT SELECT, INSERT, UPDATE, DELETE ON futsalti_futsal_tests.* TO 'futsalti_guest'@'localhost' IDENTIFIED BY '1qazXSW@3edcVFR$';

CREATE TABLE IF NOT EXISTS arenas
(
id                 TINYINT UNSIGNED                        NOT NULL AUTO_INCREMENT,
name               VARCHAR(40)                             NOT NULL,
nick               VARCHAR(10)                             NOT NULL,
region             TINYINT UNSIGNED                        NOT NULL,

PRIMARY KEY (id), 
UNIQUE (name),
UNIQUE (nick)
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

INSERT INTO `arenas` (`id`, `name`, `nick`, `region`) VALUES
(10, 'Nuevo Campo', 'nuca', 22),
(11, 'Paeek', 'paeek', 22),
(12, 'Campeone', 'camp1', 22),
(13, 'THOI', 'thoi', 22)
;

CREATE TABLE IF NOT EXISTS arenaSettings
(
id                 TINYINT UNSIGNED                        NOT NULL,
phones             VARCHAR(50)                             NOT NULL,
bookingLimit       TINYINT UNSIGNED                        NOT NULL DEFAULT 2,
smsContact         VARCHAR(10)                             NULL,
smsCustomer        BOOLEAN                                 NOT NULL DEFAULT FALSE,
smsManager         BOOLEAN                                 NOT NULL DEFAULT FALSE,
slotFormatClient   ENUM('15','30', '60')                   NOT NULL DEFAULT '30',
slotFormatManager  ENUM('15','30', '60')                   NOT NULL DEFAULT '15',
refreshRate        SMALLINT UNSIGNED                       NOT NULL DEFAULT 0,

PRIMARY KEY (id), 

CONSTRAINT fk_arenaID2 FOREIGN KEY (id) REFERENCES arenas (id)
ON UPDATE CASCADE ON DELETE CASCADE
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

INSERT INTO `arenaSettings` (`id`, `phones`, `bookingLimit`, `smsContact`, `smsCustomer`, `smsManager`, `slotFormatClient`, `slotFormatManager`, `refreshRate`) VALUES
(10, '99530500 | 99223337', 2, NULL, FALSE, FALSE, '60', '60', 30),
(11, '22468046', 2, NULL, FALSE, FALSE, '30', '30', 120),
(12, '99403603 | 99641778', 2, NULL, FALSE, FALSE, '30', '30', 120),
(13, '99890630 | 99920328 | 99174208', 2, '99680453', FALSE, FALSE, '30', '30', 120)
;


CREATE TABLE IF NOT EXISTS managers
(
id                 SMALLINT UNSIGNED                       NOT NULL AUTO_INCREMENT,
arena              TINYINT UNSIGNED                        NOT NULL,
name               VARCHAR(40)                             NOT NULL,
password           CHAR(128)                               NOT NULL,
phones             BOOLEAN                                 NOT NULL DEFAULT FALSE,
bookingLimit       BOOLEAN                                 NOT NULL DEFAULT FALSE,
smsContact         BOOLEAN                                 NOT NULL DEFAULT FALSE,
smsCustomer        BOOLEAN                                 NOT NULL DEFAULT FALSE,
smsManager         BOOLEAN                                 NOT NULL DEFAULT FALSE,
slotFormatClient   BOOLEAN                                 NOT NULL DEFAULT FALSE,
slotFormatManager  BOOLEAN                                 NOT NULL DEFAULT FALSE,
refreshRate        BOOLEAN                                 NOT NULL DEFAULT FALSE,


PRIMARY KEY (id), 
UNIQUE (arena, name),

CONSTRAINT fk_arenaID3 FOREIGN KEY (arena) REFERENCES arenas (id)
ON UPDATE CASCADE ON DELETE CASCADE
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

INSERT INTO `managers` (`id`, `arena`, `name`, `password`, `phones`, `bookingLimit`, `smsContact`, `smsCustomer`, `smsManager`, `slotFormatClient`, `slotFormatManager`, `refreshRate`) VALUES
(1, 10, 'manager', '$6$rounds=8888$10manager$FREnGf.odeEoxtRcWEcVW.vwVEJuCfdkB3H7XtdTHYhRP4ciOGWZPl3oJtDZPUTazKJWMcrQ7F.q3q3EW9i5t0', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
(2, 11, 'manager', '$6$rounds=8888$11manager$GCoSb.hkpYgoMNJo/x.dAJbO38WgpG2FqcWrlXo/VdzC9lh7z/7kb9FNmMjeD9ZUQNA6nGh4nstYAbPLmyJdC.', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
(3, 12, 'manager', '$6$rounds=8888$12manager$P44jz9VkatzZ.7JOXMulOEdKhOhNDDAcBBDZ2aNYMHSKsvF.NDBOgvuEdJ2pSZnW/DmTitjuLhU4y99w/Phaa0', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE),
(4, 13, 'manager', '$6$rounds=8888$13manager$sZbbRJsqul2ImN4TsU.A/livAKvkyjd7mDg/Zzf1jkj0GQ6lbo7EztT6K7NstFIWY6E4i2AyUT7IpdNL/Kp4h1', TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE, TRUE);


CREATE TABLE IF NOT EXISTS smsTimes
(
id                 SMALLINT UNSIGNED                       NOT NULL AUTO_INCREMENT,
arena              TINYINT UNSIGNED                        NOT NULL,
startAt            TIME                                    NOT NULL,
endAt              TIME                                    NOT NULL,

PRIMARY KEY (id), 
UNIQUE (arena, startAt, endAt),

CONSTRAINT fk_arenaID FOREIGN KEY (arena) REFERENCES arenas (id)
ON UPDATE CASCADE ON DELETE CASCADE
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS customers
(
email              VARCHAR(40)                             NULL,
name               VARCHAR(40)                             NULL,
phone              VARCHAR(10)                             NOT NULL,
password           CHAR(128)                               NULL,
alertChallenge     BOOLEAN                                 NOT NULL DEFAULT FALSE,
validated          VARCHAR(16)                             NOT NULL,

PRIMARY KEY (phone)
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;
  
INSERT INTO `customers` (`email`, `name`, `phone`, `password`, `alertChallenge`, `validated`) VALUES
("vhf_andrew@yahoo.gr", "Andreas Andreou", "99680453", '$6$rounds=8888$vhf_andrew@yahoo$Jt.DeCHONKMKetBxmm5b4ywNGCnOdvxUgZ7/pWT2/EDqSFZJd/MAwjSgq7r1GNYo11Mc6ncXH.zerUjExVIwA1', false, "VALIDATED"),
("zenon1020@me.com", "Zenon Chrysostomou", "99798951", '$6$rounds=8888$zenon1020@me.com$ekfCwJX5/QXsk.MZRgNVSV4BU1WQQIxQSGTdSm30G9h73Y7pEPyXb.2vRS9mmq8aYBSNjTYy1Uknl2yt6eGRE/', false, "VALIDATED"),
('', '', '96443545', NULL, FALSE, 'xPxdnbEnMqvyQNU0'),
('', '', '99465656', NULL, FALSE, '7YKE3dmpjDnsGmLR'),
('', 'andreas konstantinou (ploumis)', '97849498', NULL, FALSE, 'y6OQPJXBVSF8mdKN'),
('', 'Ρόδος', '99890630', NULL, FALSE, 'HKhGNpVqZ06kFJKr')
;
 
CREATE TABLE IF NOT EXISTS customersRep
(
customerPhone      VARCHAR(10)                             NOT NULL,
arenaId            TINYINT UNSIGNED                        NOT NULL,
reputation         ENUM('GOOD', 'NEUTRAL', 'BAD')          NOT NULL DEFAULT 'NEUTRAL',
notes              VARCHAR(512)                            NULL,

PRIMARY KEY (customerPhone, arenaId),

CONSTRAINT fk_arenas_rep FOREIGN KEY (arenaId) REFERENCES arenas (id)
ON UPDATE CASCADE ON DELETE CASCADE,

CONSTRAINT fk_customers_rep FOREIGN KEY (customerPhone) REFERENCES customers (phone)
ON UPDATE CASCADE ON DELETE CASCADE
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

INSERT INTO `customersRep` (`customerPhone`, `arenaId`, `reputation`, `notes`) VALUES
('99798951', 10, 'GOOD', NULL),
('99680453', 10, 'BAD', NULL),
('96443545', 10, 'NEUTRAL', NULL),
('99467843', 10, 'NEUTRAL', NULL),
('99465656', 10, 'NEUTRAL', NULL),
('99798951', 11, 'GOOD', NULL),
('99680453', 11, 'BAD', NULL),
('96443545', 11, 'NEUTRAL', NULL),
('99467843', 11, 'NEUTRAL', NULL),
('99465656', 11, 'NEUTRAL', NULL),
('99798951', 12, 'GOOD', NULL),
('99680453', 12, 'BAD', NULL),
('96443545', 12, 'NEUTRAL', NULL),
('99467843', 12, 'NEUTRAL', NULL),
('99465656', 12, 'NEUTRAL', NULL),
('99798951', 13, 'GOOD', NULL),
('99680453', 13, 'BAD', NULL),
('96443545', 13, 'NEUTRAL', NULL),
('99467843', 13, 'NEUTRAL', NULL),
('99465656', 13, 'NEUTRAL', NULL)
;


CREATE TABLE IF NOT EXISTS customersAlerts
(
alertId            INT UNSIGNED                            NOT NULL AUTO_INCREMENT,
customerPhone      VARCHAR(10)                             NOT NULL,
arenaId            TINYINT UNSIGNED                        NOT NULL,
date               DATE                                    NOT NULL,
time               TIME                                    NOT NULL,
fieldSize          ENUM('5X5','6X6','7X7','8X8','9X9','10X10')   NOT NULL DEFAULT '5X5',

PRIMARY KEY (alertId),
UNIQUE (customerPhone, arenaId, date, time),

CONSTRAINT fk_arena_al FOREIGN KEY (arenaId) REFERENCES arenas (id)
ON UPDATE CASCADE ON DELETE CASCADE,

CONSTRAINT fk_customer_al FOREIGN KEY (customerPhone) REFERENCES customers (phone)
ON UPDATE CASCADE ON DELETE CASCADE
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

  
CREATE TABLE IF NOT EXISTS reservations
(
id                 MEDIUMINT UNSIGNED                      NOT NULL AUTO_INCREMENT,
arena              VARCHAR(10)                             NOT NULL,
date               DATE                                    NOT NULL,
time               TIME                                    NOT NULL,
field              TINYINT UNSIGNED                        NOT NULL,
gameType           ENUM('MATCH','CHALLENGE', 'MATCH_C')    NOT NULL DEFAULT 'MATCH',
fieldSize          ENUM('5X5','6X6','7X7','8X8','9X9','10X10')   NOT NULL DEFAULT '5X5',
duration           TINYINT UNSIGNED                        NOT NULL DEFAULT 4,
customer           VARCHAR(10)                             NULL,
opponent           VARCHAR(10)                             NULL,
booking            TIMESTAMP                               NOT NULL DEFAULT CURRENT_TIMESTAMP,

PRIMARY KEY (id),
UNIQUE (arena,date,time,field),

CONSTRAINT fk_customer_res FOREIGN KEY (customer) REFERENCES customers (phone)
ON UPDATE CASCADE ON DELETE SET NULL
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS fields_thoi
(
fieldId            TINYINT UNSIGNED                        NOT NULL AUTO_INCREMENT,
type               ENUM('S','D')                           NOT NULL DEFAULT 'S',
fieldSize          ENUM('5X5','6X6','7X7','8X8','9X9','10X10')   NOT NULL DEFAULT '5X5',
containing         VARCHAR(10)                             NULL,

PRIMARY KEY (fieldId)
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

INSERT INTO fields_thoi VALUES
(1, 'S', '6X6', NULL),
(2, 'S', '5X5', NULL),
(3, 'S', '6X6', NULL),
(4, 'D', '7X7', '2,3'),
(5, 'D', '7X7', '1,2'),
(6, 'D', '9X9', '1,2,3');


CREATE TABLE IF NOT EXISTS game_slots_thoi
(
id                 MEDIUMINT UNSIGNED                      NOT NULL AUTO_INCREMENT,
date               DATE                                    NOT NULL,
time               TIME                                    NOT NULL,
field              TINYINT UNSIGNED                        NOT NULL,
game               ENUM('N','MS','MH','MD','C','MC','D','U') NOT NULL DEFAULT 'N',
status             ENUM('E','O','E_O')                     NOT NULL DEFAULT 'E',

PRIMARY KEY (id),
UNIQUE (date,time,field),

CONSTRAINT fk_field_thoi FOREIGN KEY (field) REFERENCES fields_thoi (fieldId)
ON UPDATE CASCADE
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS fields_nuca
(
fieldId            TINYINT UNSIGNED                        NOT NULL AUTO_INCREMENT,
type               ENUM('S','D')                           NOT NULL DEFAULT 'S',
fieldSize          ENUM('5X5','6X6','7X7','8X8','9X9','10X10')   NOT NULL DEFAULT '5X5',
containing         VARCHAR(10)                             NULL,

PRIMARY KEY (fieldId)
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

INSERT INTO fields_nuca VALUES
(1, 'S', '5X5', NULL),
(2, 'S', '5X5', NULL),
(3, 'S', '5X5', NULL),
(4, 'S', '5X5', NULL),
(5, 'S', '5X5', NULL),
(6, 'S', '5X5', NULL),
(7, 'S', '5X5', NULL),
(8, 'S', '5X5', NULL),
(9, 'D', '10X10', '7,8');


CREATE TABLE IF NOT EXISTS game_slots_nuca
(
id                 MEDIUMINT UNSIGNED                      NOT NULL AUTO_INCREMENT,
date               DATE                                    NOT NULL,
time               TIME                                    NOT NULL,
field              TINYINT UNSIGNED                        NOT NULL,
game               ENUM('N','MS','MH','MD','C','MC','D','U') NOT NULL DEFAULT 'N',
status             ENUM('E','O','E_O')                     NOT NULL DEFAULT 'E',

PRIMARY KEY (id),
UNIQUE (date,time,field),

CONSTRAINT fk_field_thoi FOREIGN KEY (field) REFERENCES fields_thoi (fieldId)
ON UPDATE CASCADE
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;




CREATE TABLE IF NOT EXISTS fields_paeek
(
fieldId            TINYINT UNSIGNED                        NOT NULL AUTO_INCREMENT,
type               ENUM('S','D')                           NOT NULL DEFAULT 'S',
fieldSize          ENUM('5X5','6X6','7X7','8X8','9X9','10X10')   NOT NULL DEFAULT '5X5',
containing         VARCHAR(10)                             NULL,

PRIMARY KEY (fieldId)
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

INSERT INTO fields_paeek VALUES
(1, 'S', '5X5', NULL),
(2, 'S', '5X5', NULL),
(3, 'S', '5X5', NULL);	


CREATE TABLE IF NOT EXISTS game_slots_paeek
(
id                 MEDIUMINT UNSIGNED                      NOT NULL AUTO_INCREMENT,
date               DATE                                    NOT NULL,
time               TIME                                    NOT NULL,
field              TINYINT UNSIGNED                        NOT NULL,
game               ENUM('N','MS','MH','MD','C','MC','D','U') NOT NULL DEFAULT 'N',
status             ENUM('E','O','E_O')                     NOT NULL DEFAULT 'E',

PRIMARY KEY (id),
UNIQUE (date,time,field),

CONSTRAINT fk_field_thoi FOREIGN KEY (field) REFERENCES fields_thoi (fieldId)
ON UPDATE CASCADE
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;





CREATE TABLE IF NOT EXISTS fields_camp1
(
fieldId            TINYINT UNSIGNED                        NOT NULL AUTO_INCREMENT,
type               ENUM('S','D')                           NOT NULL DEFAULT 'S',
fieldSize          ENUM('5X5','6X6','7X7','8X8','9X9','10X10')   NOT NULL DEFAULT '5X5',
containing         VARCHAR(10)                             NULL,

PRIMARY KEY (fieldId)
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

INSERT INTO fields_camp1 VALUES
(1, 'S', '5X5', NULL),
(2, 'S', '5X5', NULL),
(3, 'S', '5X5', NULL),
(4, 'S', '5X5', NULL),
(5, 'S', '5X5', NULL),
(6, 'S', '5X5', NULL),
(7, 'D', '10X10', '4,5,6');


CREATE TABLE IF NOT EXISTS game_slots_camp1
(
id                 MEDIUMINT UNSIGNED                      NOT NULL AUTO_INCREMENT,
date               DATE                                    NOT NULL,
time               TIME                                    NOT NULL,
field              TINYINT UNSIGNED                        NOT NULL,
game               ENUM('N','MS','MH','MD','C','MC','D','U') NOT NULL DEFAULT 'N',
status             ENUM('E','O','E_O')                     NOT NULL DEFAULT 'E',

PRIMARY KEY (id),
UNIQUE (date,time,field),

CONSTRAINT fk_field_thoi FOREIGN KEY (field) REFERENCES fields_thoi (fieldId)
ON UPDATE CASCADE
)
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;




/*
ALTER TABLE customersAlerts
ADD CONSTRAINT fk_arena_al FOREIGN KEY (arenaId) REFERENCES arenas (id)
ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE customersAlerts
ADD CONSTRAINT fk_customer_al FOREIGN KEY (customerPhone) REFERENCES customers (phone)
ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE customersRep
ADD CONSTRAINT fk_arenas_rep FOREIGN KEY (arenaId) REFERENCES arenas (id)
ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE customersRep
ADD CONSTRAINT fk_customers_rep FOREIGN KEY (customerPhone) REFERENCES customers (phone)
ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE reservations
ADD CONSTRAINT fk_customer_res FOREIGN KEY (customer) REFERENCES customers (phone)
ON UPDATE CASCADE ON DELETE SET NULL;
*/