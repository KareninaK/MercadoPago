<?php
require ('config.php');
require_once 'vendor/autoload.php';
//require ('detail.php');

MercadoPago\SDK::setAccessToken(access_token);
MercadoPago\SDK::setIntegratorId("dev_24c65fb163bf11ea96500242ac130004");

$payment_methods = MercadoPago\SDK::get("/v1/payment_methods");

$description        =filter_input(INPUT_POST,'titulo');
//$precio             =filter_input(INPUT_POST,'precio');
$precio = $_POST['price'];
$token              =filter_input(INPUT_POST,'token');
$imagen              =filter_input(INPUT_POST,'imagen');

 $payment = new MercadoPago\Payment();
    $payment->transaction_amount = $precio;
    $payment->token = $token;
    $payment->description = $description;
    $payment->installments = 6;

    $payment->payer = array(
    "id" => 471923173,
    "name" => "Lalo",
    "surname" => "Landa",
    "email" => "test_user_63274575@testuser.com",
    "phone" => array( "area_code" => "11","number" => "22223333"),
    "identification" => array("type" => "DNI", "number" => "12345678"),
    "address" => array("street_name" => "False","street_number" => 123,"zip_code" => "1111")
	);
    
    $payment->save();
	
	$preference = new MercadoPago\Preference();
    
// Crea un ítem en la preferencia
    $item = new MercadoPago\Item();
    //$item->titulo = $description;
    $item->id = 1234;
    $item->title = $description;
    $item->quantity = 1;
    $item->unit_price = $precio;
    $item->image = $imagen;
    $item->description = "Dispositivo movil de Tienda e-commerce" ;
    $preference->items = array($item);
    
    $preference->payment_methods = array("excluded_payment_methods" => array(array("id" => "amex")),
    	"excluded_payment_types" => array(array("id" => "atm")),
    	"installments" => 6);
    
    $preference->external_reference = "kareninakauffmann1989@gmail.com.ar";

    $preference->back_urls = array('success' => 'http://localhost/proyectosK/MP/ecommerce/index.php', 'pending' => 'http://localhost/proyectosK/MP/ecommerce/detail.php', 'failure' => 'https://www.mercadolibre.com.ar') ;
    $preference->auto_return = "approved";

    $preference->notification_url = "https://0cffd0c1c9a0f22a210e6525c53365a7.m.pipedream.net";

    $preference->save();

	//echo '<pre>',print_r($payment),'</pre>';
    echo $preference->init_point;
    
?>