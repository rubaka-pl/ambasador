<?php
/*
Template Name: forgot password page
*/

require 'db.php';

$message = '';

// Функция генерации случайного пароля
function generatePassword($length = 16)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#';
    $password = '';
    $maxIndex = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, $maxIndex)];
    }
    return $password;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Проверяем, существует ли пользователь с указанным email
    $sql = "SELECT * FROM ambasador_pmb_users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Генерируем новый пароль
        $password = generatePassword();

        // Шифруем пароль перед сохранением в базу данных
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Обновляем пароль в базе данных
        $update_sql = "UPDATE ambasador_pmb_users SET password='$hashed_password' WHERE email='$email'";
        if ($conn->query($update_sql) === TRUE) {
            // Формируем email с новым паролем
            $to = $email;
            $subject = "Nowe hasło do Twojego konta";

            // HTML-сообщение
            $email_message = "
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Nowe hasło do Twojego konta</title>
            </head>
            <body style='font-family: Arial, sans-serif; color: #333;'>
                <p>Szanowny Kliencie,</p>
                <p>Miło nam, że zwróciłeś się do nas. W odpowiedzi na Twoje zapytanie, generujemy nowe hasło do Twojego konta. Twoje nowe hasło to: <strong>$password</strong></p>
                <br>
                <table style='width:100%; border:none;'>
                    <tr style='height:38.75pt;'>
                        <td style='border:none; border-bottom:solid #ffc000 1.5pt; background:white; padding:0 5px;'>
                            <p style='color:black;'>
                                Pozdrawiamy serdecznie,<br><br>
                                <b style='font-size:13.5pt; color:black;'>Zespół Parking Magic Box</b><br>
                                <span style='font-size:10.5pt; color:black;'>Specjalista ds. marketingu</span><br>
                                <b style='font-size:10.5pt; color:black;'>Parking Magic Box</b><br>
                            </p>
                        </td>
                    </tr>
                    <tr style='height:84.9pt;'>
                        <td style='border:none; border-bottom:solid #ffc000 1.5pt; padding:0 5px;'>
                            <table border='0' cellspacing='0' cellpadding='0' style='width:100%;'>
                                <tr style='height:11.75pt;'>
                                    <td style='height:11.75pt;'>
                                        <p style='font-size:12pt;'>Telefon: <a href='tel:576513655' style='color:#0070c0; text-decoration:none; font-size:10.5pt;'>576 513 655</a></p>
                                    </td>
                                </tr>
                                <tr style='height:11.75pt;'>
                                    <td style='height:11.75pt;'>
                                        <p style='font-size:10.5pt;'>Email: <a href='mailto:marketing@parkingmagicbox.com' style='color:#0563c1; text-decoration:none;'>marketing@parkingmagicbox.com</a></p>
                                    </td>
                                </tr>
                                <tr style='height:11.75pt;'>
                                    <td style='height:11.75pt;'>
                                        <p style='font-size:10.5pt;'>Strona: <a href='http://www.parkingmagicbox.com/' style='color:#0563c1; text-decoration:none;'>www.parkingmagicbox.com</a></p>
                                    </td>
                                </tr>
                                <tr style='height:11.75pt;'>
                                    <td style='height:11.75pt;'>
                                        <p style='font-size:10.5pt; color:black;'>
                                            <b>PMB Sp. z o.o.</b>, ul. Armii Krajowej 8; 17-300 Siemiatycze,<br>
                                            <b>NIP</b>: 5441541443 | <b>REGON</b>: 385900060 | <b>KRS</b>: 0000837230
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>";

            // Настройки заголовков для HTML-сообщения
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: parkingmagicbox@attthost24.pl\r\n";
            $headers .= "Reply-To: parkingmagicbox@attthost24.pl\r\n";

            // Отправляем email
            if (mail($to, $subject, $email_message, $headers)) {
                $message = '<div class="success-message">Nowe hasło zostało wysłane na Twój adres email.</div>';
            } else {
                $message = '<div class="error-message">Wystąpił błąd podczas wysyłania emaila.</div>';
            }
        } else {
            $message = '<div class="error-message">Wystąpił błąd podczas resetowania hasła.</div>';
        }
    } else {
        $message = '<div class="error-message">Użytkownik z tym adresem email nie istnieje.</div>';
    }
}

$conn->close();
?>

<?php get_header(); ?>

<main>
    <div class="forgot-password-container container-form">
        <h1>Zapomniałeś hasła?</h1>
        <p>Wprowadź swój adres email, aby zresetować hasło.</p>
        <?php echo $message; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="action-btn passwords-btn">Wyślij nowe hasło</button>
            </div>
        </form>
        <div class="additional-links">
            <a href="<?php echo home_url('/login'); ?>">Wróć do logowania</a>
        </div>
    </div>
</main>

<?php get_footer(); ?>