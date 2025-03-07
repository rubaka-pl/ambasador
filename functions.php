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
    // main css
    wp_enqueue_style('ambasador-style', get_template_directory_uri() . '/assets/css/home.css');
    // Additional style
    if (is_page('registration')) {
        wp_enqueue_style('custom-style', get_template_directory_uri() . '/assets/css/register.css');
    }
    // main script
    wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/main.js', array(), null, true);
    // accordion script
    wp_enqueue_script('my-accordion-script', get_template_directory_uri() . '/assets/js/accordion.js', array('jquery'), null, true);
    // Copy to clipboard script
    wp_enqueue_script('copy-clipboard', get_template_directory_uri() . '/assets/js/copy-clipboard.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

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


    $users = $wpdb->get_results("SELECT * FROM ambasador_pmb_users");
?>
    <div class="wrap">
        <h1>Ręczne zarządzanie bonusami</h1>
        <!-- Form for resetting bonuses -->
        <form method="post" action="">
            <?php wp_nonce_field('reset_bonus'); ?>
            <input type="hidden" name="reset_user" id="reset_user" value="">
        </form>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Login</th>
                    <th>Kod promocyjny</th>
                    <th>Aktualne bonusy</th>
                    <th>Wypłacone bonusy</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= esc_html($user->username) ?></td>
                        <td><?= esc_html($user->promo_code) ?></td>
                        <td><?= $user->current_bonus ?> zł</td>
                        <td><?= $user->paid_bonus ?> zł</td>
                        <td>
                            <button type="button"
                                class="button reset-bonus-button"
                                data-user-id="<?= esc_attr($user->id) ?>"
                                data-username="<?= esc_attr($user->username) ?>">
                                Wypłacić (zresetować aktualne bonusy)
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

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
    </script>
<?php
}


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
