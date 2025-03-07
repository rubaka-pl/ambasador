<?php
/*
Template Name: forgot password page
*/

require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Checking if a user with this email exists
    $sql = "SELECT * FROM ambasador_pmb_users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $password = bin2hex(random_bytes(8)); // Generating a new password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // update in sql
        $update_sql = "UPDATE ambasador_pmb_users SET password='$hashed_password' WHERE email='$email'";
        if ($conn->query($update_sql) === TRUE) {
            // send mail with new pass
            $to = $email;
            $subject = "Nowe hasło do Twojego konta";
            $message = "Twoje nowe hasło: $password\nZaloguj się i zmień hasło w ustawieniach konta.";
            $headers = "From: no-reply@example.com";

            if (mail($to, $subject, $message, $headers)) {
                $message = '<div class="success-message">Nowe hasło zostało wysłane na Twój adres email.</div>';
            } else {
                $message = '<div class="error-message">Wystąpił błąd podczas wysyłania emaila.</div>';
            }
        } else {
            $message = '<div class="error-message">Wystąpił błąd podczas resetowania hasła.</div>';
        }
    } else {
        $message = '<div class="error-message">Użytkownik z tym adresem email nie istnieje.</div>';
    }
}

$conn->close();
?>

<?php get_header(); ?>

<main>
    <div class="forgot-password-container container-form">
        <h1>Zapomniałeś hasła?</h1>
        <p>Wprowadź swój adres email, aby zresetować hasło.</p>
        <?php echo $message; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="action-btn passwords-btn">Wyślij nowe hasło</button>
            </div>
        </form>
        <div class="additional-links">
            <a href="<?php echo home_url('/login'); ?>">Wróć do logowania</a>
        </div>
    </div>
</main>

<?php get_footer(); ?>