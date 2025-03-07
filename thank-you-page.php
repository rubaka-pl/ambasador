<?php

/**
 * Template Name: thank you page
 */
// Starting the session if it has not been started yet

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: " . home_url('/login'));
    exit();
}
get_header();
?>



<main>
    <div class="thank-you-page">
        <h1>Dziękujemy za rejestrację!</h1>
        <?php
        if (isset($_SESSION['registration_message'])) {
            echo "<p>" . $_SESSION['registration_message'] . "</p>";
            unset($_SESSION['registration_message']);
        }
        ?>
        <div class="action-buttons">
            <a href="<?php echo home_url('/'); ?>" class="action-btn">Strona główna</a>
            <a href="<?php echo esc_url(home_url('/login/')); ?>" class="action-btn login-main-btn">Zaloguj się</a>
        </div>
    </div>
</main>


<?php get_footer(); ?>