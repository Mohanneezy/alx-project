<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='alert alert-danger'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecomm";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// sql to delete a record
$sql = "DELETE FROM cart";

if ($conn->query($sql) === TRUE) {

} else {
  echo "Error deleting record: " . $conn->error;
}
$conn->close();
	        		?>
	        		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		              <h1>thank you for shopping with us </h1>
					  <?PHP
$order_id=$_GET['orderid'];
$username = "merchant.010000100123";
$password = "1c032382c19d1c7d8a17ec4ac29cc02a";
$remote_url = 'https://test-gateway.mastercard.com/api/rest/version/63/merchant/010000100123/order/'.$order_id;

// Create a stream
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header' => "Authorization: Basic " . base64_encode("$username:$password")                 
  )
);

$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
$file = file_get_contents($remote_url, false, $context);
$array=explode(',',$file);
$status=$array[15];
$order_id=$array[9];
$amount=$array[0];
$discription=$array[53];
//var_dump($array);

?>
	<table class="table table-bordered">
		        			<thead>
		        				<th></th>
		        				<tr><?php echo str_replace('"', '', $status).'<br>';?></tr>
								<tr><?php echo str_replace('"', '', $order_id).'<br>';?></tr>
								<tr><?php echo str_replace('"', '', $amount).'<br>';?></tr>
								<tr><?php echo str_replace('"', '', $discription).'<br>';?></tr>
		        			</thead>
		        			<tbody id="tbody">
		        			</tbody>
		        		</table>
					  
		                <div class="carousel-inner">
		                <form action="index.php" class="paymentWidgets">
						<button>Continue shopping</button>
						</form>
		                </div>
		                
		            </div>
		         
		       		<?php
		       			$month = date('m');
		       			$conn = $pdo->open();

		       			try{
		       			 	$inc = 3;	
						    $stmt = $conn->prepare("SELECT *, SUM(quantity) AS total_qty FROM details LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN products ON products.id=details.product_id WHERE MONTH(sales_date) = '$month' GROUP BY details.product_id ORDER BY total_qty DESC LIMIT 6");
						    $stmt->execute();
						    foreach ($stmt as $row) {
						    	$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
						    	$inc = ($inc == 3) ? 1 : $inc + 1;
	       						if($inc == 1) echo "<div class='row'>";
	       						echo "
	       							<div class='col-sm-4'>
	       								<div class='box box-solid'>
		       								<div class='box-body prod-body'>
		       									<img src='".$image."' width='100%' height='230px' class='thumbnail'>
		       									<h5><a href='product.php?product=".$row['slug']."'>".$row['name']."</a></h5>
		       								</div>
		       								<div class='box-footer'>
		       									<b>&#36; ".number_format($row['price'], 2)."</b>
		       								</div>
	       								</div>
	       							</div>
	       						";
	       						if($inc == 3) echo "</div>";
						    }
						    if($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>"; 
							if($inc == 2) echo "<div class='col-sm-4'></div></div>";
						}
						catch(PDOException $e){
							echo "There is some problem in connection: " . $e->getMessage();
						}

						$pdo->close();

		       		?> 
	        	</div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
<