<?php

use Model\ShtrafovnetClient\ShtrafovnetClient;
use Symfony\Component\Yaml\Yaml;

date_default_timezone_set('Europe/Moscow');

try {
    require __DIR__.'/vendor/autoload.php';
    require __DIR__.'/bootstrap.php';

    $params = Yaml::parse(file_get_contents('app/config/parameters.yml'));

    $apiClient = new ShtrafovnetClient($params);

    /**
     * // EXAMPLE's
     *
     * list($headers, $body) = $apiClient->createAccount(
     *      'webmaster@example.com',
     *      '12345678',
     *      'Best service',
     *      'https://example.com',
     *      777,
     *      [
     *          'notificationUrl' => 'https://example.com/postback/shtrafovnet',
     *      ]
     * );
     *
     * list($headers, $body) = $apiClient->createUser('First User', 'user@example.com');
     *
     * list($headers, $body) = $apiClient->createCar([
     *      'email' => 'user@example.com',
     *      'name'  => 'Best Car',
     *      'cert'  => '5051034816',
     *      'reg'   => 'Т356НХ750',
     * ]);
     *
     * list($headers, $body) = $apiClient->createInvoice([
     *      'email'          => 'user@example.com',
     *      'wireSurname'    => 'Иванов',
     *      'wireName'       => 'Иван',
     *      'wirePatronymic' => 'Иванович',
     *      'successUrl'     => 'https://example.com/success',
     *      'failUrl'        => 'https://example.com/fail',
     *      'fines'          => [
     *          2952407,
     *          2952402,
     *          2952416,
     *          2952400,
     *      ],
     * ]);
     *
     *     list($headers, $body) = $apiClient->getCarFines(1003747, [
     *         'paid'    => 'nopaid',
     *         'filters' => ['discount'],
     *     ]);
     */

    displayResponse($headers, $body);
} catch (\Exception $e) {
    displayError($e);
}