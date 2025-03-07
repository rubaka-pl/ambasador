1) Formularz kontaktowy działa na podstawie szablonu i 
aktualnie jest skonfigurowany za pomocą formspree.io. 
zmieniс ustawienia na prawidłową konfigurację SMTP (contacts.php , index.php)

2) Database Setup

ambasador_pmb_users Table:


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


ambasador_pmb_purchases Table:

CREATE TABLE ambasador_pmb_purchases (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    promo_code VARCHAR(10) COLLATE utf8mb4_general_ci NOT NULL,
    box_id VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    username VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    purchase_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    quantity INT(11) NOT NULL DEFAULT 1
);




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

    -- Find the user by promo_code and username
    SELECT id INTO user_id
    FROM ambasador_pmb_users
    WHERE promo_code = NEW.promo_code AND username = NEW.username;

    -- Calculate the bonus (200 for each item purchased)
    SET bonus = NEW.quantity * 200;

    -- Update the current_bonus for the user
    UPDATE ambasador_pmb_users
    SET current_bonus = current_bonus + bonus
    WHERE id = user_id;
END;

//
DELIMITER ;




4) zmienić dane w pliku db.php


opis strony: 
1) Działanie promokodu :
Promokod jest przypisany do każdego użytkownika, który zarejestruje się na stronie. Użytkownik po zarejestrowaniu otrzymuje unikalny promokod, 
który może udostępniać innym osobom.

2) Obliczanie bonusu
Po dokonaniu zakupu na stronie z użyciem promokodu, system oblicza bonus dla użytkownika, który posiada ten promokod.
Bonus jest dodawany do pola current_bonus w tabeli ambasador_pmb_users w bazie danych.

3) Dodawanie i resetowanie bonusów
Każdy zakup jest rejestrowany w tabeli ambasador_pmb_purchases, a bonusy są przypisywane na podstawie liczby boxow zakupionych przez użytkownika. Kiedy użytkownik zbiera bonusy, 
administrator może ręcznie je przenosić do pola paid_bonus
 oraz resetować pole current_bonus, gdy bonusy zostaną wypłacone. (w Wordpres do tego powstanie nowa zakladka "bonusy"
Administrator może ręcznie zresetować bonusy (czyli przenieść je z current_bonus do paid_bonus), 
co oznacza, że bonusy zostały już wypłacone użytkownikowi.
Pole current_bonus jest zresetowane, a wszystkie przyznane bonusy zostają zapisane w paid_bonus dla historii.

