<?php
/*
Template Name: login Page
*/

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM ambasador_pmb_users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['promo_code'] = $user['promo_code'];
            header("Location: " . home_url('/dashboard'));
        } else {
            echo '<div class="error-message">Nieprawidłowe hasło! <span class="close-btn" onclick="this.parentElement.remove()">x</span></div>';
        }
    } else {
        echo '<div class="error-message">Użytkownik nie znaleziony! <span class="close-btn" onclick="this.parentElement.remove()">x</span></div>';
    }
}

$conn->close();
?>

<?php get_header(); ?>

<main>
    <div class="login-container">
        <h1>Zaloguj się do swojego konta</h1>
        <p>Wprowadź swoje dane, aby uzyskać dostęp</p>
        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Nazwa użytkownika</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Hasło</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="action-btn login-main-btn login-form--actions">Zaloguj się</button>
                <a href="<?php echo home_url('/register'); ?>" class="secondary-btn">Zarejestruj się</a>
            </div>
        </form>
        <div class="additional-links">
            <a href="<?php echo home_url('/reset-password'); ?>">Nie pamiętasz hasła?</a>
        </div>
    </div>
</main>

<?php get_footer(); ?>