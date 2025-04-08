<?php
// Session start
function start_session()
{
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'start_session', 1);

// Logout page registration
function register_logout_page()
{
    add_rewrite_rule('^logout/?', 'index.php?is_logout_page=1', 'top');
}
add_action('init', 'register_logout_page');

// Adding a query variable for the logout page
function add_logout_query_var($vars)
{
    $vars[] = 'is_logout_page';
    return $vars;
}
add_filter('query_vars', 'add_logout_query_var');

// Setting a template for the logout page
function logout_page_template($template)
{
    if (get_query_var('is_logout_page')) {
        return get_template_directory() . '/logout.php';
    }
    return $template;
}
add_filter('template_include', 'logout_page_template');

// Logic for logout
function handle_logout()
{
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        // Terminate the session and clear the data
        session_unset();
        session_destroy();

        // Redirect to the homepage or login page
        wp_redirect(home_url('/'));
        exit;
    }
}
add_action('init', 'handle_logout');

// Connecting styles and scripts
function my_theme_enqueue_styles()
{
    //  css
    wp_enqueue_style('ambasador-style', get_template_directory_uri() . '/assets/css/home.css');
    if (is_page('registration')) {
        wp_enqueue_style('custom-style', get_template_directory_uri() . '/assets/css/register.css');
    }
    // scripts
    wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/main.js', array(), null, true);
    wp_enqueue_script('my-accordion-script', get_template_directory_uri() . '/assets/js/accordion.js', array('jquery'), null, true);
    wp_enqueue_script('copy-clipboard', get_template_directory_uri() . '/assets/js/copy-clipboard.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function custom_admin_styles()
{
    $custom_css = "
        .js .postbox .hndle, .js .widget .widget-top {
            cursor: default !important;
        }
    ";
    wp_add_inline_style('wp-admin', $custom_css);
}
add_action('admin_enqueue_scripts', 'custom_admin_styles');
// Function for updating bonuses
function update_user_bonus($user_id, $quantity)
{
    global $wpdb;

    // Calculating bonuses
    $bonus = $quantity * 200;

    // Updating current_bonus for the user
    $result = $wpdb->query($wpdb->prepare(
        "UPDATE ambasador_pmb_users SET current_bonus = current_bonus + %d WHERE id = %d",
        $bonus,
        $user_id
    ));
    // debugging
    if ($result === false) {
        error_log("Error updating bonuses: " . $wpdb->last_error);
    } else {
        error_log("Bonuses successfully updated for the user $user_id.");
    }
}

// Function for resetting bonuses
function reset_user_bonus($user_id)
{
    global $wpdb;

    // Getting the user's current bonuses
    $user = $wpdb->get_row($wpdb->prepare(
        "SELECT current_bonus FROM ambasador_pmb_users WHERE id = %d",
        $user_id
    ));

    if ($user) {
        log_bonus_change(
            $user_id,
            $user->promo_code,
            $user->username,
            'reset',
            $user->current_bonus,
            0,
            $user->paid_bonus,
            $user->paid_bonus + $user->current_bonus,
            get_current_user_id()
        );
        // Moving current_bonus to paid_bonus
        $result = $wpdb->query($wpdb->prepare(
            "UPDATE ambasador_pmb_users SET paid_bonus = paid_bonus + %d, current_bonus = 0 WHERE id = %d",
            $user->current_bonus,
            $user_id
        ));

        $wpdb->query($wpdb->prepare(
            "UPDATE ambasador_pmb_users_data 
             SET status_wyplaty = 'wypłacono' 
             WHERE user_id = %d 
             ORDER BY id DESC LIMIT 1",
            $user_id
        ));

        // Debugging
        if ($result === false) {
            error_log("Error resetting bonuses: " . $wpdb->last_error);
        } else {
            error_log("Bonuses successfully reset for user $user_id.");
        }
    } else {
        error_log("User with ID $user_id not found.");
    }
}

// Registering the bonus management page in the admin panel
add_action('admin_menu', 'register_bonus_admin_page');



function handle_bonus_reset()
{
    global $wpdb;

    //  sent using the POST method
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_user'])) {
        // Checking  'nonce'
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'reset_bonus')) {
            wp_die('Security check error.');
        }

        $user_id = intval($_POST['reset_user']);

        reset_user_bonus($user_id);

        wp_redirect(admin_url('admin.php?page=bonus-control'));
        exit;
    }
}
add_action('admin_init', 'handle_bonus_reset');

function register_bonus_admin_page()
{
    add_menu_page(
        "Bonus Management",
        "Bonusy",
        "manage_options",
        "bonus-control",
        "render_bonus_control_page",
        "dashicons-money-alt",
        6
    );
    add_submenu_page(
        "bonus-control",
        "Statusy Boxów",
        "Statusy Boxów",
        "manage_options",
        "box-statuses",
        "render_box_statuses_page"
    );
    add_submenu_page(
        "bonus-control",
        "Ustawienia wypłat",
        "Ustawienia wypłat",
        "manage_options",
        "payout-settings",
        "render_payout_settings_page"
    );
}
//  "Ustawienia wypłat"
function render_payout_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['next_payout_date'])) {
        update_option('next_payout_date', sanitize_text_field($_POST['next_payout_date']));
        echo '<div class="notice notice-success"><p>Data została zaktualizowana!</p></div>';
    }

    $next_payout_date = get_option('next_payout_date', '');

?>
    <div class="wrap">
        <h1>Ustawienia wypłat bonusów</h1>
        <form method="POST">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="next_payout_date">Data następnej wypłaty:</label>
                    </th>
                    <td>
                        <input
                            type="date"
                            name="next_payout_date"
                            id="next_payout_date"
                            value="<?php echo esc_attr($next_payout_date); ?>"
                            required>
                        <p class="description">Format: RRRR-MM-DD (np. 2025-12-31)</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Zapisz datę'); ?>
        </form>

        <h3>Gdzie zmiana jest widoczna?</h3>
        <p>Nowa data automatycznie pojawi się:</p>

        W Panelu Ambasadora → sekcja „Dostępne bonusy”,

        W miejscu:

        <b>Termin następnej wypłaty: [wpisana data]</b>
    </div>
<?php
}

function render_box_statuses_page()
{
    global $wpdb;

    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to access this page.');
    }

    if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['new_status'])) {
        check_admin_referer('update_box_status');

        $order_id = intval($_POST['order_id']);
        $new_status = sanitize_text_field($_POST['new_status']);
        $old_status = $wpdb->get_var($wpdb->prepare("SELECT status FROM ambasador_pmb_purchases WHERE order_id = %d", $order_id));
        if ($old_status === 'potwierdzona') {
            echo '<div class="error"><p>Nie można zmienić statusu "potwierdzona" po jego zatwierdzeniu!</p></div>';
        } else {
            $wpdb->update(
                'ambasador_pmb_purchases',
                array('status' => $new_status),
                array('order_id' => $order_id),
                array('%s'),
                array('%d')
            );
            $order_data = $wpdb->get_row($wpdb->prepare(
                "SELECT kod_rabatowy, quantity 
             FROM ambasador_pmb_purchases 
             WHERE order_id = %d",
                $order_id
            ));

            if ($order_data) {
                $confirmed_quantity = $wpdb->get_var($wpdb->prepare(
                    "SELECT SUM(quantity) 
                 FROM ambasador_pmb_purchases 
                 WHERE kod_rabatowy = %s 
                   AND status = 'potwierdzona'",
                    $order_data->kod_rabatowy
                ));

                $wpdb->query($wpdb->prepare(
                    "UPDATE ambasador_pmb_users 
                 SET current_bonus = %d 
                 WHERE promo_code = %s",
                    $confirmed_quantity * 200,
                    $order_data->kod_rabatowy
                ));
            }
        }
        echo '<div class="updated"><p>Status zaktualizowany!</p></div>';
    }

    $promo_codes = $wpdb->get_col("SELECT DISTINCT kod_rabatowy FROM ambasador_pmb_purchases WHERE kod_rabatowy != ''");

    echo '<div class="wrap">';
    echo '<h1>Statusy płatności za boxy</h1>';

    foreach ($promo_codes as $promo_code) {
        $orders = $wpdb->get_results($wpdb->prepare(
            "SELECT p.*, u.username as ambassador_username, u.email as ambassador_email 
             FROM ambasador_pmb_purchases p
             LEFT JOIN ambasador_pmb_users u ON p.kod_rabatowy = u.promo_code 
             WHERE p.kod_rabatowy = %s 
             GROUP BY p.order_id 
             ORDER BY p.purchase_date DESC",
            $promo_code
        ));

        if ($orders) {
            echo '<div class="postbox" style="margin-bottom: 20px;">';
            echo '<button type="button" class="handlediv" aria-expanded="true">';
            echo '<span class="toggle-indicator" aria-hidden="true"></span>';
            echo '</button>';
            if (!empty($orders)) {
                $ambassador_username = esc_html($orders[0]->ambassador_username);
                $ambassador_email = esc_html($orders[0]->ambassador_email);
            } else {
                $ambassador_username = 'Nieznany';
                $ambassador_email = 'Brak danych';
            }

            echo '<h2 class="hndle"><span>Promokod: <span class="yellow-text">' . esc_html($promo_code) . '</span> | Ambasador: ' . $ambassador_username . ' (' . $ambassador_email . ')</span></h2>';
            echo '<div class="inside">';

            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr>
                    <th>Zamówienie</th>
                    <th>Model</th>
                    <th>Ilość</th>
                    <th>Data</th>
                    <th>Klient</th>
                    <th>Email klienta</th>
                    <th>Status płatności</th>
                    <th>Akcje</th>
                  </tr></thead>';
            echo '<tbody>';

            foreach ($orders as $order) {
                $status = $order->status ?: 'nowe';
                $status_class = '';
                switch ($status) {
                    case 'odrzucona':
                        $status_class = 'status-rejected';
                        break;
                    case 'oczekujaca':
                        $status_class = 'status-pending';
                        break;
                    case 'potwierdzona':
                        $status_class = 'status-confirmed';
                        break;
                    case 'nowe':
                    default:
                        $status_class = 'status-new';
                        break;
                }

                echo '<tr>';
                echo '<td>' . esc_html($order->crc) . '</td>';
                echo '<td>' . esc_html($order->produktZamowienie) . '</td>';
                echo '<td>' . esc_html($order->quantity) . '</td>';
                echo '<td>' . esc_html($order->purchase_date) . '</td>';
                echo '<td>' . esc_html($order->firstName . ' ' . $order->lastName) . '</td>';
                echo '<td>' . esc_html($order->email) . '</td>';
                echo '<td><span class="status-badge ' . $status_class . '">' . esc_html($status) . '</span></td>';
                echo '<td>
                        <form method="post" style="display:inline;">
                            ' . wp_nonce_field('update_box_status', '_wpnonce', true, false) . '
                            <input type="hidden" name="order_id" value="' . esc_attr($order->order_id) . '">
                            <select name="new_status"' . ($status === 'potwierdzona' ? ' disabled' : '') . '>
                                <option value="nowe" ' . selected($status, 'nowe', false) . '>Nowe</option>
                                <option value="oczekujaca" ' . selected($status, 'oczekujaca', false) . '>Oczekująca</option>
                                <option value="potwierdzona" ' . selected($status, 'potwierdzona', false) .
                    ($status === 'potwierdzona' ? ' disabled' : '') . '>Potwierdzona</option>
                                <option value="odrzucona" ' . selected($status, 'odrzucona', false) . '>Odrzucona</option>
                            </select>
                            ' . ($status === 'potwierdzona' ? '<input type="hidden" name="new_status" value="potwierdzona">' : '') . '
                            <button type="submit" name="update_status" class="button button-small" ' .
                    ($status === 'potwierdzona' ? 'disabled' : '') . '>Zapisz</button>
                        </form>
                      </td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
            echo '</div></div>';
        }
    }

    echo '</div>';


    echo '<style>
        .status-badge {
             padding: 10px;
        display: inline-block;
        text-transform: uppercase;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-new {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #cccccc;
        }
        .status-pending {
            background-color: rgba(0, 123, 255, 0.2);
            color: #004085;
            border: 1px solid #b8daff;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .postbox .hndle {
            cursor: pointer;
        }

    .hndle span {
          
    font-size: 20px;
    margin-left: 20px;
    }

    .hndle .yellow-text{
     color: #F6BE05;  
     margin-left: 0 !important;  
 }
     .status-confirmed{
             background: green;
        color: white;
}
    </style>';

    echo '<script>
        jQuery(document).ready(function($) {
            $(".postbox .handlediv").click(function() {
                $(this).parent().toggleClass("closed").next(".inside").toggle();
            });
        });
    </script>';
}

function render_bonus_control_page()
{
    global $wpdb;

    // Checking admin permissions
    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to access this page.');
    }

    // Processing the bonus reset request
    if (isset($_GET['reset_user']) && isset($_GET['_wpnonce'])) {
        $user_id = intval($_GET['reset_user']);
        if (wp_verify_nonce($_GET['_wpnonce'], 'reset_bonus')) {
            reset_user_bonus($user_id);
            echo '<div class="updated"><p>Bonuses successfully reset and moved to paid_bonus!</p></div>';
        } else {
            echo '<div class="error"><p>Security check failed. Please try again.</p></div>';
        }
    }

    $query = "
    SELECT 
        u.id, u.username, u.promo_code, u.current_bonus, u.paid_bonus,
        d.status_wyplaty, d.data_zgloszenia,
        d.imie, d.nazwisko, d.data_urodzenia, d.numer_rachunku,
        d.nazwa_banku, d.ulica, d.miasto, d.kod_pocztowy,
        d.urzad_skarbowy, d.email as user_email, d.telefon, d.pesel
    FROM ambasador_pmb_users u
    LEFT JOIN (
        SELECT 
            user_id, status_wyplaty, data_zgloszenia,
            imie, nazwisko, data_urodzenia, numer_rachunku,
            nazwa_banku, ulica, miasto, kod_pocztowy,
            urzad_skarbowy, email, telefon, pesel
        FROM ambasador_pmb_users_data
        WHERE id IN (
            SELECT MAX(id) 
            FROM ambasador_pmb_users_data 
            GROUP BY user_id
        )
    ) d ON u.id = d.user_id
    ORDER BY u.username
";

    $users = $wpdb->get_results($query);
?>
    <div class="wrap">
        <h1>Ręczne zarządzanie bonusami</h1>
        <div class="responsive-table">
            <table class="wp-list-table widefat fixed striped compact-table">
                <thead>
                    <tr>
                        <th>Login</th>
                        <th>Kod promocyjny</th>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Data urodzenia</th>
                        <th>Nr rachunku</th>
                        <th>Bank</th>
                        <th>Ulica</th>
                        <th>Miasto</th>
                        <th>Kod pocztowy</th>
                        <th>Urząd skarbowy</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>PESEL</th>
                        <th>Aktualne bonusy</th>
                        <th>Wypłacone bonusy</th>
                        <th>Status wypłaty</th>
                        <th>Data wniosku od Ambasadora</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= esc_html($user->username) ?></td>
                            <td><?= esc_html($user->promo_code) ?></td>
                            <td><?= $user->imie ? esc_html($user->imie) : 'brak' ?></td>
                            <td><?= $user->nazwisko ? esc_html($user->nazwisko) : 'brak' ?></td>
                            <td><?= $user->data_urodzenia ? esc_html($user->data_urodzenia) : 'brak' ?></td>
                            <td><?= $user->numer_rachunku ? esc_html($user->numer_rachunku) : 'brak' ?></td>
                            <td><?= $user->nazwa_banku ? esc_html($user->nazwa_banku) : 'brak' ?></td>
                            <td><?= $user->ulica ? esc_html($user->ulica) : 'brak' ?></td>
                            <td><?= $user->miasto ? esc_html($user->miasto) : 'brak' ?></td>
                            <td><?= $user->kod_pocztowy ? esc_html($user->kod_pocztowy) : 'brak' ?></td>
                            <td><?= $user->urzad_skarbowy ? esc_html($user->urzad_skarbowy) : 'brak' ?></td>
                            <td><?= $user->user_email ? esc_html($user->user_email) : 'brak' ?></td>
                            <td><?= $user->telefon ? esc_html($user->telefon) : 'brak' ?></td>
                            <td><?= $user->pesel ? esc_html($user->pesel) : 'brak' ?></td>
                            <td><?= $user->current_bonus ?> zł</td>
                            <td><?= $user->paid_bonus ?> zł</td>
                            <td><?= $user->status_wyplaty ? esc_html($user->status_wyplaty) : 'brak' ?></td>
                            <td><?= $user->data_zgloszenia ? esc_html($user->data_zgloszenia) : 'brak' ?></td>
                            <td>
                                <form method="post" class="inline-form">
                                    <?php wp_nonce_field('reset_bonus'); ?>
                                    <input type="hidden" name="reset_user" value="<?= esc_attr($user->id) ?>">
                                    <button type="submit" class="button bonus-reset-button" title="Wypłać">
                                        Wypłać
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .responsive-table {
            overflow-x: auto;
            width: 100%;
            min-width: 1800px;
            /* Увеличиваем минимальную ширину */
        }

        .compact-table {
            table-layout: auto;
            /* Автоматический расчет ширины */
            width: 100%;
        }

        .compact-table th,
        .compact-table td {
            white-space: normal;
            /* Разрешаем перенос текста */
            overflow: visible;
            min-width: 160px;
            /* Увеличиваем минимальную ширину */
            max-width: 350px;
            /* Максимальная ширина для длинных полей */
            word-wrap: break-word;
            padding: 8px 12px;
        }

        /* Специфичные настройки для email */
        .compact-table td:nth-child(12) {
            min-width: 200px;
            max-width: 250px;
            white-space: normal;
        }

        /* Фиксированная колонка с кнопкой */
        .compact-table th:last-child,
        .compact-table td:last-child {
            position: -webkit-sticky;
            position: sticky;
            right: 0;
            background: #fff;
            width: 140px;
            min-width: 140px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .bonus-reset-button {
            width: 100%;
            white-space: normal;
            height: auto;
            padding: 8px;
            line-height: 1.3;
        }


        .compact-table td,
        .compact-table th {
            padding: 8px;
            font-size: 13px;
            vertical-align: top;
        }

        .action-button {
            padding: 4px 8px;
            font-size: 12px;
        }
    </style>
<?php
} ?>

<script>
    // Handling the click on the 'Reset' button
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.reset-bonus-button');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const username = this.getAttribute('data-username');

                if (confirm(`reset bonus for ${username}?`)) {
                    document.getElementById('reset_user').value = userId;
                    document.querySelector('form').submit();
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.inline-form');

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const button = this.querySelector('button[type="submit"]');
                const username = this.closest('tr').querySelector('td:first-child').textContent;
                const bonusAmount = this.closest('tr').querySelector('td:nth-child(15)').textContent;

                if (confirm(`Zmienić status ${bonusAmount} dla ${username} na "wypłacono"?\n\n(Przelew realizowany oddzielnie)`)) {
                    this.submit();
                }
            });
        });
    });
</script>
<?php



// zapis do bonus_logs
function log_bonus_change($user_id, $promo_code, $username, $action, $old_current_bonus, $new_current_bonus, $old_paid_bonus, $new_paid_bonus, $changed_by)
{
    global $wpdb;

    $wpdb->insert(
        'bonus_logs',
        array(
            'user_id' => $user_id,
            'promo_code' => $promo_code,
            'username' => $username,
            'action' => $action,
            'old_current_bonus' => $old_current_bonus,
            'new_current_bonus' => $new_current_bonus,
            'old_paid_bonus' => $old_paid_bonus,
            'new_paid_bonus' => $new_paid_bonus,
            'changed_by' => $changed_by
        )
    );
}
