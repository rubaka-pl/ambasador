1) Database Setup

CREATE TABLE ambasador_pmb_users (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    password VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    email VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    promo_code VARCHAR(10) COLLATE utf8mb4_general_ci NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    phone VARCHAR(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
    facebook TINYINT(1) DEFAULT 0,
    instagram TINYINT(1) DEFAULT 0,
    recommend TINYINT(1) DEFAULT 0,
    tiktok TINYINT(1) DEFAULT 0,
    youtube TINYINT(1) DEFAULT 0,
    other TINYINT(1) DEFAULT 0,
    paid_bonus INT(11) NOT NULL DEFAULT 0,
    current_bonus INT(11) NOT NULL DEFAULT 0
);

CREATE TABLE ambasador_pmb_purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Unikalny identyfikator rekordu
    order_id INT NOT NULL,              -- ID zamówienia z tabeli pmb_zamowienia
    firstName VARCHAR(64) NOT NULL,     -- Imię
    lastName VARCHAR(64) NOT NULL,      -- Nazwisko
    street VARCHAR(124),                -- Ulica
    postalCode VARCHAR(24),             -- Kod pocztowy
    city VARCHAR(64),                   -- Miasto
    phone VARCHAR(24),                  -- Numer telefonu
    email VARCHAR(64),                  -- Adres e-mail
    message TEXT,                       -- Wiadomość
    employerNIP VARCHAR(64),            -- NIP pracodawcy
    employerName VARCHAR(124),          -- Nazwa pracodawcy
    cenaSuma FLOAT,                     -- Całkowita cena zamówienia
    cenaBox FLOAT,                      -- Cena boxa
    cenaAsortyment FLOAT,               -- Cena asortymentu
    produktZamowienie VARCHAR(255),     -- Nazwa produktu
    asortymentZamowienie TEXT,          -- Opis asortymentu
    szerokoscBoxa VARCHAR(24),          -- Szerokość boxa
    wysokoscSkrzyni VARCHAR(24),        -- Wysokość skrzyni
    wysokoscPodstawy VARCHAR(24),       -- Wysokość podstawy
    md5sum VARCHAR(124),                -- Suma kontrolna MD5
    securityCode VARCHAR(24),           -- Kod zabezpieczający
    platnosc VARCHAR(24),               -- Sposób płatności
    cenaTransport FLOAT,                -- Koszt transportu
    wojewodztwo VARCHAR(24),            -- Województwo
    crc VARCHAR(24),                    -- Kod CRC
    status VARCHAR(64),                 -- Status zamówienia
    czy_rabat VARCHAR(64),              -- Czy zastosowano rabat
    kod_rabatowy VARCHAR(64),           -- Kod rabatowy
    kwota_rabatu VARCHAR(64),           -- Kwota rabatu
    zrodlo VARCHAR(64),                 -- Źródło zamówienia
    notka TEXT,                         -- Notatka
    data_ponowny_kontakt VARCHAR(64),   -- Data ponownego kontaktu
    data_montazu DATE,                  -- Data montażu
    opiekun VARCHAR(64)                 -- Opiekun zamówienia
);
ALTER TABLE `ambasador_pmb_purchases`
ADD COLUMN `quantity` INT(11) DEFAULT 1;
ALTER TABLE `ambasador_pmb_purchases`
ADD COLUMN `purchase_date` DATETIME DEFAULT CURRENT_TIMESTAMP;


3) Trigger for Bonus Calculation (sql)

Kiedy nowy zakup boxa zostanie dodany do tabeli ambasador_pmb_purchases, 
zostanie uruchomiony trigger, który zwiększy current_bonus ambasadora o 200 PLN 
za każdy zakupiony produkt.

DELIMITER //

CREATE TRIGGER after_purchase_insert
AFTER INSERT ON ambasador_pmb_purchases
FOR EACH ROW
BEGIN
    DECLARE user_id INT;
    DECLARE bonus INT;

    -- Находим пользователя только по промокоду
    SELECT id INTO user_id
    FROM ambasador_pmb_users
    WHERE promo_code = NEW.kod_rabatowy;

    -- Если пользователь найден
    IF user_id IS NOT NULL THEN
        -- Увеличиваем бонус на 200
        SET bonus = 200;

        -- Обновляем current_bonus для найденного пользователя
        UPDATE ambasador_pmb_users
        SET current_bonus = current_bonus + bonus
        WHERE id = user_id;
    END IF;

END;
//

DELIMITER ;




4)
trigger dla pmb_zamowienia

DELIMITER $$

CREATE TRIGGER after_pmb_zamowienia_insert
AFTER INSERT ON pmb_zamowienia
FOR EACH ROW
BEGIN
    DECLARE promo_exists INT;

    -- Проверяем, существует ли промокод в таблице ambasador_pmb_users
    SELECT COUNT(*) INTO promo_exists
    FROM ambasador_pmb_users
    WHERE promo_code = NEW.kod_rabatowy;

    -- Если промокод существует, копируем данные в таблицу ambasador_pmb_purchases
    IF promo_exists > 0 THEN
        INSERT INTO ambasador_pmb_purchases (
            order_id, firstName, lastName, street, postalCode, city, phone, email, message,
            employerNIP, employerName, cenaSuma, cenaBox, cenaAsortyment, produktZamowienie,
            asortymentZamowienie, szerokoscBoxa, wysokoscSkrzyni, wysokoscPodstawy, md5sum,
            securityCode, platnosc, cenaTransport, wojewodztwo, crc, status, czy_rabat,
            kod_rabatowy, kwota_rabatu, zrodlo, notka, data_ponowny_kontakt, data_montazu, opiekun
        ) VALUES (
            NEW.id, NEW.firstName, NEW.lastName, NEW.street, NEW.postalCode, NEW.city, NEW.phone,
            NEW.email, NEW.message, NEW.employerNIP, NEW.employerName, NEW.cenaSuma, NEW.cenaBox,
            NEW.cenaAsortyment, NEW.produktZamowienie, NEW.asortymentZamowienie, NEW.szerokoscBoxa,
            NEW.wysokoscSkrzyni, NEW.wysokoscPodstawy, NEW.md5sum, NEW.securityCode, NEW.platnosc,
            NEW.cenaTransport, NEW.wojewodztwo, NEW.crc, NEW.status, NEW.czy_rabat, NEW.kod_rabatowy,
            NEW.kwota_rabatu, NEW.zrodlo, NEW.notka, NEW.data_ponowny_kontakt, NEW.data_montazu, NEW.opiekun
        );
    END IF;
END$$

DELIMITER ;




opis strony: 
1) Działanie promokodu :
Promokod jest przypisany do każdego użytkownika, który zarejestruje się na stronie. Użytkownik po zarejestrowaniu otrzymuje unikalny promokod, 
który może udostępniać innym osobom. Każda osoba,
 która skorzysta z tego promokodu na stronie zakupu boxa przy zakupie na stronie PMB, 
powoduje przyznanie bonusu dla osoby, która ten promokod posiada.

2) Obliczanie bonusu
Po dokonaniu zakupu na stronie z użyciem promokodu, system oblicza bonus dla użytkownika, który posiada ten promokod.

Za każdy zakupiony box, użytkownik otrzymuje 200 zł bonusu.
Bonus jest dodawany do pola current_bonus w tabeli ambasador_pmb_users w bazie danych.

3) Dodawanie i resetowanie bonusów
Każdy zakup jest rejestrowany w tabeli ambasador_pmb_purchases, a bonusy są przypisywane na podstawie liczby boxow zakupionych przez użytkownika. Kiedy użytkownik zbiera bonusy, 
administrator może ręcznie je przenosić do pola paid_bonus
 oraz resetować pole current_bonus, gdy bonusy zostaną wypłacone. (w Wordpres do tego powstanie nowa zakladka "bonusy"

Administrator może ręcznie zresetować bonusy (czyli przenieść je z current_bonus do paid_bonus), 
co oznacza, że bonusy zostały już wypłacone użytkownikowi.
Pole current_bonus jest zresetowane, a wszystkie przyznane bonusy zostają zapisane w paid_bonus dla historii.

