<?php

$method = $_SERVER['REQUEST_METHOD'];

//Script Foreach
$c = true;
if ( $method === 'POST' ) {

	$project_name = trim($_POST["project_name"]);
	$admin_email  = trim($_POST["admin_email"]);
	$form_subject = trim($_POST["form_subject"]);

	foreach ( $_POST as $key => $value ) {
		if ( $value != "" && $key != "project_name" && $key != "admin_email" && $key != "form_subject" && $key != "g-recaptcha-response") {
			$message .= "
			" . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
				<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
				<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
			</tr>
			";
		}
	}
} else if ( $method === 'GET' ) {

	$project_name = trim($_GET["project_name"]);
	$admin_email  = trim($_GET["admin_email"]);
	$form_subject = trim($_GET["form_subject"]);

	foreach ( $_GET as $key => $value ) {
		if ( $value != "" && $key != "project_name" && $key != "admin_email" && $key != "form_subject" && $key != "g-recaptcha-response" ) {
			$message .= "
			" . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
				<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
				<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
			</tr>
			";
		}
	}
}

$message = "<table style='width: 100%;'>$message</table>";

function adopt($text) {
	return '=?UTF-8?B?'.Base64_encode($text).'?=';
}

/*Google reCAPTCHA*/
const GOOGLE_RECAPTCHA_PRIVATE_KEY = 'SECRET_KEY'; // reCaptcha secret key

if (isset($_POST['g-recaptcha-response'])) {
    $params = [
        'secret' => GOOGLE_RECAPTCHA_PRIVATE_KEY,
        'response' => $_POST['g-recaptcha-response'],
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];
    $curl = curl_init('https://www.google.com/recaptcha/api/siteverify?' . http_build_query($params));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($curl));
    curl_close($curl);

    if (isset($response->success) && $response->success == true) {
        /*$fromEmail = 'noreply@site.ru';*/ // Если нужно указать другой емейл для отправки писем
        $headers = "MIME-Version: 1.0" . PHP_EOL .
            "Content-Type: text/html; charset=utf-8" . PHP_EOL .
            'From: '.adopt($project_name).' <'.$admin_email.'>' . PHP_EOL . //заменить "$admin_email" на "$fromEmail" если нужно указать другой емейл для отправки писем
            'Reply-To: '.$admin_email.'' . PHP_EOL;
        mail($admin_email, adopt($form_subject), $message, $headers );
        if(isset($_POST['Email'])){ // Дублирование письмо клиенту, если он указал емейл
            $message = '<p>Вы оставили заявку на сайте SITE_NAME. Наши менеджеры свяжутся с вами в ближайшее время.</p><p>Данные, которые вы оставили:</p>' . $message . '<p>С уважением, компания "SITE_COMPANY"</p>';
            mail($_POST['Email'], adopt($form_subject), $message, $headers );
        }
    }
}
/*END Google reCAPTCHA*/

