<?php
session_start();
include("includes/funcion.php");
header('Content-Type: application/json');

$sku = $_GET["sku"];
$qty = $_GET["qty"];
$prod = $_SESSION["productos"];


if($_GET["accion"] == "Add")
{
  if( strlen($prod[$sku]["qty"]) )
  {
  	$prod[$sku]["qty"] += 1;
  }
  else
  {
  	$prod[$sku]["qty"] = 1;
  }
   
}
else
if($_GET["accion"]== "Upd")
{
  if( isset($prod[$sku]) )
  {
  	$prod[$sku]["qty"] -= 1;
  	if($prod[$sku]["qty"] ==0)
  	{
  	  unset($prod[$sku]);
  	}
  }
}
else
if($_GET["accion"] == "Del")
{
 	unset($prod[$sku]);
}
else
if($_GET["accion"] == "Clean")
{
  session_destroy();
}
else
if($_GET["accion"] == "Print")
{
  if(count($prod) > 0)
  {
		foreach($prod as $skus => $producto)
		{

			date_default_timezone_set("UTC");

			// The current time. Needed to create the Timestamp parameter below.
			$now = new DateTime();

			// The parameters for our GET request. These will get signed.
			$parameters = array(
					// The user ID for which we are making the call.
					'UserID' => 'alejandro318@gmail.com',

					// The API version. Currently must be 1.0
					'Version' => '1.0',

					// The API method to call.
					'Action' => 'GetProducts',
					'Filter' => 'active',
					//'SkuSellerList' => $skuList,
					'Search' => $skus,

					// The format of the result.
					'Format' => 'JSON',

					// The current time formatted as ISO8601
					'Timestamp' => $now->format(DateTime::ISO8601)
			);

			// Sort parameters by name.
			ksort($parameters);

			// URL encode the parameters.
			$encoded = array();
			foreach ($parameters as $name => $value) {
					$encoded[] = rawurlencode($name) . '=' . rawurlencode($value);
			}

			// Concatenate the sorted and URL encoded parameters into a string.
			$concatenated = implode('&', $encoded);

			// The API key for the user as generated in the Seller Center GUI.
			// Must be an API key associated with the UserID parameter.
			$api_key = '';

			// Compute signature and add it to the parameters.
			$parameters['Signature'] =
					rawurlencode(hash_hmac('sha256', $concatenated, $api_key, false));
					
					
			// ...continued from above

			// Replace with the URL of your API host.
			$url = "https://sellercenter-api.linio.com.mx/";

			// Build Query String
			$queryString = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);

			// Open cURL connection
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url."?".$queryString);

			// Save response to the variable $data
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$data = curl_exec($ch);

			//echo $data;
			// Close Curl connection
			curl_close($ch);


			$JSONData = json_decode($data, true);
			//print_r($JSONData["SuccessResponse"]["Body"]["Body"]["Products"]);
			$product = $JSONData["SuccessResponse"]["Body"]["Products"]["Product"];
		  //echo json_encode($product);
		  
		  $productDetail["SellerSku"] = $product["SellerSku"];
		  $productDetail["ShopSku"] = $product["ShopSku"];
			$productDetail["Name"] = $product["Name"];
			$productDetail["SalePrice"] = $product["SalePrice"];
			$productDetail["MainImage"] = $product["MainImage"];
			$productDetail["qty"] = $producto["qty"];
			$productList[$skus] = $productDetail;
			//$Description = $product[$i]["Description"];
			//$PrimaryCategory = $product[$i]["PrimaryCategory"];
		}
	}
  $products["products"] = $productList;
  
  $total=0;
  $numProds=0;
  
  
  $texto = "Hola, te mando mi lista de productos que encontré en el catálogo Toys 4 us que me gustaría adquirir:\n\n";
  if(count($productList)> 0 )
  {
		foreach ($productList as $sku => $prodDetail)
		{
		  $texto .= $prodDetail["SellerSku"]." - ".$prodDetail["Name"]." - ".$prodDetail["qty"]." - $".$prodDetail["SalePrice"]."\n\n";
			$total += ($prodDetail["SalePrice"] * $prodDetail["qty"]);
			$numProds += $prodDetail["qty"];
		}
	}
	
	$texto .="\n\nTotal: $".$total." \n\n";
	$texto .="\nTeléfono: ".$_GET["telefono"]."\n";
	$texto .="\nEmail: ".$_GET["email"]."\n";
	$texto .="¡Gracias! ";
	$textoEmail = str_replace("\n","<br>",$texto);
		
  $products["total"] = array("numProds"=>$numProds,"total"=>$total);
	$products["texto"] = ($texto);
	
	if ($_GET["send"]==1)
	{
	  sendEMail("Usuario","Nuevo pedido Toys4us","rakxel@gmail.com",$textoEmail);
	}
	
	$json = json_encode($products);
	echo $json;

}

$_SESSION["productos"] = $prod;
?>
