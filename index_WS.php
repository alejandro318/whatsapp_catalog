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

//print_r($cats);

?>


<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Catálogo Toys 4 us</title>
		<meta name="description" content="Catálogo Toys 4 us" />
		<meta name="keywords" content="" />
		<meta name="author" content="Pragmasys" />
		<link rel="shortcut icon" href="favicon.ico"> 
		<link rel="stylesheet" type="text/css" href="css/jquery.jscrollpane.custom.css" />
		<link rel="stylesheet" type="text/css" href="css/bookblock.css" />
		<link rel="stylesheet" type="text/css" href="css/custom.css" />

		<script src="js/modernizr.custom.79639.js"></script>
		
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    
 		
   
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="fonts/material-icons.min.css">   
	</head>
	<body>
<!--Modal carrito-->
<div class="modal fade" id="carritoModal" tabindex="-1" role="dialog" aria-labelledby="carritoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="carritoModalLabel">Carrito de compras</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="table-responsive" id="Productos">
						<table class="table" id='productsTable'>
								
								<thead>
								
										<tr id='body_caption'>
											<td>Producto</td>
											<td  colspan=2>Cantidad /Precio</td>
										</tr>
										
								</thead>
								
								<tbody>
								</tbody>
						</table>
					</div>
      </div>
      <div class="modal-footer1">
<!--        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
        <button type="button" id="envia-pedido"      class="btn btn-primary" data-dismiss="modal"style="background-color:#069; font-size:11px;"  data-toggle="modal" data-target="#enviarPedidoModal"  data-toggle="modal"  ><i class="fa fa-check" "></i>  Hacer pedido</button>
       
        <button type="button" id="limpia-carrito" class="btn btn-primary" style="background-color:#069; font-size:11px;" data-toggle="modal" data-target="#carritoModal" ><i class="fa fa-cart-arrow-down"></i>  Vaciar carrito</button>
      </div>
    </div>
  </div>
</div>
<!--Modal carrito-->	

<!--Modal producto-->
<div class="modal fade productoModal" id="productoModal" tabindex="-1" role="dialog" aria-labelledby="productoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productoModalLabel">Agregar producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<div class="table-responsive">
					<div class="modal-body1">
				  
						<table class="table">
								<thead>
										<tr></tr>
								</thead>
								<tbody>
										<tr>
												<td class="col-3" id="productName" name="productName">---</td>
												<!--<td class="col-4" ><input type="number"  id="productQty" name="productQty" value="1" class="col-4" size="50" /></td>-->
												<td class="col-4" id="productPrice">$00.00</td>
										</tr>							
								</tbody>
						</table>
					</div>
			  </div>
      </div>
      <div class="modal-footer1">
<!--        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
        <input name="productSku" class="productSku" id="productSku" hidden> 
        <button type="button" id="agregar-producto"  class="btn btn-primary" data-dismiss="modal" style="background-color:#069; font-size:11px;"><i class="fa fa-cart-plus"></i> Confirmar</button>
      </div>
    </div>
  </div>
</div>
<!--Modal producto-->	

<!--Modal Accion-->
<div class="modal fade accionModal" id="accionModal" tabindex="-1" role="dialog" aria-labelledby="accionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="accionModalLabel">Listo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
					Producto agregado
      </div>
      <div class="modal-footer1">


      </div>
    </div>
  </div>
</div>
<!--Modal Accion-->	
<!--Modal Enviar Pedido-->
<form name="EnviaWA" name="EnviaWA" class="needs-validation">
<div class="modal fade enviarPedidoModal" id="enviarPedidoModal" tabindex="-1" role="dialog" aria-labelledby="enviarPedidoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="enviarPedidoModalLabel">Confirmación de pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	Ingrese su correo y su número telefónico
      </div>
      <div class="modal-footer1">
      <div class="form-group">
      	<input class="form-control " type="email" name="email" id="email" placeholder="Email" required />
      </div>
      <div class="form-group">
      
	      <input class="form-control" type="phone" name="telefono" id="telefono" placeholder="Teléfono Celular a 10 dígitos" required/>
			</div>
      <br>
              <button type="submit"  id="envia-pedido-wa" class="btn btn-primary" style="background-color:#069; font-size:11px;"><i class="fa fa-check"></i>  Hacer pedido</button>      
      </div>
    </div>
  </div>
</div>
</form>
<!--Modal Accion-->	

		<div id="container" class="container2">	
				
			<div class="menu-panel">
				<!--<img src="imgs/logo.png" width=240>-->
				<h3>Menú</h3>
				<ul id="menu-toc" class="menu-toc">
<?
$i=0;
foreach ($cats as $id => $cat)
{
?>
					<li id=<? echo $id ?>><a href="#item<? echo $id ?>"><? echo $cat ?></a></li>
<?
$i++;
}
?>					
				</ul>
			</div>

			<div class="bb-custom-wrapper">
				<div id="bb-bookblock" class="bb-bookblock">
				  <div class='header-title'>
				    <img src="imgs/logo.png" width="200" class="img-fluid d-inline-flex">
				  </div>
				
				<div>
					<nav class='header-content'>
							<i id="tblcontents" class="fas fa-bars fa-2x" style="color:#069" ></i>
							<i id="bb-nav-prev" class="fas fa-arrow-circle-left fa-2x" style="color:#069;"></i>
							<i id="bb-nav-next" class="fas fa-arrow-circle-right fa-2x" style="color:#069;"></i>
							<!--<i  class="fas fa-download fa-2x" style="color:#069;" id="descarga-catalogo" ></i>-->					
							<i  class="open-carritoModal fas fa-shopping-cart fa-2x" style="color:#069;"  data-toggle="modal" data-target="#carritoModal" ></i>
					</nav>
				</div>
<?
for ($i=0 ; $i < count($product); $i++)
{
  $SellerSku = $product[$i]["ShopSku"];
	$Name = $product[$i]["Name"];
	$SalePrice = $product[$i]["SalePrice"];
	$Price = $product[$i]["Price"];
	$MainImage = $product[$i]["MainImage"];
	$Description = $product[$i]["Description"];
	$PrimaryCategory = $product[$i]["PrimaryCategory"];
?>

					<div class="bb-item" id="item2">
						<div class="content">
							<div class="scroller">
								<h4><? echo strip_tags($Name)?></h4>
								<div class ='price'>Categoría: <? echo strip_tags($PrimaryCategory)?></div>
								<img   class="img-fluid d-inline-flex"  src="<? echo $MainImage ?>"><br>
								<?
								
								//if($SalePrice < $Price)
								if(0)
								{
								?>
								<div class ='price'><strike>Precio: <? echo "$ ".strip_tags($Price)?></strike></div>
								<?
								}
								?>
								<div class ='final-price'>Precio: <? echo "$ ".strip_tags($SalePrice)?></div>
																
                <div class="catalogo-footer">
								  	<button class="open-productoModal btn btn-primary active pull-right" type="button" style="background-color:#069; font-size:11px;"  
								  	data-sku="<? echo $SellerSku?>"
								  	data-name="<? echo $Name?>"
								  	data-price="<? echo $SalePrice?>"
								  	data-qty="<? echo 1?>" 
								  	data-toggle="modal" data-target="#productoModal" ><i class="fa fa-cart-plus"></i> Agregar al carrito</button>
								</div><br>
								
								<p><? //echo $Description ?></p>
							</div>
						</div>					
					</div>
<?			
}
//data-toggle="modal" data-target="#productoModal"
?>
				
					

						
					
																	
			</div>
			
		</div>

		
		<!-- /container -->

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

		<script src="js/jquery.mousewheel.js"></script>
		<script src="js/jquery.jscrollpane.min.js"></script>
		<script src="js/jquerypp.custom.js"></script>
		<script src="js/jquery.bookblock.js"></script>
		<script src="js/page.js"></script>
			<script>
			
			//Show carrito Model
			$(document).on("click", ".open-carritoModal", function () {

          var products ="";					
          //Get  Data from basket
		          $.get("sessCarrito.php?accion=Print", function(data, status){

							if(status == "success")
			 			  {
			 			    if (data.total.numProds >0)
			 			    {
				 			    products+="<table class='table' id='productsTable'><thead><tr id='body_caption'><td>Producto</td><td  colspan=2>Cantidad /Precio</td></tr></thead><tbody>";
				 			 	  for (const [key, value] of Object.entries(data.products))
				 			 	  {
										console.log(key);
										console.log(value.MainImage);
										console.log(value.Name);
										console.log(value.SalePrice);

										products +='<tr ><td rowspan=2><img src="'+value.MainImage+'" width="100"></td><td colspan=2>'+value.Name+'</td></tr><tr><td>';
										products +='<button data-sku='+key+'  id="bb-nav-next"  class="fas add-producto fa-plus-circle" style="color:#069;" ></button>';
										products +=' '+value.qty+' <button data-sku='+key+'  id="bb-nav-next"  class="fas del-producto fa-minus-circle" style="color:#069;"></button></td><td>$ '+value.SalePrice+' </td></tr>';		 
									}
				 		      products+='<tr><td><b>Total</b></td><td></td><td><b>$ '+data.total.total+'</b></td></tr>';
				 		      products+="</tbody></table>";
				 		    }
			 			  }   			  
			 		    document.getElementById("Productos").innerHTML=products; 
  			});

   		  
			});
			
			

			
			
			
			
			
			$( "#limpia-carrito" ).click(function() {
			

				$.get("sessCarrito.php?accion=Clean", function(data, status){
   			  //alert("Data: " + data + "\nStatus: " + status);
   			  if(status == "success")
   			  {
   			 	  alert("Todos los productos fueron eliminados");
   			 	  
   			 	  //Get  Data from basket
		          $.get("sessCarrito.php?accion=Print", function(data, status){
			 			  var products = "";
							if(status == "success")
			 			  {
			 			    if (data.total.numProds >0)
			 			    {
				 			    products+="<table class='table' id='productsTable'><thead><tr id='body_caption'><td>Producto</td><td  colspan=2>Cantidad /Precio</td></tr></thead><tbody>";
				 			 	  for (const [key, value] of Object.entries(data.products))
				 			 	  {
										console.log(key);
										console.log(value.MainImage);
										console.log(value.Name);
										console.log(value.SalePrice);

										products +='<tr ><td rowspan=2><img src="'+value.MainImage+'" width="100"></td><td colspan=2>'+value.Name+'</td></tr><tr><td>';
										products +='<button data-sku='+key+'  id="bb-nav-next"  class="fas add-producto fa-plus-circle" style="color:#069;" ></button>';
										products +=' '+value.qty+' <button data-sku='+key+'  id="bb-nav-next"  class="fas del-producto fa-minus-circle" style="color:#069;"></button></td><td>$ '+value.SalePrice+' </td></tr>';		 
									}
				 		      products+='<tr><td><b>Total</b></td><td></td><td><b>$ '+data.total.total+'</b></td></tr>';
				 		      products+="</tbody></table>";
				 		    }
			 			  }   			  
			 		    document.getElementById("Productos").innerHTML=products;
   			 
  					});
  				} 
				});
			});
			
			//Agregar product Model
			$(document).on("click", ".open-productoModal", function () {

				var myName = $(this).data('name');
				var myQty = $(this).data('qty');
				var myPrice = $(this).data('price');
				var mySku = $(this).data('sku');
				$(".productoModal #productName").html( myName );
				$(".productoModal #productQty").val( myQty );
				$(".productoModal #productPrice").html( myPrice );
				$(".productoModal #productSku").val( mySku );
				
				// As pointed out in comments, 
				// it is unnecessary to have to manually call the modal.
				//$('#productoModal').show();


			});
			
			//Agregar product Model
			$(document).on("click", ".add-producto", function () {

				var mySku = $(this).data('sku');

				$.get("sessCarrito.php?accion=Add&qty=1&sku="+mySku, function(data, status){   			            			
					if(status == "success")
   			  {
   			      
   			     alert("Producto agregado"); 
   			     
   			      //Get  Data from basket
		          $.get("sessCarrito.php?accion=Print", function(data, status){
			 			  var products = "";
							if(status == "success")
			 			  {
			 			    if (data.total.numProds >0)
			 			    {
				 			    products+="<table class='table' id='productsTable'><thead><tr id='body_caption'><td>Producto</td><td  colspan=2>Cantidad /Precio</td></tr></thead><tbody>";
				 			 	  for (const [key, value] of Object.entries(data.products))
				 			 	  {
										console.log(key);
										console.log(value.MainImage);
										console.log(value.Name);
										console.log(value.SalePrice);

										products +='<tr ><td rowspan=2><img src="'+value.MainImage+'" width="100"></td><td colspan=2>'+value.Name+'</td></tr><tr><td>';
										products +='<button data-sku='+key+'  id="bb-nav-next"  class="fas add-producto fa-plus-circle" style="color:#069;" ></button>';
										products +=' '+value.qty+' <button data-sku='+key+'  id="bb-nav-next"  class="fas del-producto fa-minus-circle" style="color:#069;"></button></td><td>$ '+value.SalePrice+' </td></tr>';		 
									}
				 		      products+='<tr><td><b>Total</b></td><td></td><td><b>$ '+data.total.total+'</b></td></tr>';
				 		      products+="</tbody></table>";
				 		    }
			 			  }   			  
			 		    document.getElementById("Productos").innerHTML=products;
		 		     
						});
   			  }
   			});
				
			});
			
			$(document).on("click", ".del-producto", function () {

				var mySku = $(this).data('sku');
				$.get("sessCarrito.php?accion=Upd&qty=1&sku="+mySku, function(data, status){   			            			
					if(status == "success")
   			  {
   			      
   			     alert("Producto eliminado"); 
   			     
   			     //Get  Data from basket
		        $.get("sessCarrito.php?accion=Print", function(data, status){
							var products = "";
							if(status == "success")
			 			  {
			 			    if (data.total.numProds >0)
			 			    {
				 			    products+="<table class='table' id='productsTable'><thead><tr id='body_caption'><td>Producto</td><td  colspan=2>Cantidad /Precio</td></tr></thead><tbody>";
				 			 	  for (const [key, value] of Object.entries(data.products))
				 			 	  {
										console.log(key);
										console.log(value.MainImage);
										console.log(value.Name);
										console.log(value.SalePrice);

										products +='<tr ><td rowspan=2><img src="'+value.MainImage+'" width="100"></td><td colspan=2>'+value.Name+'</td></tr><tr><td>';
										products +='<button data-sku='+key+'  id="bb-nav-next"  class="fas add-producto fa-plus-circle" style="color:#069;" ></button>';
										products +=' '+value.qty+' <button data-sku='+key+'  id="bb-nav-next"  class="fas del-producto fa-minus-circle" style="color:#069;"></button></td><td>$ '+value.SalePrice+' </td></tr>';		 
									}
				 		      products+='<tr><td><b>Total</b></td><td></td><td><b>$ '+data.total.total+'</b></td></tr>';
				 		      products+="</tbody></table>";
				 		    }
			 			  }   			  

			 		    document.getElementById("Productos").innerHTML=products;
		 		     
						});
   			  }
   			});
				
			});			
			
			$( "#envia-pedido-wa" ).click(function() {
			
			
				var forms = document.getElementsByClassName('needs-validation');
				// Loop over them and prevent submission
				var validation = Array.prototype.filter.call(forms, function(form) {
				form.addEventListener('submit', function(event) {
				if (form.checkValidity() === false) {
					event.preventDefault();
					event.stopPropagation();
				  
					
				}
				else
				{
					event.preventDefault();
					event.stopPropagation();
          var email = $("#email").val();
				  var telefono = $("#telefono").val();
				  
					$.get("sessCarrito.php?accion=Print&send=1&email="+email+"&telefono="+telefono, function(data, status){
						//alert("Data: " + data + "\nStatus: " + status);
						if(status == "success")
						{
					 		console.log(data.texto);
					 		prodDPagina = data.texto;
					 		var newwindow=window.open("https://api.whatsapp.com/send?phone=+525562868190&text="+encodeURI(prodDPagina)+"","Compartir ","height=400,width=500"); 
						}   
					});
				}
				//form.classList.add('was-validated');
				}, false);
				});

				var prodDPagina = "";

			});
			
			
			
			$( "#agregar-producto" ).click(function() {
			
				var mySku = $('#productSku').val();				
				var myQty = $('#productQty').val();
				console.log(mySku);
				console.log(myQty);
				//Add product to basket
				
				$.ajax({
                    type: 'GET',
                    url: 'sessCarrito.php?accion=Add&sku='+mySku+'&qty='+myQty,
                    
                    dataType: 'json',
                    success: function(d) {
                        alert( "Producto agregado" );
                       
                    }
          }); 
				
			});

			

			$( "#descarga-catalogo" ).click(function() {

			var newwindow=window.open("toys4us.pdf","Compartir ","height=400,width=500");


			});
			
			var myModal = $('#myModal').on('shown', function () {
		  clearTimeout(myModal.data('hideInteval'))
		  var id = setTimeout(function(){
		      myModal.modal('hide');
		  });
		  myModal.data('hideInteval', id);
			})

			$(function() {

				Page.init();
			

			});
		</script>
	</body>
</html>
