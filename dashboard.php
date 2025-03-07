<?php

/**
 * Template Name: dashboard
 */


// Checking authorization

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: " . home_url('/login')); // login page redirect
    exit();
}

require 'db.php';


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching user data
$promo_code = $_SESSION['promo_code'];
$sql = "SELECT * FROM ambasador_pmb_purchases WHERE promo_code='$promo_code'";
$result = $conn->query($sql);

get_header();
?>

<main class="main-dashboard">
    <div class="dashboard-container">
        <h1>Witaj <span><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Ambasadorze'; ?></span></h1>
        <p>Sprawdź swoje osiągnięcia i odkryj nowe możliwości.</p>
        <a href="<?php echo home_url('/dashboard-panel'); ?>"><button>Sprawdź</button></a>

    </div>
</main>
<section class="bonus-section">
    <div class="bonus-section__container">
        <div class="text">
            <h3>Jak ambasadorzy mogą zdobywać bonusy za swoje działania i aktywności</h3>
            <p>
                Ambasadorzy mogą zdobywać bonusy za polecanie produktów i realizację sprzedaży za pomocą swojego indywidualnego kodu.
                Im więcej osób skorzysta z Twoich poleceń, tym większe bonusy możesz zdobyć, co motywuje do dalszej aktywności i zwiększania zysków.
            </p>
        </div>
        <div class="image">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/output.jpg" alt="Magic Box">
        </div>
    </div>
    <div class="bonus-container">
        <div class="bonus-section">
            <h2>Sprawdź swoje aktualne bonusy i nagrody</h2>
            <p class="bonus-description">Na swoim koncie możesz łatwo sprawdzić liczbę zdobytych bonusów oraz ich wartość. Zyskaj dostęp do pełnej historii nagród i ciesz się swoimi osiągnięciami.</p>
            <div class="bonus-boxes">
                <div class="bonus-box">
                    <h3>Historia Nagród</h3>
                    <p>Przeglądaj swoją historię nagród.</p>
                    <a href="<?php echo home_url('/dashboard-panel'); ?>"><button>Sprawdź</button></a>
                </div>
                <div class="bonus-box">
                    <h3>Twoje Bonusy</h3>
                    <p>Zobacz, ile bonusów zdobyłeś.</p>
                    <a href="<?php echo home_url('/dashboard-panel'); ?>"><button>Sprawdź</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="steps-container steps-container--dashboard">
        <div class="step">
            <div class="step-circle">1</div>
            <p class="step-title">Polecaj Parking Magic Box znajomym</p>
            <p class="step-text">Polecaj nasze produkty i zarabiaj za każdą sprzedaż dokonaną z Twoim kodem. Im więcej osób skorzysta, tym więcej zarobisz.</p>
        </div>
        <div class="step">
            <div class="step-circle">2</div>
            <p class="step-title"> Twórz posty w mediach społecznościowych</p>
            <p class="step-text">Dziel się swoimi doświadczeniami z naszymi produktami na Instagramie, Facebooku czy TikToku i zdobywaj bonusy za każdą sprzedaż przez Twój kod.</p>
        </div>
        <div class="step">
            <div class="step-circle">3</div>
            <p class="step-title">Promuj nasze produkty w różnych kanałach</p>
            <p class="step-text"> Korzystaj z różnych mediów, takich jak blogi, grupy, aby dotrzeć do szerszej grupy osób i zdobywać nagrody za każdą realizację sprzedaży.</p>
        </div>
    </div>
</section>
<?php
get_footer();

$conn->close();
?>