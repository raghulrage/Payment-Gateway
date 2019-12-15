<?php
$orderId = $_POST["orderId"];
$orderAmount = $_POST["amount"];

echo $orderId . "|" . $orderAmount;
$host = "./ngrok.io"; //local url
$notifyUrl = $host. "/cf-checkout/notify.php";
$returnUrl = $host. "/cf-checkout/return.php";

$orderDetails = array();
$orderDetails["notifyUrl"] = $notifyUrl;
$orderDetails["returnUrl"] = $returnUrl;

$userDetails = getUserDetails($orderId);
$order = getOrderDetails($orderId);

$orderDetails["customerName"] = $userDetails["customerName"];
$orderDetails["customerEmail"] = $userDetails["customerEmail"];
$orderDetails["customerPhone"] = $userDetails["customerPhone"];

$orderDetails["orderId"] = $order["orderId"];
$orderDetails["orderAmount"] = $order["orderAmount"];
$orderDetails["orderNote"] = $order["orderNote"];
$orderDetails["orderCurrency"] = $order["orderCurrency"];

$orderDetails["appId"] = "9275b8fe7664d4779876054c5729";

$orderDetails["signature"] = generateSignature($orderDetails);

echo json_encode($orderDetails);

function generateSignature($postData){
  $secretKey = "9ff22c327535346594f2c957bf6081a1997413b6";
 ksort($postData);
 $signatureData = "";
 foreach ($postData as $key => $value){
      $signatureData .= $key.$value;
 }
 $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
 $signature = base64_encode($signature);
 return $signature;
}
?>
 <form id="redirectForm" method="post" action="https://test.cashfree.com/billpay/checkout/post/submit">
    <input type="hidden" name="appId" value="<?php echo $orderDetails["appId"] ?>"/>
    <input type="hidden" name="orderId" value="<?php echo $orderDetails["orderId"] ?>"/>
    <input type="hidden" name="orderAmount" value="<?php echo $orderDetails["orderAmount"] ?>"/>
    <input type="hidden" name="orderCurrency" value="<?php echo $orderDetails["orderCurrency"] ?>"/>
    <input type="hidden" name="orderNote" value="<?php echo $orderDetails["orderNote"] ?>"/>
    <input type="hidden" name="customerName" value="<?php echo $orderDetails["customerName"] ?>"/>
    <input type="hidden" name="customerEmail" value="<?php echo $orderDetails["customerEmail"] ?>"/>
    <input type="hidden" name="customerPhone" value="<?php echo $orderDetails["customerPhone"] ?>"/>
    <input type="hidden" name="returnUrl" value="<?php echo $orderDetails["returnUrl"] ?>"/>
    <input type="hidden" name="notifyUrl" value="<?php echo $orderDetails["notifyUrl"] ?>"/>
    <input type="hidden" name="signature" value="<?php echo $orderDetails["signature"] ?>"/>
  </form>

  <script>document.getElementById("redirectForm").submit();</script>

<?php


function getUserDetails($orderId) {
    return array(
      "customerName" => "raghul",
      "customerEmail" => "raghulrage@gmail.com",
      "customerPhone" => "1111111111"
    );
}

function getOrderDetails($orderId) {
  return array(
    "orderId" => time(),
    "orderAmount" => $_POST["amount"],
    "orderNote" => "test order",
    "orderCurrency" => "INR"
  );
}



 ?>
