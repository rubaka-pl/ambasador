<?php
/*
Template Name: contacts
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

get_header();
?>

<div id="inner-content" class="home-content">
    <section class="contact-header">
        <div class="container">
            <div class="header-content">
                <div class="breadcrumbs">
                    <span>
                        <span><a href="<?php echo home_url('/'); ?>">PMB</a></span> »
                        <span class="breadcrumb-last" aria-current="page">Skontaktuj się z nami</span>
                    </span>
                </div>
                <h1 class="main-title"><strong>Skontaktuj się</strong> z&nbsp;nami</h1>
            </div>
        </div>
    </section>

    <section class="contact-main">
        <div class="container">
            <div class="contact-row">
                <div class="contact-info contact-info-column">
                    <div class="contact-card sales-department">
                        <div class="contact-card--item">
                            <div class="contact-card--title">
                                <h3>Dział Handlowy</h3>
                            </div>
                            <a href="tel:504505718" class="contact-phone">+48 504 505 718</a>
                            <a href="mailto:kontakt@parkingmagicbox.com" class="contact-email">kontakt@parkingmagicbox.com</a>
                            <a href="mailto:biuro@parkingmagicbox.com" class="contact-email">biuro@parkingmagicbox.com</a>

                        </div>

                        <div class="contact-card--item">
                            <div class="contact-card--title">
                                <h3>Status zamówienia:</h3>
                            </div>
                            <a href="tel:882819858" class="contact-phone">+48 882 819 858</a>
                            <a href="mailto:serwis@parkingmagicbox.com" class="contact-email">serwis@parkingmagicbox.com</a>
                        </div>
                    </div>

                    <div class="contact-card--wrapper">
                        <div class="contact-card invoice-details">
                            <h2>Dane do faktury</h2>
                            <p>
                                <strong>PMB Sp. z o.o.</strong><br>
                                ul. Armii Krajowej 8, 17-300 Siemiatycze<br>
                                NIP: 5441541443<br>
                                REGON: 385900060<br>
                                KRS: 0000837230
                            </p>
                        </div>

                        <div class="contact-card bank-details">
                            <h2>Dane bankowe</h2>
                            <p>
                                Santander Bank Polska S.A.<br>
                                <strong>69 1090 2590 0000 0001 4468 1433</strong>
                            </p>
                        </div>
                        <div class="contact-card location-card">
                            <h2 class="section-title"><span>Samoobsługowe</span> punkty pokazowe</h2>
                            <strong>Warszawa</strong>
                            <div class="location-address">BARTYCKA 26 <br>ul. Bartycka 26 | Pawilon 51</div>
                            <a style="color: var(--primary-yellow);" href="https://www.google.com/maps/place/Parking+Magic+Box+-+producent+szaf+gara%C5%BCowych/@52.2148637,21.0548037,15z/data=!4m2!3m1!1s0x0:0x51411929e6e3df61?sa=X&amp;ved=2ahUKEwj9g4mcmLGAAxXAFhAIHW4lDEAQ_BJ6BAhOEAA&amp;ved=2ahUKEwj9g4mcmLGAAxXAFhAIHW4lDEAQ_BJ6BAhqEAk" target="_blank" rel="nofollow">Zobacz na mapie</a>
                        </div>
                        <div id="modal" class="modal">
                            <span class="close">&times;</span>
                            <img class="modal-content" id="modal-img">
                            <div id="caption"></div>
                        </div>
                    </div>


                </div>

                <div class="contact-form">
                    <div id="contactModal" class="modal modal--visible">
                        <div class="modal-content">
                            <h2 class="section-title">Szybki <span>kontakt</span></h2>
                            <form id="contactForm" action="https://formspree.io/f/mqaewpdj" method="POST" onsubmit="return validateForm()">
                                <input type="email" id="email" name="email" placeholder="Twój e-mail">
                                <input type="tel" id="phone" name="phone" placeholder="Twój numer telefonu">
                                <textarea id="message" name="message" placeholder="Treść wiadomości..." maxlength="500" required></textarea>
                                <div class="counter"><span id="charCount">0</span>/500</div>

                                <div class="checkbox-group">
                                    <div class="first-group">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="agreeDataProcessing" required>
                                            <span class="checkmark"></span>
                                            Wyrażam zgodę na przetwarzanie przez PMB Sp. z o.o. z siedzibą przy ul. Armii Krajowej nr 8, 17-300 Siemiatycze, NIP 5441541443, REGON 385900060, e-mail: kontakt@parkingmagicbox.com, dalej jako “Administrator”, moich danych osobowych - adresu poczty elektronicznej, przekazanych za pośrednictwem formularza kontaktowego, w celu i zakresie koniecznym do przedstawienia oferty marketingowej produktów i usług własnych Administratora.
                                        </label>

                                        <label class="checkbox-item">
                                            <input type="checkbox" name="agreeTelecom" required>
                                            <span class="checkmark"></span>
                                            Wyrażam zgodę na używanie przez PMB Sp. z o.o. z siedzibą przy ul. Armii Krajowej nr 8, 17-300 Siemiatycze, NIP 5441541443, REGON 385900060, e-mail: kontakt@parkingmagicbox.com, dalej jako “Administrator”, telekomunikacyjnych urządzeń końcowych, których jestem użytkownikiem, w celu prowadzenia marketingu bezpośredniego za pośrednictwem połączeń telefonicznych oraz wysyłania wiadomości sms/mms zgodnie z art. 172 ustawy z dnia 16 lipca 2004 r. Prawo telekomunikacyjne.
                                        </label>
                                    </div>
                                </div>
                                <p id="error-message" class="error-message error-message--modal "></p>
                                <button class="modal-btn" type="submit">Wyślij</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>