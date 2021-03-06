<?php

    define("CONTACT_FORM", 'roman.kopka@yandex.ru');

    // функция проверки введенного email клиентом
    function ValidateEmail($value){
        $regex = '/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i';

        if($value == '') {
            return false;
        } else {
            $string = preg_replace($regex, '', $value);
        }

        return empty($string) ? true : false;
    }
    //============================================

    $post = (!empty($_POST)) ? true : false;

    if($post){


        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $total = $_POST['total'];
        $email = stripslashes($_POST['email']);
        $goods = $_POST['goods'];
        $tr = '';
        foreach ($goods as $item) {
            $tr = $tr . '<tr><td>' . $item['productNameRus'] . '</td>' .
            '<td>' . $item['productPrice'] . '</td>' .
            '<td>' . ((int)$item['amount'] . ' x ' . $item['productUnit']) . '</td>' .
            '<td>' . $item['productSum'] . '</td></tr>';
    }

    $subject = 'Заказ тортов с сайта Cookiemix';

    $messageToClient = '
	<html>
		<head>
			<title>Заказ тортов с сайта Cookiemix</title>
			<style>
		     td, th{
		      border: 1px solid #d4d4d4;
		      padding: 5px;
		     }
		  	</style>
		</head>
		<body>
			<h2>Заказ</h2>
            <p>Здравствуйте, от вас поступила заявка на заказ с сайта Cookiemix</p>
			<table>
				<thead>
					<tr>
						<th>Название товара</th>
						<th>Цена (рубли)</th>
						<th>Количество (ед. измерения)</th>
						<th>Сумма по товару</th>
					</tr>
				</thead>
				<tbody>'
				. $tr .
				'</tbody>
			</table>
            <p>Итоговая сумма вашего заказа равна: ' . $total . ' руб.<p>
			<h2>Заказчик</h2>
			<table>
				<thead>
					<tr>
						<th>Имя</th>
						<th>Телефон</th>
                        <th>Email</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>' . $name . '</td>
						<td>' . $phone . '</td>
                        <td>' . $email . '</td>
					</tr>
				</tbody>
			</table>
            <p>В самое ближайшее время с вами свяжется наш менеджер, для уточнения адреса доствавки и деталей оплаты товара.</p>
            <p>Если у вас остались вопросы или пожелания, то пишите на наш адрес электронной почты <a href="mailto:roman.kopka@yandex.ru"><b>roman.kopka@yandex.ru</b></p>
		</body>
	</html>';

    $messageToMe = '
    <html>
        <head>
            <title>Заказ тортов с сайта Cookiemix</title>
            <style>
             td, th{
              border: 1px solid #d4d4d4;
              padding: 5px;
             }
            </style>
        </head>
        <body>
            <p>Поступила заявка от клиента с сайта Coockiemix</p>
            <h2>Заказ</h2>
            <table>
                <thead>
                    <tr>
                        <th>Название товара</th>
                        <th>Цена (рубли)</th>
                        <th>Количество (ед. измерения)</th>
                        <th>Сумма по товару</th>
                    </tr>
                </thead>
                <tbody>'
                . $tr .
                '</tbody>
            </table>
            <p>Итого: ' . $total . ' руб.<p>
            <h2>Заказчик</h2>
            <table>
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Телефон</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>' . $name . '</td>
                        <td>' . $phone . '</td>
                        <td>' . $email . '</td>
                    </tr>
                </tbody>
            </table>
        </body>
    </html>';

    $error = '';

    if(!$name)
    {
        $error .= 'Пожалуйста, введите ваше имя.<br />';
    }

    if(!$phone)
    {
        $error .= 'Пожалуйста, введите ваш телефон.<br />';
    }

    if (!ValidateEmail($email)){
        $error = 'Email введен неправильно!';
    }

    if(!$error){
        //отправка почты клиенту
        $mailToClient = mail(
         $email,
         $subject, $messageToClient,
             "From: Интернет-магазин coockiemix.ru<roman.kopka@yandex.ru>\r\n"
            ."Reply-To: ".$email."\r\n"
            ."Content-type: text/html; charset=utf-8 \r\n"
            ."X-Mailer: PHP/" . phpversion());

        //Отправка почты мне
        $mailToMe = mail(
         'roman.kopka@yandex.ru',
         //$email,
         $subject,
         $messageToMe,
             "From: Интернет-магазин coockiemix.ru <roman.kopka@yandex.ru>\r\n"
            ."Reply-To: ".$email."\r\n"
            ."Content-type: text/html; charset=utf-8 \r\n"
            ."X-Mailer: PHP/" . phpversion());

        if($mailToClient && $mailToMe){
            echo 'OK';
        }

    }else{
        echo '<div class="bg-danger">'.$error.'</div>';
    }

}
?>
