<?php

/**
 * Template Name: Wniosek o wypłatę
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: " . home_url('/login'));
    exit();
}

global $wpdb;

// Обработка формы
if (isset($_POST['submit_payout']) && wp_verify_nonce($_POST['payout_nonce'], 'payout_form_nonce')) {
    $user_id = $_SESSION['user_id'];

    $data = [
        'user_id' => $user_id,
        'imie' => sanitize_text_field($_POST['imie']),
        'nazwisko' => sanitize_text_field($_POST['nazwisko']),
        'data_urodzenia' => sanitize_text_field($_POST['data_urodzenia']),
        'numer_rachunku' => sanitize_text_field($_POST['numer_rachunku']),
        'nazwa_banku' => sanitize_text_field($_POST['nazwa_banku']),
        'ulica' => sanitize_text_field($_POST['ulica']),
        'miasto' => sanitize_text_field($_POST['miasto']),
        'kod_pocztowy' => sanitize_text_field($_POST['kod_pocztowy']),
        'urzad_skarbowy' => sanitize_text_field($_POST['urzad_skarbowy']),
        'email' => sanitize_email($_POST['email']),
        'telefon' => sanitize_text_field($_POST['telefon']),
        'pesel' => sanitize_text_field($_POST['pesel']),
        'data_zgloszenia' => current_time('mysql')
    ];

    $result = $wpdb->query($wpdb->prepare(
        "INSERT INTO ambasador_pmb_users_data
        (user_id, imie, nazwisko, data_urodzenia, numer_rachunku, nazwa_banku, ulica, miasto, kod_pocztowy, urzad_skarbowy, email, telefon, pesel, data_zgloszenia)
        VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        $data['user_id'],
        $data['imie'],
        $data['nazwisko'],
        $data['data_urodzenia'],
        $data['numer_rachunku'],
        $data['nazwa_banku'],
        $data['ulica'],
        $data['miasto'],
        $data['kod_pocztowy'],
        $data['urzad_skarbowy'],
        $data['email'],
        $data['telefon'],
        $data['pesel'],
        $data['data_zgloszenia']
    ));

    if ($result !== false) {
        $_SESSION['form_success'] = true;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        $error_message = 'Błąd zapisu: ' . $wpdb->last_error;
    }
}

get_header();

if (isset($_SESSION['form_success'])) {
    echo '
    <div id="fullscreen-message" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(10px);
    ">
        <div style="text-align: center; padding: 30px; max-width: 600px;">
            <h2 style="color: #4CAF50; font-size: 2.5em; margin-bottom: 20px;">✓ Sukces!</h2>
            <p style="font-size: 1.2em; margin-bottom: 30px;">Dane zostały pomyślnie zapisane!</p>
            <a href="' . home_url('/dashboard-panel') . '" 
                style="
                    padding: 12px 30px;
                    font-size: 1.1em;
                    background: #4CAF50;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    text-decoration: none;
                    display: inline-block;
                ">
                Zamknij i wróć do panelu
            </a>
        </div>
    </div>';
    unset($_SESSION['form_success']);
}
?>


<style>
    .wniosek-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 30px;
        background: #1a1a1a;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    }

    .wniosek-title {
        color: #ffd700;
        text-align: center;
        margin-bottom: 30px;
        font-size: 2em;
    }

    .payout-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .input-group.full-width {
        grid-column: span 2;
    }

    label {
        display: block;
        color: #fff;
        margin-bottom: 8px;
        font-size: 0.9em;
    }

    input,
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ffd700;
        border-radius: 4px;
        background: #2a2a2a;
        color: #fff;
        font-size: 1em;
    }

    .submit-btn {
        grid-column: span 2;
        background: linear-gradient(45deg, #ffd700, #ffc966ba);
        color: #000;
        border: none;
        padding: 15px;
        font-size: 1.1em;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 20px;
    }

    .success-msg {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        grid-column: span 2;
    }

    .error-msg {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        grid-column: span 2;
    }

    @media (max-width: 768px) {
        .payout-form {
            grid-template-columns: 1fr;
        }

        .input-group.full-width {
            grid-column: span 1;
        }

        .submit-btn {
            grid-column: span 1;
        }
    }
</style>

<div class="wniosek-container">
    <h1 class="wniosek-title">Wniosek o wypłatę bonusów</h1>

    <?php if (isset($success_message)): ?>
        <div class="success-msg"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="error-msg"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" class="payout-form">
        <div class="input-group">
            <label>Imię</label>
            <input type="text" name="imie" required>
        </div>

        <div class="input-group">
            <label>Nazwisko</label>
            <input type="text" name="nazwisko" required>
        </div>

        <div class="input-group">
            <label>Adres E-mail</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Numer telefonu</label>
            <input type="tel" name="telefon" required pattern="[+]{0,1}[0-9]{9,12}">
        </div>

        <div class="input-group">
            <label>Data urodzenia</label>
            <input type="date" name="data_urodzenia" required>
        </div>

        <div class="input-group">
            <label>PESEL</label>
            <input type="text" name="pesel" required pattern="\d{11}">
        </div>

        <div class="input-group full-width">
            <label>Numer rachunku bankowego</label>
            <input type="text" name="numer_rachunku" required placeholder="PL00 0000 0000 0000 0000 0000 0000">
        </div>

        <div class="input-group">
            <label>Nazwa banku</label>
            <input type="text" name="nazwa_banku" required>
        </div>

        <div class="input-group">
            <label>Urząd Skarbowy</label>
            <input type="text" name="urzad_skarbowy" required>
        </div>

        <div class="input-group full-width">
            <label>Ulica i numer</label>
            <input type="text" name="ulica" required>
        </div>

        <div class="input-group">
            <label>Miasto</label>
            <input type="text" name="miasto" required>
        </div>

        <div class="input-group">
            <label>Kod pocztowy</label>
            <input type="text" name="kod_pocztowy" required pattern="\d{2}-\d{3}" placeholder="00-000">
        </div>

        <?php wp_nonce_field('payout_form_nonce', 'payout_nonce'); ?>
        <button type="submit" name="submit_payout" class="submit-btn">Wyślij wniosek</button>
    </form>
</div>

<?php
function validate_phone($phone)
{
    $clean = preg_replace('/[^0-9+]/', '', $phone);
    if (!preg_match('/^(\+48)?\d{9}$/', $clean)) {
        throw new Exception('Nieprawidłowy numer telefonu');
    }
    return $clean;
}

function validate_pesel($pesel)
{
    if (!preg_match('/^\d{11}$/', $pesel)) {
        throw new Exception('Nieprawidłowy numer PESEL');
    }
    return $pesel;
}

function validate_postcode($postcode)
{
    $normalized = preg_replace('/^(\d{2})(\d{3})$/', '$1-$2', $postcode);
    if (!preg_match('/^\d{2}-\d{3}$/', $normalized)) {
        throw new Exception('Nieprawidłowy format kodu pocztowego (00-000)');
    }
    return $normalized;
}

function validate_iban($iban)
{
    $clean = strtoupper(str_replace(' ', '', $iban));
    if (!preg_match('/^PL\d{24}$/', $clean)) {
        throw new Exception('Nieprawidłowy numer rachunku (wymagany format PL + 24 cyfry)');
    }
    return $clean;
}
get_footer();
?>