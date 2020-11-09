<?php
    $plaid_client_id = '5f64c507166e6d0012449b6c';
    $plaid_secret = '3132debb49806b95f75a35e1e1bdc5';
    $headers[] = 'Content-Type: application/json';

    $params = array(
        "client_id"     => $plaid_client_id,
        "secret"        => $plaid_secret,
        "client_name"   => "My App",
        "user"          => ["client_user_id" => "My User"],
        "products"      => ["transactions"],
        "country_codes" => ["US"],
        "language"      => "en",
        "webhook"       => "https://sample.webhook.com"
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://sandbox.plaid.com/link/token/create");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 80);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    if (!$result = curl_exec($ch)) {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);

    $jsonParsed = json_decode($result);

    exit($result);
?>