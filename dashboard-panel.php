<?php

/**
 * Template Name: dashboard panel
 */


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: " . home_url('/login'));
    exit();
}

require 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieving user data
$promo_code = $_SESSION['promo_code'];
$username = $_SESSION['username'];

// number of sold boxes and the total bonus amount
$stmt = $conn->prepare("SELECT 
            SUM(p.quantity) as total_sold, 
            MAX(p.purchase_date) as last_purchase_date,
            u.paid_bonus,
            u.current_bonus
        FROM ambasador_pmb_purchases p
        JOIN ambasador_pmb_users u ON p.promo_code = u.promo_code AND p.username = u.username
        WHERE p.promo_code = ? 
        AND p.username = ?");
$stmt->bind_param("ss", $promo_code, $username);
$stmt->execute();
$result = $stmt->get_result();

$last_purchase_date = "Brak danych";

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_sold = $row['total_sold'] ?? 0;
    $total_bonus = $row['current_bonus'] ?? 0;
    $last_purchase_date = $row['last_purchase_date'] ?? "Brak danych";
}

$conn->query("SET SESSION group_concat_max_len = 10000");

// Запрос для получения истории покупок
$history_sql = "SELECT 
                  order_id,
                  GROUP_CONCAT(box_id SEPARATOR ', ') as models,
                  SUM(quantity) as quantity,
                  MAX(purchase_date) as purchase_date,
                  (SUM(quantity) * 200) as bonus 
                FROM ambasador_pmb_purchases 
                WHERE promo_code='$promo_code' 
                  AND username='$username' 
                GROUP BY order_id 
                ORDER BY purchase_date DESC";
$history_result = $conn->query($history_sql);

get_header();
?>
<style>
    .table_component {
        overflow: auto;
        width: 100%;
    }

    .table_component table {
        border: 1px solid var(--primary-yellow);
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .table_component th {
        border-bottom: 1px solid var(--primary-yellow);
        background-color: transparent;
        color: var(--primary-yellow);
        padding: 10px;
    }

    .table_component td {
        border: 1px solid var(--primary-yellow);
        background-color: transparent;
        color: #fff;
        padding: 10px;
    }

    .table_component caption {
        caption-side: top;
        text-align: left;
        margin-bottom: 10px;
    }

    /* Стили для модального окна */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #1a1a1a;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 8px;
    }

    .modal-content h3 {
        margin-bottom: 20px;
    }

    .modal-content p {
        margin: 20px auto;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: black;
    }

    .panel-box {
        width: 600px;
        min-height: 215px;
        border: 1px solid var(--primary-yellow);
        text-align: center;
        padding: 8px;
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
    }

    .panel-container {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 30px;
        margin: 150px auto;
        justify-content: center;
    }

    .code {
        font-size: 4rem;
    }


    .payout-button {
        margin-top: 5px;
        background: linear-gradient(45deg, #ffd700, #ffc966ba);
        border-radius: 10px;
    }

    .copy-btn {
        display: inline-block;
        margin-left: 10px;
        font-size: 16px;
        color: var(--primary-yellow);
        cursor: pointer;
        text-decoration: underline;
    }
</style>
<section class="dashboard-panel">
    <div class="dashboard-container">
        <h1>Witaj <span><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Ambasadorze'; ?></span></h1>
        <p>Sprawdź swoje osiągnięcia i odkryj nowe możliwości.</p>
    </div>

    <div class="dashboard-panel">
        <div class="panel-container">
            <div class="panel-box">
                <h3>Sprawdź swoje dostępne bonusy i nagrody</h3>
                <p>Zbieraj bonusy za sprzedaż boksów garażowych. Im więcej sprzedasz, tym większe nagrody możesz zdobyć.</p>
            </div>
            <div class="panel-box">
                <h3>Twój indywidualny kod:</h3>
                <p class="yellow-text code"><?php echo htmlspecialchars($promo_code); ?></p>
                <span class="copy-btn">Kopiuj</span>
            </div>
            <div class="panel-box">
                <h3>Twoje osiągnięcia</h3>
                <p>Ilość sprzedanych boksów z kodem:
                    <?php echo $total_sold; ?>
                </p>
            </div>
            <div class="panel-box">
                <h3>Dostępne bonusy</h3>
                <p>Kwota bonusów do wypłaty: <?php echo $total_bonus; ?> zł</p>
                <p>Ostatnia sprzedaż: <?php echo htmlspecialchars($last_purchase_date); ?></p>
                <?php if ($total_bonus > 0): ?>
                    <button onclick="showContactModal()" class="payout-button action-btn">
                        Złóż wniosek o wypłatę
                    </button>

                    <!-- Модальное окно с контактами -->
                    <div id="contactModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal()">&times;</span>
                            <h3>Skontaktuj się z naszym specjalistą</h3>
                            <p>Email: biuro@parkingmagicbox.com</p>
                            <p>Telefon: +48 534 831 358</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="table_component" role="region" tabindex="0">
            <table style="overflow-x:auto;">
                <caption style="font-size:48px; margin: 40px auto">Historia sprzedaży</caption>
                <thead>
                    <tr>
                        <th>Model</th>
                        <th>Ilość</th>
                        <th>Data</th>
                        <th>Bonusy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($history_result->num_rows > 0) {
                        while ($row = $history_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['models']) . "</td>"; // Список моделей
                            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>"; // Количество в заказе
                            echo "<td>" . htmlspecialchars($row['purchase_date']) . "</td>";
                            echo "<td>" . ($row['quantity'] * 200) . " zł</td>"; // Общий бонус
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Brak danych o sprzedaży.</td></tr>";
                    }
                    ?>
                </tbody>


            </table>
        </div>
    </div>
    <div class="steps-container steps-container--dashboard">
        <div class="step">
            <div class="step-circle">1</div>
            <p class="step-title">Skontaktuj się z naszym pracownikiem</p>
            <p class="step-text">Kliknij przycisk powyżej, aby nawiązać kontakt z naszym specjalistą, który przeprowadzi Cię przez cały proces wypłaty.</p>
        </div>
        <div class="step">
            <div class="step-circle">2</div>
            <p class="step-title">Odbierz dokumenty i potwierdź
                dane</p>
            <p class="step-text">Po skontaktowaniu się z nami otrzymasz dokumenty z szczegółowym rozliczeniem Twoich bonusów. Podpisz je i prześlij wymagane dane do wypłaty.</p>
        </div>
        <div class="step">
            <div class="step-circle">3</div>
            <p class="step-title">Odbierz swoje wynagrodzenie i kontynuuj zarabianie</p>
            <p class="step-text">Po zakończeniu formalności, środki zostaną przelane na Twoje konto. Ciesz się swoimi zarobkami i nie przestawaj polecać naszych produktów!</p>
        </div>
    </div>
</section>
<script>
    function showContactModal() {
        document.getElementById('contactModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('contactModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('contactModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
<?php
get_footer();

$conn->close();
?>