<?php
/*
Template Name: about-us
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

get_header();
?>

<main>
    <div class="main-wrapper wrapper-about-us">
        <div>
            <h1><a href="<?php echo esc_url(home_url('/')); ?>" class="pmb-link">PMB</a> » <span class="yellow-text">O nas</span></h1>
            <p class="quote"><em>"Mniej znaczy więcej"</em></p>
            <p>Spełniamy marzenia naszych Klientów o dodatkowej przestrzeni do przechowywania, oferując innowacyjne rozwiązania, które doskonale łączą funkcjonalność z nowoczesnym designem.</p>
        </div>
    </div>
</main>

<section class="about-us-section">
    <div class="about-us-content">
        <div class="history-section">
            <div class="text-content--about">
                <h2>Nasza <span class="yellow-text">historia</span></h2>
                <p>Wszystko zaczęło się od potrzeby znalezienia praktycznego rozwiązania problemu przechowywania.</p>
                <p>2016 roku stworzyliśmy pierwszy box garażowy, który stał się odpowiedzią na rosnące zapotrzebowanie na dodatkową przestrzeń w mieszkaniach i domach. Dziś Parking Magic Box to synonim innowacji, jakości i niezawodności.</p>
            </div>
        </div>

        <div class="business-section">
            <div class="text-content--about">
                <h2>Wspólny <span class="yellow-text">biznes</span></h2>
                <p>Razem w życiu, razem w biznesie - Agnieszka i Radek miłośnicy czworonogów i pasjonaci sportów rowerowych. Od 2016 roku wspólnie tworzymy prężnie rozwijającą się firmę i markę Parking Magic Box, która nadaje nowy wymiar dla bezpiecznego przechowywania różnych sprzętów i przedmiotów.</p>
            </div>
        </div>

        <div class="challenges-section">
            <div class="text-content--about">
                <h2>Wyzwania na <span class="yellow-text">starcie</span></h2>
                <p>Na początku działalności boxy parkingowe okazały się zagadkową nowością zarówno dla deweloperów, firm administrujących budynki wielorodzinne jak również mieszkańców osiedli.</p>
                <p>Pomimo początkowych wyzwań, boxy parkingowe szybko zdobyły zaufanie rynku, a ich produkcja i instalacja zaczęły podlegać rygorystycznym normom prawnym i technicznym. Produkty, które trafiły na rynek, uzyskały wszystkie niezbędne certyfikaty, co stanowi gwarancję ich wysokiej jakości oraz zgodności z obowiązującymi przepisami.</p>
            </div>
        </div>

        <div class="team-section">

            <div class="text-content--about">
                <h2>Nasz <span class="yellow-text">zespół</span></h2>
                <p>Nasza firma to przede wszystkim Ludzie przez duże "L" - stanowimy zespół pasjonatów zafiksowanych w swojej branży. Każdego dnia nasi niezastąpieni pracownicy zaczynając od działu projektowego poprzez produkcję, aż po zespół montażystów wkładają w pracę wiedzę i doświadczenie, aby w efekcie końcowym Klienci otrzymali produkt najwyższej jakości.</p>
            </div>
        </div>

        <div class="quality-section">
            <div class="text-content--about">
                <h2>Jakość i <span class="yellow-text">satysfakcja</span></h2>
                <p>Wartość, na którą równie mocno postawiliśmy to budowanie relacji z Klientami. Dbamy o naszych Klientów od etapu pomocy w wyborze konkretnego boxa garażowego aż po wrażenia i recenzje z użytkowania boxa już po dokonanym zakupie. To dla nas szczególnie ważne, ponieważ wiele ulepszeń które wdrażamy pochodzi z sugestii naszych Klientów użytkujących Magic Box-y. Wspólnie tworzymy ten biznes!</p>
            </div>
        </div>

        <div class="experience-section">
            <div class="text-content--about">
                <h2><span class="yellow-text">Doświadczenie</span></h2>
                <p>Korzenie Parking Magic Box sięgają roku 2016. Imponujący, rodzinny park produkcyjny o powierzchni 2200 m2 z najnowocześniejszym wyposażeniem maszynowym i zespołem specjalistów umożliwił nam mocny start, aby wizję boxu od projektu/szkicu na kartce papieru przenieść do rzeczywistości.</p>
            </div>
            <div class="image-content">
                <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/img/relacja-z-wydarzenia-polski-rynem-mieszkaniowy-1050x500.jpg" alt="Doświadczenie">
            </div>
        </div>
    </div>
</section>

<section class="contact-info">
    <div class="contact-item contact-email">
        <a href="mailto:kontakt@parkingmagicbox.com" class="dh-email">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/mail-icon.svg" alt="Email" class="contact-icon">
            <span>Email</span>
            kontakt@parkingmagicbox.com
        </a>
    </div>

    <div class="contact-item contact-phone">
        <a href="tel:504505718" class="contact-link">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/telephone.svg" alt="Phone" class="contact-icon">
            <span>Telefon</span>
            +48 504 505 718
        </a>
    </div>
    <div class="contact-item contact-map">
        <a href="https://www.google.com/maps?q=BARTYCKA+26+Warszawa" target="_blank">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/map.svg" alt="Map" class="contact-icon">
            <span>Punkt Pokazowy</span>
            Warszawa, ul. Bartycka 26
            <a style="margin-top: 10px; text-decoration: underline;" href="https://www.google.com/maps/place/Parking+Magic+Box+-+producent+szaf+gara%C5%BCowych/@52.2148637,21.0548037,15z/data=!4m2!3m1!1s0x0:0x51411929e6e3df61?sa=X&amp;ved=2ahUKEwj9g4mcmLGAAxXAFhAIHW4lDEAQ_BJ6BAhOEAA&amp;ved=2ahUKEwj9g4mcmLGAAxXAFhAIHW4lDEAQ_BJ6BAhqEAk" target="_blank" rel="nofollow">Zobacz na mapie</a>
        </a>
    </div>
</section>

<?php get_footer(); ?>