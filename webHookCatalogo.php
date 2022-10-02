<?php
//header('Content-Type: application/json');
// Pay no attention to this statement.
// It's only needed if timezone in php.ini is not set correctly.
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

//Concatenate the sorted and URL encoded parameters into a string.
$concatenated = implode('&', $encoded);

// The API key for the user as generated in the Seller Center GUI.
// Must be an API key associated with the UserID parameter.
$api_key = 'd2c690e98958353f905005a3b5153354725e3209';

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


// Close Curl connection
curl_close($ch);


$JSONData = json_decode($data, true);
//print_r( $JSONData["SuccessResponse"]["Body"]["Products"]["Product"]);
$product = $JSONData["SuccessResponse"]["Body"]["Products"]["Product"];
$categorias = array();

$catPermitidas[]="Figuras de Accion";
$catPermitidas[]="Muñecas coleccionables";
$catPermitidas[]="Juegos de Mesa";
$catPermitidas[]="Coches de control remoto";
$catPermitidas[]="Juguetes Didácticos";
$catPermitidas[]="Personajes Infantiles de Peluche";
$catPermitidas[]="Juguetes y Entretenimiento para Bebés"; 
$catPermitidas[]="Lanzadores";
$catPermitidas[]="Secadoras";
$catPermitidas[]="Plastilina";
$catPermitidas[]="Juegos de viaje y de bolsillo";
$catPermitidas[]="Muñecas Bebé";
$catPermitidas[]="Carriolas, Cunas y Sillas para Muñecas";
$catPermitidas[]="Camionetas Eléctricas para Niños";
$catPermitidas[]="Cocinas para Jugar";
$catPermitidas[]="Juguetes para Otras Profesiones";
$catPermitidas[]="Lanzadores De Agua";
$catPermitidas[]="Coches y Pistas de Colección";
$catPermitidas[]="Fashion Dolls";
$catPermitidas[]="Juegos de Construcción";
/*
	[0] => Figuras de Accion
	[2] => Muñecas coleccionables
Peluches Interactivos  
	[4] => Juegos de Mesa
	[5] => Coches de control remoto
Herramientas Médicas para Jugar
Coleccionables
	[17] => Juguetes Didácticos
	[37] => Personajes Infantiles de Peluche
	[39] => Juguetes y Entretenimiento para Bebés
Juegos Deportivos
Juguetes y Juegos
	[93] => Lanzadores
Juguetes de Musica y Sonido para Bebés
Sonajas
Centros de Actividades para Bebes
Formas y Colores
	[103] => Secadoras
	Plastilina
Bloques para Bebés
Gimnasios y Ejercitadores para Bebes
Sets de Juguetes para Regalos
Jabones y Géles Antibacteriales
Playsets de Colecionables y Vehiculos
	[159] => Juegos de viaje y de bolsillo
	[164] => Muñecas Bebé
	[176] => Carriolas, Cunas y Sillas para Muñecas
Juguetes Novedosos
	[201] => Camionetas Eléctricas para Niños
	[205] => Cocinas para Jugar
Juguetes de miniatura
	[211] => Juguetes para Otras Profesiones
Programas y Libros Electrónicos
Modelado y escultura
Juguetes Didácticos de Animales y Habitats
	[234] => Lanzadores De Agua
Plastilina Preescolares
Máscaras para Disfraces
	[270] => Coches y Pistas de Colección
Artículos para el Cuidado de los pies
	[286] => Fashion Dolls
	[287] => Juegos de Construcción
Otras tarjetas de memoria
Mascarillas de seguridad
Iluminación
*/


//print_r($catPermitidas);

for ($i = 0 ; $i < count($product); $i++)
{
  if ( in_array(trim($product[$i]["PrimaryCategory"]),$catPermitidas) )
  {
		$categorias[] =  $product[$i]["PrimaryCategory"];
  }
}
//print_r($product[0]);
//echo $i;
$cats = array_unique($categorias);

$link = mysql_connect('toys4us.com.mx', 'toys4us', '');
mysql_select_db('catalogo_toys4us', $link);

$SQL = "truncate table catalogo";
$query = mysql_query($SQL);

for ($i=0 ; $i < count($product); $i++)
{


  if ( in_array(trim($product[$i]["PrimaryCategory"]),$catPermitidas) )
  {
  
		$SellerSku = $product[$i]["ShopSku"];
		$Name = $product[$i]["Name"];
		$SalePrice = $product[$i]["SalePrice"];
		$Price = $product[$i]["Price"];
		$MainImage = $product[$i]["MainImage"];
		$Description = "";//$product[$i]["Description"];
		$PrimaryCategory = $product[$i]["PrimaryCategory"];
		
		$SQL = "INSERT INTO catalogo(
		  ShopSku,
			Name,
			SalePrice,
			Price,
			MainImage,
			Description,
			PrimaryCategory
			) 
		VALUES (
			'".$SellerSku."',
			'".$Name."',
			'".$SalePrice."',
			'".$Price."',
			'".$MainImage."',
			'".$Description."',
			'".utf8_decode($PrimaryCategory)."'
			
		);";
		$SQL;
		$query = mysql_query($SQL);	
  }
	
}




?>
