<?php
function request() {
include 'includes/session.php';

	if(isset($_SESSION['user'])){
		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products on products.id=cart.product_id WHERE user_id=:user_id");
		$stmt->execute(['user_id'=>$user['id']]);

		$total = 0;
		foreach($stmt as $row){
			$subtotal = $row['price'] * $row['quantity'];
			$total += $subtotal;

		}
		$pdo->close();
	}
	$order_id=rand();
	$url = "https://test-gateway.mastercard.com/api/nvp/version/63";
	$data = "apiOperation=INITIATE_CHECKOUT" .
           "&apiPassword=1c032382c19d1c7d8a17ec4ac29cc02a" .
           "&apiUsername=merchant.010000100123" .
		   "&merchant=010000100123" .
           "&interaction.operation=PURCHASE" .
		   "&interaction.returnUrl=http://10.10.12.133/Ecommerce/result.php?orderid=$order_id" .
		   "&order.id=$order_id" .
           "&order.amount=$total" .
           "&order.currency=USD";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if(curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);
	return $responseData;
}
$responseData = request();
$session_id=explode("=",explode("&",$responseData)[3])[1];

?>
<html>
    <head>
        <script src="https://test-gateway.mastercard.com/static/checkout/checkout.min.js" data-error="errorCallback" data-cancel="cancelCallback"
		data-error="errorcallback"
		data-cancel="http://localhost/Ecommerce/index.php"
		></script>
		
        <script type="text/javascript">
            function errorCallback(error) {
                  console.log(JSON.stringify(error));
				  //window.location.href="http://10.10.13.133/Ecommerce/index.php";
            }
			function cancelCallback() {
                  console.log('Payment cancelled');
				  window.location.href="http://10.10.12.133/Ecommerce/cart_view.php";
            }
		Checkout.configure({
			merchant :'010000100124',
			session: { 
            	id: '<?php echo $session_id;?>'
       			},
              interaction: {
                    merchant: {
                        name: 'Harmony Hotel',
                        address: {
                            line1: '200 Sample St',
                            line2: '1234 Example Town'            
                        }    
                    },
					
					
               },
			   
			    order: {
					description:'online shopping with MPGS',
					id:'145'
               },
			  
            });
			Checkout.showPaymentPage();
        </script>
    </head>
    <body>
    </body>
</html>
