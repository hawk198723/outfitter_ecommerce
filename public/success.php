<?php
if (isset($_GET['paymentId']) && isset($_GET['PayerID'])) {
    // 从 URL 获取 paymentId 和 PayerID
    $paymentId = $_GET['paymentId'];
    $payerId = $_GET['PayerID'];

    $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);

    $execute = new \PayPal\Api\PaymentExecution();
    $execute->setPayerId($payerId);

    try {
        $result = $payment->execute($execute, $apiContext);
        echo "Payment made successfully!";
        // 这里可以更新订单状态，记录支付信息等
    } catch (Exception $ex) {
        // 处理错误
        die($ex);
    }
} else {
    echo "User cancelled payment or did not fully authorize.";
}
