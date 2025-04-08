<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--  dynamic name -->
    <title><?php wp_title(); ?></title>

    <!-- add styles WordPress -->
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">

    <!-- meta data -->
    <meta name="keywords" content="boxy garażowe, garaże w Polsce, zarządzanie bonusami, nagrody, platforma dla ambasadorów">
    <meta name="author" content="rubaka-pl">
    <meta name="robots" content="noindex, nofollow">

    <!-- icons -->
    <link rel="icon" href="https://parkingmagicbox.com/wp-content/uploads/2023/04/fav-150x150.png" sizes="32x32">
    <link rel="icon" href="https://parkingmagicbox.com/wp-content/uploads/2023/04/fav.png" sizes="192x192">

    <!-- Open Graph / Facebook  -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Boxy Garażowe w Polsce | Najlepsze Oferty i Zarządzanie">
    <meta property="og:description" content="Oferujemy najlepsze boxy garażowe w Polsce. Zarządzaj bonusami, śledź postępy i zdobywaj nagrody z naszą platformą. Sprawdź nasze oferty już dziś!">
    <meta property="og:image" content="https://www.twojadomena.pl/images/boxy-garazowe.jpg">
    <meta property="og:url" content="https://www.twojadomena.pl/boxy-garazowe">

    <!--  WordPress scripts -->
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <header>
        <nav>
            <div class="logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img class="logo-img"
                        src=""
                        alt="logo"
                        data-logo-default="<?php echo get_template_directory_uri(); ?>/assets/icons/logo.svg"
                        data-logo-small="<?php echo get_template_directory_uri(); ?>/assets/icons/logo-small.png">
                </a>
            </div>

            <ul class="nav-links">
                <li><a href="<?php echo home_url('/o-nas'); ?>">O nas</a></li>
                <li><a href="<?php echo home_url('/kontakty'); ?>">Kontakt</a></li>
                <!-- <li><a href="#wiecej">Więcej opcji</a></li> -->
            </ul>
            <!-- Check if the user is logged in -->
            <?php if (isset($_SESSION['user_id'])) : ?>
                <a href="<?php echo esc_url(home_url('/logout')); ?>" class="logout-btn">Wyloguj się</a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/login/')); ?>" class="login-btn">Zaloguj się</a>
            <?php endif; ?>
        </nav>
    </header>