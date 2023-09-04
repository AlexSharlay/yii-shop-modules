<?php

//https://express-pay.by/docs/api/v1

namespace common\components\erip;


class expayApi {

    private static $baseUrl = "https://api.express-pay.by/v1/";

    // Формирование цифровой подписи
    public static function computeSignature($requestParams, $secretWord, $method) {
        $normalizedParams = array_change_key_case($requestParams, CASE_LOWER);
        $mapping = array(
            "add-invoice" => array(
                "token",
                "accountno",
                "amount",
                "currency",
                "expiration",
                "info",
                "surname",
                "firstname",
                "patronymic",
                "city",
                "street",
                "house",
                "building",
                "apartment",
                "isnameeditable",
                "isaddresseditable",
                "isamounteditable"),
            "get-details-invoice" => array(
                "token",
                "id"),
            "cancel-invoice" => array(
                "token",
                "id"),
            "status-invoice" => array(
                "token",
                "invoiceid"),
            "get-list-invoices" => array(
                "token",
                "from",
                "to",
                "accountno",
                "status"),
            "get-list-payments" => array(
                "token",
                "from",
                "to",
                "accountno"),
            "get-details-payment" => array(
                "token",
                "id")
        );

        $apiMethod = $mapping[$method];
        $result = "";

        foreach ($apiMethod as $item){
            $result .= $normalizedParams[$item];
        }

        $hash = strtoupper(hash_hmac('sha1', $result, $secretWord));

        return $hash;
    }

    // Отправка GET запроса
    private static function sendRequestGET($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    // Отправка POST запроса
    private static function sendRequestPOST($url, $params) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    // Отправка DELETE запроса
    private static function sendRequestDELETE($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    // Просмотр списка счетов по параметрам
    public static function getListInvoices($token, $fromDate = "", $toDate = "", $status = "", $accountNo = "") {
        $url = self::$baseUrl . "invoices?token=" . $token . "&From=" . $fromDate . "&To=" . $toDate . "&AccountNo=" . $accountNo . "&Status=" . $status;

        // Использование цифровой подписи
        // $requestParams = array(
        //     "Token" => $token,
        //     //Параметры фильтра являются опциональными, по умолчанию возвращает значения за последние 30 дней
        //     "AccountNo" => $accountNo,
        //     "From" => $fromDate,
        //     "To" => $toDate,
        //     "Status" => $status
        // );

        // $signature = self::computeSignature($requestParams, "", "get-list-invoices");
        // $url .= "&signature=" . $signature;

        return self::sendRequestGET($url); 
    }


    // Выставление счета
    public static function addInvoice($token, $numberAccount, $amount, $currency, $expiration = "", $info = "",
        $surname = "", $firstName = "", $patronymic = "", $city = "", $street = "", $building = "", $apartment = "",
        $isNameEditable = "", $isAddressEditable = "", $isAmountEditable = "") {

        $url = self::$baseUrl . "invoices?token=" . $token;

        $requestParams = array(
                "Token" => $token,
                "AccountNo" => $numberAccount,
                "Amount" => $amount,
                "Currency" => $currency,
                "Expiration" => $expiration,
                "Info" => $info,
                "Surname" => $surname,
                "FirstName" => $firstName,
                "Patronymic" => $patronymic,
                "City" => $city,
                "Street" => $street,
                "Building" => $building,
                "Apartment" => $apartment,
                "IsNameEditable" => $isNameEditable,
                "IsAddressEditable" => $isAddressEditable,
                "IsAmountEditable" => $isAmountEditable
        );

        // Использование цифровой подписи
        // $signature = self::computeSignature($requestParams, "", "add-invoice");
        // $url .= "&signature=" . $signature;

        return self::sendRequestPOST($url, $requestParams);
    }

    // Детальная информация о счете
    public static function getDetailsInvoice($numberInvoice, $token) {
        $url = self::$baseUrl . "invoices/" . $numberInvoice . "?token=" . $token;

        // Использование цифровой подписи
        // $requestParams = array(
        //     "Token" => $token,
        //     "Id" => $numberInvoice
        // );

        // $signature = self::computeSignature($requestParams, "", "get-details-invoice");
        // $url .= "&signature=" . $signature;

        return self::sendRequestGET($url); 
    }

    // Статус счета
    public static function statusInvoice($numberInvoice, $token) {
        $url = self::$baseUrl . "invoices/" . $numberInvoice . "/status?token=" . $token;

        // Использование цифровой подписи
        // $requestParams = array(
        //     "Token" => $token,
        //     "Id" => $numberInvoice
        // );

        // $signature = self::computeSignature($requestParams, "", "status-invoice");
        // $url .= "&signature=" . $signature;

        return self::sendRequestGET($url); 
    }

    // Отменить счет
    public static function cancelInvoice($numberInvoice, $token) {
        $url = self::$baseUrl . "invoices/" . $numberInvoice . "?token=" . $token;

        // Использование цифровой подписи
        // $requestParams = array(
        //     "Token" => $token,
        //     "Id" => $numberInvoice
        // );

        // $signature = self::computeSignature($requestParams, "", "get-details-invoice");
        // $url .= "&signature=" . $signature;

        return self::sendRequestDELETE($url); 
    }

    // Получить список оплат
    public static function getListPayments($token, $fromDate = "", $toDate = "", $numberPayment = "") {
        $url = self::$baseUrl . "payments?token=" . $token;

        // Использование цифровой подписи
        // $requestParams = array(
        //     "Token" => $token,
        //      //Параметры фильтра являются опциональными, по умолчанию возвращает значения за последние 30 дней
        //      "AccountNo" => $numberPayment,
        //      "From" => $fromDate,
        //      "To" => $toDate,
        // );

        // $signature = self::computeSignature($requestParams, "", "get-list-payments");
        // $url .= "&signature=" . $signature;

        return self::sendRequestGET($url); 
    }

    // Детальная информация об оплате
    public static function getDetailPayment($token, $numberPayment) {
        $url = self::$baseUrl . "payments/" . $numberPayment . "?token=" . $token;

        // Использование цифровой подписи
        // $requestParams = array(
        //     "Token" => $token,
        //      "PaymentNo" => $numberPayment
        // );

        // $signature = self::computeSignature($requestParams, "", "get-details-payment");
        // $url .= "&signature=" . $signature;

        return self::sendRequestGET($url); 
    }   
}

// Примеры запуска
// echo expayAPI::getListInvoices("a75b74cbcfe446509e8ee874f421bd64"); //Просмотр списка счетов по параметрам
// echo expayAPI::getDetailsInvoice(10, "a75b74cbcfe446509e8ee874f421bd65"); //Детальная информация о счете
// echo expayAPI::addInvoice("a75b74cbcfe446509e8ee874f421bd64", 1, 4100500, 974); //Выставление счета
// echo expayAPI::statusInvoice(10, "a75b74cbcfe446509e8ee874f421bd64"); //Статус счета
// echo expayAPI::cancelInvoice(1, "a75b74cbcfe446509e8ee874f421bd64"); //Отменить счет
// echo expayAPI::getListPayments("a75b74cbcfe446509e8ee874f421bd64"); //Получить список оплат
// echo expayAPI::getDetailPayment("a75b74cbcfe446509e8ee874f421bd64", 1); //Детальная информация об оплате