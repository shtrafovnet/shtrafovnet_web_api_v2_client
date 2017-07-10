<?php

namespace Model\ShtrafovnetClient;

class ShtrafovnetClient
{
    /**
     * @var array
     */
    private $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    private function sendGetRequest($url, $queryParameters = [], $headers = [])
    {
        if (!empty($queryParameters)) {
            $url .= (stripos($url, "?") === false ? "?" : "&").http_build_query($queryParameters);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (($response = curl_exec($curl)) === false) {
            throw new \Exception(curl_error($curl));
        }

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeader = trim(substr($response, 0, $header_size));
        $responseBody = trim(substr($response, $header_size));

        curl_close($curl);

        return [$responseHeader, $responseBody];
    }

    private function sendPostRequest($url, $body, $headers = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (($response = curl_exec($curl)) === false) {
            throw new \Exception(curl_error($curl));
        }

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeader = trim(substr($response, 0, $header_size));
        $responseBody = trim(substr($response, $header_size));

        curl_close($curl);

        return [$responseHeader, $responseBody];
    }

    private function sendPatchRequest($url, $body, $headers = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (($response = curl_exec($curl)) === false) {
            throw new \Exception(curl_error($curl));
        }

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeader = trim(substr($response, 0, $header_size));
        $responseBody = trim(substr($response, $header_size));

        curl_close($curl);

        return [$responseHeader, $responseBody];
    }

    private function sendDeleteRequest($url, $body, $headers = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (($response = curl_exec($curl)) === false) {
            throw new \Exception(curl_error($curl));
        }

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $responseHeader = trim(substr($response, 0, $header_size));
        $responseBody = trim(substr($response, $header_size));

        curl_close($curl);

        return [$responseHeader, $responseBody];
    }

    /**
     * @return string
     */
    public function getApiBaseUrl(): string
    {
        return $this->params['host'] ?? 'http://example.com';
    }

    /**
     * @param string $api_base_url
     */
    public function setApiBaseUrl(string $api_base_url)
    {
        $this->api_base_url = $api_base_url;
    }


    public function getBasicAuthHeader()
    {
        $username = $this->getParams()['account']['login'] ?? 'username';
        $password = $this->getParams()['account']['password'] ?? 'password';

        return 'Authorization: Basic '.base64_encode($username.":".$password);
    }

    public function getBearerAuthHeader()
    {
        $token = $this->getParams()['token'] ?? 'fail_token';

        return 'Authorization: Bearer '.$token;
    }

    /**
     * ===============================================================
     * ACCOUNT
     * ===============================================================
     */
    /**
     * Создание нового аккаунта
     * POST /account
     */
    public function createAccount($email, $password, $name, $site_url, $affiliate_id, $extraData = [])
    {
        $url = $this->getApiBaseUrl()."/account";

        $headers = [
            'Content-Type: application/json',
        ];

        $data = [
            'email'         => $email,
            'plainPassword' => $password,
            'name'          => $name,
            'url'           => $site_url,
            'affiliateId'   => $affiliate_id,
        ];

        $data = array_merge($data, $extraData);

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * Обновление информации аккаунта
     * PATCH /account
     */
    public function updateAccount($data = [])
    {
        $url = $this->getApiBaseUrl()."/account";

        $headers = [
            'Content-Type: application/json',
            $this->getBasicAuthHeader(),
        ];

        return $this->sendPatchRequest($url, json_encode($data), $headers);
    }

    /**
     * Сброс и отправка нового пароля от аккаунта
     * POST /account/reset-password
     */
    public function resetPasswordAccount()
    {
        $url = $this->getApiBaseUrl()."/account/reset-password";

        $headers = [
            'Content-Type: application/json',
        ];

        $data = [
            'email' => $this->getParams()['account']['login'] ?? 'user@example.com',
        ];

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * Получение информации об аккаунте
     * GET /account
     */
    public function getAccount()
    {
        $url = $this->getApiBaseUrl()."/account";

        $headers = [
            $this->getBasicAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }

    /**
     * ===============================================================
     * TOKENS
     * ===============================================================
     */
    /**
     * Создание токена доступа к ресурсам ШтрафовНЕТ
     * POST /tokens
     */
    public function createToken()
    {
        $url = $this->getApiBaseUrl()."/tokens";

        $headers = [
            $this->getBasicAuthHeader(),
        ];

        return $this->sendPostRequest($url, [], $headers);
    }

    /**
     * ===============================================================
     * USER's
     * ===============================================================
     */
    /**
     * Получение списка пользователей
     * GET /users
     */
    public function getUsers()
    {
        $url = $this->getApiBaseUrl()."/users";

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }

    /**
     * Получение информации о пользователе
     * GET /users/{email}
     */
    public function getUser($email)
    {
        $url = $this->getApiBaseUrl()."/users/".$email;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }

    /**
     * Создание нового пользователя
     * POST /users
     */
    public function createUser($username, $email)
    {
        $url = $this->getApiBaseUrl()."/users";

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        $data = [
            'email'    => $email,
            'username' => $username,
        ];

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * Обновление пользователя
     * PATCH /users/{email}
     */
    public function updateUser($email, $data = [])
    {
        $url = $this->getApiBaseUrl()."/users/".$email;

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        return $this->sendPatchRequest($url, json_encode($data), $headers);
    }

    /**
     * Удаление пользователя
     * DELETE /users/{email}
     */
    public function deleteUser($email)
    {
        $url = $this->getApiBaseUrl()."/users/".$email;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendDeleteRequest($url, [], $headers);
    }

    /**
     * Получение списка транспортных средств пользователя
     * GET /users/{email}/cars
     */
    public function getUserCars($email)
    {
        $url = $this->getApiBaseUrl()."/users/".$email."/cars";

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }

    /**
     * Получение списка штрафов пользователя
     * GET /users/{email}/fines
     */
    public function getUserFines($email, $queryParams = [])
    {
        $url = $this->getApiBaseUrl()."/users/".$email."/fines";

        if (!empty($queryParams)) {
            $url .= "?".http_build_query($queryParams);
        }

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }

    /**
     * ===============================================================
     * CARS's
     * ===============================================================
     */
    /**
     * Информация о транспортном средстве
     * GET /cars
     */
    public function getCar($id)
    {
        $url = $this->getApiBaseUrl()."/cars/".$id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }

    /**
     * Создание нового транспортного средства
     * POST /cars
     */
    public function createCar($data)
    {
        $url = $this->getApiBaseUrl()."/cars";

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * Обновление транспортного средства
     * PATCH /cars/{id}
     */
    public function updateCar($id, $data = [])
    {
        $url = $this->getApiBaseUrl()."/cars/".$id;

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        return $this->sendPatchRequest($url, json_encode($data), $headers);
    }

    /**
     * Обновление транспортного средства
     * DELETE /cars/{id}
     */
    public function deleteCar($id)
    {
        $url = $this->getApiBaseUrl()."/cars/".$id;

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        return $this->sendDeleteRequest($url, [], $headers);
    }

    /**
     * Получение штрафов транспортного средства
     * GET /cars/{id}/fines
     */
    public function getCarFines($id, $queryParams = [])
    {
        $url = $this->getApiBaseUrl()."/cars/".$id."/fines";

        if (!empty($queryParams)) {
            $url .= "?".http_build_query($queryParams);
        }

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }

    /**
     * ===============================================================
     * FINES's
     * ===============================================================
     */
    /**
     * Информация о штрафе
     * GET /fines/{id}
     */
    public function getFine($id)
    {
        $url = $this->getApiBaseUrl()."/fines/".$id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }

    /**
     * ===============================================================
     * SCAN
     * ===============================================================
     */
    /**
     * Проверка штрафов
     * POST /scan
     */
    public function scan($car_id)
    {
        $url = $this->getApiBaseUrl()."/scan";

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        $data = [
            'car' => $car_id,
        ];

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * Получение статуса проверки
     * GET /scan/{id}
     */
    public function getScanStatus($id)
    {
        $url = $this->getApiBaseUrl()."/scan/".$id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }

    /**
     * ===============================================================
     * INVOICE's
     * ===============================================================
     */
    /**
     * Создание счета на оплату штрафов пользователя
     * POST /invoices
     */
    public function createInvoice($data)
    {
        $url = $this->getApiBaseUrl()."/invoices";

        $headers = [
            'Content-Type: application/json',
            $this->getBearerAuthHeader(),
        ];

        return $this->sendPostRequest($url, json_encode($data), $headers);
    }

    /**
     * Получение информации о платеже
     * GET /invoices/{id}
     */
    public function getInvoice($id)
    {
        $url = $this->getApiBaseUrl()."/invoices/".$id;

        $headers = [
            $this->getBearerAuthHeader(),
        ];

        return $this->sendGetRequest($url, [], $headers);
    }
}