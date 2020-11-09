<?php
    $plaid_client_id = '5f64c507166e6d0012449b6c';
    $public_token = $_REQUEST['public_token'];
    $plaid_secret = '3132debb49806b95f75a35e1e1bdc5';
    $env = 'sandbox';
    $account_id = $_REQUEST['account_id'];

    $headers[] = 'Content-Type: application/json';

    $params = array(
        "client_id"     => $plaid_client_id,
        "secret"        => $plaid_secret,
        "public_token"  => $public_token,
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://" . $env . ".plaid.com/item/public_token/exchange");
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

    $btok_params = array(
        'client_id' => $plaid_client_id,
        'secret' => $plaid_secret,
        'access_token' => $jsonParsed->access_token,
        'account_id' => $account_id,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://" . $env . ".plaid.com/processor/stripe/bank_account_token/create");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($btok_params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 80);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    if (!$result = curl_exec($ch)) {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);

    $btoken = json_decode($result);

    $stripe_bank_account_token = $btoken->stripe_bank_account_token;
?>
