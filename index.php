<?php error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_SESSION['user_id'])) {
    header("Location: " . home_url('/dashboard'));
    exit();
}
get_header();

if (isset($_SESSION['user_id'])) {
    // If the user is logged in
    echo '<main>
        <div class="main-wrapper">
            <div class="text-content">
                <h1>Witaj z powrotem, <span class="yellow-text">' . $_SESSION['username'] . '!</span></h1>
                <p>Przejdź do swojego panelu, aby zobaczyć najnowsze aktualizacje.</p>
                <div class="action-buttons">
                    <a href="' . esc_url(home_url('/dashboard')) . '">
                        <button class="action-btn">Przejdź do dashboardu</button>
                        <button class="action-btn">Wyloguj sie</button>
                    </a>
                </div>
            </div>
        </div>
    </main>';
} else {
    // If the user is not logged in
    echo '<main>
        <div class="main-wrapper">
            <div class="text-content">
                <h1>Witaj w programie <span class="yellow-text">Parking Magic Box!</span></h1>
                <p>Dołącz do naszej społeczności ambasadorów i odkryj korzyści płynące z promocji naszych innowacyjnych
                    szaf garażowych. Razem możemy osiągnąć więcej!</p>
                <div class="action-buttons">
                    <a href="' . esc_url(home_url('/registration/')) . '">
                        <button class="action-btn register-btn">Zarejestruj się</button>
                    </a>
                    <a href="' . esc_url(home_url('/login/')) . '">
                        <button class="action-btn login-main-btn">Zaloguj się</button>
                    </a>
                </div>
            </div>
        </div>
    </main>';
}
?>


<section class="why-join-section">
    <div class="why-join-content">
        <h3 class="why-join-title">Dlaczego warto być ambasadorem <span class="yellow-text">Parking Magic
                Box?</span></h3>
        <p class="why-join-text">Jako aktywny ambasador Parking Magic Box, zyskujesz dostęp do ekskluzywnych bonusów
            oraz nagród. Twoje
            zaangażowanie przekłada się na realne korzyści, które możesz wykorzystać w codziennym życiu.
        </p>
        <ul class="benefits-list">
            <li>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-pig.svg" alt="icon-pig">
                Zbieraj bonusy za swoje zaangażowanie
            </li>
            <li>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-lock.svg" alt="icon-lock">
                Dostęp do ekskluzywnych nagród i promocji
            </li>
            <li>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-graph.svg" alt="icon-graph">
                Możliwość śledzenia postępów i osiągnięć
            </li>
        </ul>
    </div>
    <figure class="why-join-section-image">
        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/img/bicycle-in-box.png" alt="Rower w boxie garażowym">
    </figure>
</section>

<section class="features">
    <div class="features-content">
        <h3 class="features-title">Odkryj kluczowe funkcje dedykowane ambasadorom <span class="yellow-text">Parking
                Magic
                Box.</span>
        </h3>

        <p class="features-text">Nasza platforma oferuje łatwy sposób zarządzania bonusami oraz nagrodami.
            Ambasadorzy
            mogą śledzić swoje
            postępy, zdobywać nagrody i cieszyć się prostym dostępem do wszystkich informacji.
        </p>
    </div>
    <div class="features-cards">

        <div class="features-card-item">
            <div class="features-card-content">
                <p class="features-card-title">Zarządzaj swoimi bonusami i nagrodami.</p>
                <div class="yellow-line"></div>
                <p class="features-card-footer">Śledź przyznane bonusy i historię nagród w prosty sposób.</p>
            </div>
        </div>

        <div class="features-card-item">
            <div class="features-card-content">
                <p class="features-card-title">Intuicyjny panel użytkownika dla ambasadorów.</p>
                <div class="yellow-line"></div>
                <p class="features-card-footer"> Łatwy w obsłudze interfejs pozwala na szybkie sprawdzenie postępów.
                </p>
            </div>
        </div>

        <div class="features-card-item">
            <div class="features-card-content">
                <p class="features-card-title">Wypłacaj nagrody i zarabiaj więcej.</p>
                <div class="yellow-line"></div>
                <p class="features-card-footer">Zgromadzone bonusy możesz wymienić na realne wynagrodzenie.</p>
            </div>
        </div>
    </div>
</section>

<section class="how-it-works">
    <div class="hot-it-works-desc">
        <h2>Jak to działa?</h2>
        <p>Rejestracja w naszym systemie jest szybka i intuicyjna. Po założeniu konta, ambasadorzy mogą łatwo
            logować
            się i zarządzać swoimi bonusami.</p>
    </div>



    <div class="steps-container">
        <div class="step">
            <div class="step-circle">1</div>
            <p class="step-title">Zarejestruj się</p>
            <p class="step-text">Zarejestruj się na naszej platformie, podając podstawowe informacje.</p>
        </div>
        <div class="step">
            <div class="step-circle">2</div>
            <p class="step-title">Zdobądź bonusy</p>
            <p class="step-text">Otrzymuj bonusy za promowanie naszych Boxów Garażowych.</p>
        </div>
        <div class="step">
            <div class="step-circle">3</div>
            <p class="step-title">Wypłać swoje środki</p>
            <p class="step-text">Bonusy możesz zamienić na realne wynagrodzenie.</p>
        </div>
    </div>

</section>

<section class="join-community">
    <div class="join-community-content">
        <h2 class="join-community-title">Dołącz do naszej społeczności!</h2>
        <p class="join-community-text">
            Zarejestruj się już dziś i odkryj korzyści bycia ambasadorem Parking Magic Box!
        </p>
        <div class="action-buttons">
            <a href="/page-register.html"><button class="action-btn register-btn">Zarejestruj się</button></a>
            <button class="action-btn login-main-btn">Zaloguj się</button>
        </div>
    </div>

    <figure class="join-community-image">
        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/img/box-join-community.png" alt="box garażowy">
    </figure>
</section>

<section class="faq">
    <h2 class="faq-title">Najczęściej zadawane pytania</h2>
    <p class="faq-desc">Oto odpowiedzi na najczęściej zadawane pytania dotyczące korzystania z panelu
        administracyjnego.</p>

    <div class="faq-item">
        <button class="faq-question">Jak zarejestrować konto? <span class="arrow">&#9662;</span></button>
        <div class="faq-answer">Aby zarejestrować konto, wypełnij formularz rejestracyjny podając swoje dane. Po
            zatwierdzeniu, otrzymasz e-mail z potwierdzeniem. Następnie możesz zalogować się do swojego konta.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">Jak zresetować hasło? <span class="arrow">&#9662;</span></button>
        <div class="faq-answer">Aby zresetować hasło, kliknij link 'Zapomniałeś hasła?' na stronie logowania.
            Wprowadź swój adres e-mail, a otrzymasz instrukcje na swoją skrzynkę. Postępuj zgodnie z tymi
            wskazówkami, aby ustawić nowe hasło.</div>
    </div>

    <div class="faq-item">
        <button class="faq-question">Jak sprawdzić bonusy? <span class="arrow">&#9662;</span></button>
        <div class="faq-answer">W panelu użytkownika znajdziesz sekcję 'Moje bonusy', gdzie możesz sprawdzić
            aktualny stan swoich punktów. Możesz również zobaczyć historię przyznanych nagród. To miejsce daje pełen
            wgląd w Twoje osiągnięcia.</div>
    </div>
    <div class="faq-footer">
        <h4 class="faq-subtitle">Masz jeszcze pytania?</h4>
        <p class="faq-text">Nie wahaj się z nami skontaktować!</p>
        <button onclick="openContactModal()" class="faq-contact-btn">Kontakt</button>
    </div>

    <!-- MODAL WINDOW. start -->
    <div id="contactModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeContactModal()">&times;</span>
            <h2>Napisz do nas</h2>
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
    <!-- MODAL WINDOW. End -->

    <div class="contact-info">
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
    </div>
</section>

<?php get_footer(); ?>