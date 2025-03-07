<?php
require 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: " . home_url('/dashboard'));
    exit();
}

ob_start(); // Output buffering
header('Content-Type: text/html; charset=UTF-8');

/**
 * Template Name: registration page
 */
get_header();

$conn->set_charset("utf8mb4");

// Generating a unique promo code
function generatePromoCode($conn)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';

    do {
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Check if the promo code exists in the database
        $stmt = $conn->prepare("SELECT id FROM ambasador_pmb_users WHERE promo_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);
    return $code;
}

// Registration form handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $phone = $_POST['telefon'];

    // handling checkboxes
    $facebook = isset($_POST['promotion']) && in_array('facebook', $_POST['promotion']) ? 1 : 0;
    $instagram = isset($_POST['promotion']) && in_array('instagram', $_POST['promotion']) ? 1 : 0;
    $recommend = isset($_POST['promotion']) && in_array('recommend', $_POST['promotion']) ? 1 : 0;
    $tiktok = isset($_POST['promotion']) && in_array('tiktok', $_POST['promotion']) ? 1 : 0;
    $youtube = isset($_POST['promotion']) && in_array('youtube', $_POST['promotion']) ? 1 : 0;
    $other = isset($_POST['promotion']) && in_array('other', $_POST['promotion']) ? 1 : 0;

    // validation
    if (!preg_match('/^[a-zA-Zа-яА-ЯąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$/u', $username)) {
        echo '<div class="error-message">Nazwa użytkownika może zawierać tylko litery i spacje! <span class="close-btn" onclick="this.parentElement.remove()">x</span></div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<div class="error-message">Nieprawidłowy adres email! <span class="close-btn" onclick="this.parentElement.remove()">x</span></div>';
    } elseif (strlen($_POST['password']) < 8) {
        echo '<div class="error-message">Hasło musi zawierać co najmniej 8 znaków! <span class="close-btn" onclick="this.parentElement.remove()">x</span></div>';
    } else {
        // validation if user exist
        $check_user = $conn->prepare("SELECT id FROM ambasador_pmb_users WHERE username = ? OR email = ?");
        $check_user->bind_param("ss", $username, $email);
        $check_user->execute();
        $check_user->store_result();

        if ($check_user->num_rows > 0) {
            echo '<div class="error-message">Użytkownik o podanym imieniu lub adresie email już istnieje! <span class="close-btn" onclick="this.parentElement.remove()">x</span></div>';
        } else {

            $promo_code = generatePromoCode($conn);

            // insert to DB
            $stmt = $conn->prepare("INSERT INTO ambasador_pmb_users (username, password, email, promo_code, phone, facebook, instagram, recommend, tiktok, youtube, other) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssiiiiii", $username, $password, $email, $promo_code, $phone, $facebook, $instagram, $recommend, $tiktok, $youtube, $other);

            if ($stmt->execute()) {
                // set session
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;

                // redirect to thanks page
                $_SESSION['registration_message'] = "Rejestracja zakończona sukcesem! Twój kod promocyjny: <span> $promo_code </span>";
                wp_redirect(home_url('/welcome/'));
                exit();
            } else {
                echo '<div class="error-message">Błąd: ' . $stmt->error . ' <span class="close-btn" onclick="this.parentElement.remove()">x</span></div>';
            }

            $stmt->close();
        }

        $check_user->close();
    }
}

$conn->close();
?>

<main>
    <div class="main-wrapper">
        <div class="text-content">
            <h1>Witaj Ambasadorze!</h1>
            <p>Rozpocznij swoją przygodę z Parking Magic Box i zyskaj wyjątkowe nagrody za swoją aktywność.</p>
        </div>
    </div>
</main>

<div class="registration-wrapper">
    <form action="" method="POST">
        <h2>Rejestracja</h2>
        <p>Zarejestruj się jako ambasador Parking Magic Box</p>
        <div class="registration-fields">
            <label>Imię*:</label>
            <input type="text" name="username" required>

            <label>Email*:</label>
            <input type="email" name="email" required>

            <label>Hasło*:</label>
            <input type="password" name="password" required>

            <label>Numer telefonu:</label>
            <input type="tel" name="telefon">
        </div>

        <div class="promotion-section">
            <h3>Gdzie planujesz promować naszą firmę?</h3>
            <div class="checkbox-group">
                <div class="first-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="promotion[]" value="facebook">
                        <span class="checkmark"></span>
                        Facebook
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="promotion[]" value="instagram">
                        <span class="checkmark"></span>
                        Instagram
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="promotion[]" value="recommend">
                        <span class="checkmark"></span>
                        Będę polecać znajomym
                    </label>
                </div>
                <div class="second-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="promotion[]" value="tiktok">
                        <span class="checkmark"></span>
                        TikTok
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="promotion[]" value="youtube">
                        <span class="checkmark"></span>
                        YouTube
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="promotion[]" value="other">
                        <span class="checkmark"></span>
                        Inne
                    </label>
                </div>
            </div>
        </div>
        <div class="registration-actions">
            <label class="checkbox-item">
                <input type="checkbox" name="regulamin" required>
                <span class="checkmark"></span>
                Akceptuję regulamin
            </label>
            <button class="action-btn register-btn" type="submit">Zarejestruj się</button>
        </div>
    </form>
</div>

<?php get_footer(); ?>

<?php
ob_end_flush();
?>