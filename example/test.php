<?php

require_once './../vendor/autoload.php';

/*
clientId - ID вашего приложения.
redirectUrl - URL куда будет перенаправлен ответ с кодом.
portalUrl - по умолчанию: https://esia-portal1.test.gosuslugi.ru/. Домен портала для авторизация (только домен).
codeUrlPath - по умолчанию: aas/oauth2/ac. URL для получения кода.
tokenUrlPath - по умолчанию: aas/oauth2/te. URL для получение токена.
scope - по умолчанию: fullname birthdate gender email mobile id_doc snils inn. Запрашиваемые права у пользователя.
privateKeyPath - путь до приватного ключа.
privateKeyPassword - пароль от приватного ключа.
certPath - путь до сертификата.
tmpPath - путь до дериктории где будет проходить подпись (должна быть доступна для записи).
 */

$request = var_export(
    [
        '$_ENV' => $_ENV ?? '',
        '$_COOKIE' => $_COOKIE ?? '',
        '$_FILES' => $_FILES ?? '',
        '$_GET' => $_GET ?? '',
        '$_POST' => $_POST ?? '',
        '$_REQUEST' => $_REQUEST ?? '',
        '$_SERVER' => $_SERVER ?? '',
        '$_SESSION' => $_SESSION ?? ''
    ],
    true
);

function writeLog($message)
{
    $input = file_put_contents(
        './tmp/log-' . time() . '.txt',
        $message,
        FILE_APPEND,
    );
}

function printout(
    $description,
    $variable,
) {
    echo '<pre>' . PHP_EOL . $description . PHP_EOL;
    var_dump($variable);
    echo '</pre>' . PHP_EOL;

    writeLog(
        $description .
        PHP_EOL .
        var_export($variable, true) .
        PHP_EOL
    );
}

printout('Request data is ', $request);


/** @noinspection PhpUnhandledExceptionInspection */
$config = new \Esia\ConfigWithoutKeyPair([
    'clientId' => 'U407501',
    'redirectUrl' => 'http://localhost:8000/',
    'portalUrl' => 'https://esia-portal1.test.gosuslugi.ru/',
    'scope' => [
        'fullname', /* Просмотр вашей фамилии, имени и отчества */
        'id_doc', /* Просмотр данных о вашем документе, удостоверяющем личность */

    ]
]);

$signer = new Esia\Signer\HttpSigner(
    'http://localhost:3037/cryptopro/sign',
    [
        'Content-Type' => 'application/json',
        'Accept' => '*/*', /* не обязательный заголовок */
    ],
    'POST',
);

$esia = new \Esia\OpenId($config);
$esia->setSigner($signer);


$code = $_GET['code'] ?? '';

if (!$code) {
    $dest = $esia->buildUrl();
    ?>

    <p>
        <a href="<?= $dest ?>">Войти через портал ГосУслуги</a>
    </p>
    <?php
}

if ($code) {
    printout('JWT token is ', $esia->getToken($code));
    printout('DocInfo is ', $esia->getDocInfo());
    printout('PersonInfo is ', $esia->getPersonInfo());
}