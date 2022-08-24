<?php
$produccion=false;
$amatech=true;

$referer = "";
$domain['host'] ="";

if($domain['host']=="contrata.telmex.com" || $domain['host']=="telmex.com"|| $domain['host']=="mitelmex.telmex.com"){
	$GLOBALS['produccion'] =true;
}

if($produccion){
	require_once("/home/apcomercial/htdocs/dev/tomapedido/connect.php");
}else{
	require_once ('/xampp64/htdocs/dev/tomapedido/connect.php');
}

$datos =null;
$allowedDomains = array('telmex.com', 'lfpre.telmex.com', 'contrataciones.intranet.telmex.com', 'contrata.telmex.com', 'contratastg.telmex.com', 'telmexstg.telmex.com');
$allowedIPs = array('10.105.124.106','10.105.124.3','10.105.124.2','10.105.124.5','10.105.124.21','201.116.244.176', '10.105.124.9', '10.105.124.87', '10.108.156.61', '10.248.201.18', '10.248.201.168', '10.105.124.87', '10.105.124.21','148.233.37.49');
// GlobalQuark Acceso QA @AB 220603
array_push($allowedIPs, "10.241.232.111", "10.241.232.112", "10.241.232.113", "10.241.232.114", "10.241.232.115", "10.241.232.116", "10.241.232.117", "10.241.232.118");
array_push($allowedDomains, "telmexstaging.telmex.com", "tmx7.telmex.com");
$allowedIPs = array();

$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);

$base='contrataciones_pyme';
$Connect_sind = new Connect_sind();
$con=null;
$post = file_get_contents("php://input");

if(isset($_SERVER['HTTP_REFERER'])) {
	$referer = $_SERVER['HTTP_REFERER'];
	$domain = parse_url($referer); //If yes, parse referrer
}else{
	$domain['host']="vacio";
}


//if(in_array( $ip, $allowedIPs) || in_array( $domain['host'], $allowedDomains) ) {
	//if(isset($HTTP_RAW_POST_DATA)){
	if(isset($post)){
		//$GLOBALS['datos'] = $HTTP_RAW_POST_DATA;
		$GLOBALS['datos'] = $post;
		$datos= json_decode($GLOBALS['datos']);
		if($datos!=""){
			$GLOBALS['con']=$GLOBALS['Connect_sind'] -> conectar($GLOBALS['base']);
			switch ($_REQUEST["op"]){
				case "in":
					insertNewRecord();
				break;
				case "obtr":
					obtenerRegistros();
				break;
			}
			
			$GLOBALS['con'] -> close();
		}else{
			echo json_encode(array('error'=>array("codigo"=>"1004", "descripcion"=>"error", "descTecnica"=>"empty")));
		}
	}else{
		echo json_encode(array('error'=>array("codigo"=>"1001", "descripcion"=>"error", "descTecnica"=>"Servicio Arriba")));
	}
/*}else{
	echo json_encode(array('error'=>array("codigo"=>"1002", "descripcion"=>"Error", "descTecnica"=>"Unathorized DOMINIO ". $domain['host'])));
}*/

function insertNewRecord(){
	$obj=$GLOBALS['datos'];
	if($obj!=""){
		try{

			$nombre				=	isset($obj->nombre) ? $obj->nombre : "";
			$apaterno			=	isset($obj->apaterno) ? $obj->apaterno : "";
			$teltelmex			=	isset($obj->teltelmex) ? $obj->teltelmex : "";
			$dominio			=	isset($obj->dominio) ? $obj->dominio : "";
			$empleado			=	isset($obj->empleado) ? $obj->empleado : "";
			$canalcontrata		=	isset($obj->canalcontrata) ? $obj->canalcontrata : "";
			$telefono			=	isset($obj->telefono) ? $obj->telefono : "";
			$email				=	isset($obj->email) ? $obj->email : "";
			$paginaweb			=	isset($obj->paginaweb) ? $obj->paginaweb : "";
			$mercado			=	isset($obj->mercado) ? $obj->mercado : "";
			$empresa			=	isset($obj->empresa) ? $obj->empresa : "";
			$cuentamaestra		=	isset($obj->cuentamaestra) ? $obj->cuentamaestra : "";
			$horariocontacto	=	isset($obj->horariocontacto) ? $obj->horariocontacto : "";
			$dominiogsuite		=	isset($obj->dominiogsuite) ? $obj->dominiogsuite : "";
			$dominiooffice		=	isset($obj->dominiooffice) ? $obj->dominiooffice : "";
			$servicios			=	$obj->servicios;
			$rfc				=	isset($obj->rfc) ? $obj->rfc : "";
			
			$query = "
			INSERT INTO  
				formulario_contratacioncrm
			VALUES (
				 NULL
				,'$nombre'
				,'$apaterno'
				,'$teltelmex'
				,'$empleado'
				,'$canalcontrata'
				,'$email'
				,'$telefono'
				,'$mercado'
				,'$empresa'
				,'$cuentamaestra'
				, NULL
				, NOW()
				,'$dominio'
				)";
			$resEmp = mysqli_query($GLOBALS['con'], $query);
			$folio = mysqli_insert_id($GLOBALS['con']);

			$serviciosxml="<Servicios>";
			foreach($servicios as $servicio) {
				$plan=isset($servicio->plan) ? $servicio->plan : "";
				$nombreplan=isset($servicio->nombreplan) ? $servicio->nombreplan : "";
				$rtamensciva=isset($servicio->rtamensciva) ? $servicio->rtamensciva : "";
				$incluido=isset($servicio->incluido) ? $servicio->incluido : "";
				$addrtamensciva=isset($servicio->addrtamensciva) ? $servicio->addrtamensciva : "";
				$maxadicional=isset($servicio->maxadicional) ? $servicio->maxadicional : "";
				
				$query = "
					INSERT INTO  
						contrato_producto
					VALUES (
						 NULL
						,'$plan'
						,'$nombreplan'
						,'$maxadicional'
						,'$folio'
					)";
				$resEmp = mysqli_query($GLOBALS['con'], $query);
				
				$serviciosxml.="
				<planes>
					<cPlan>".$plan."</cPlan>
					<cNombrePlan>".$nombreplan."</cNombrePlan>
					<cRtaMensCIVA>".$rtamensciva."</cRtaMensCIVA>
					<cIncluido>".$incluido."</cIncluido>
					<cAddRtaMensCIVA>".$addrtamensciva."</cAddRtaMensCIVA>
					<cMaxAdicional>".$maxadicional."</cMaxAdicional>
				 </planes>
				";
			}
			$serviciosxml.="</Servicios>";
			$folioamt=null;
			$arr = array('folio'=>$folio);
			if($GLOBALS['produccion'] || $GLOBALS['amatech']){
				$sql="SELECT fecha FROM formulario_contratacioncrm WHERE id=$folio";
				$resEmp = mysqli_query($GLOBALS['con'], $sql);
				$resValidation= mysqli_fetch_assoc($resEmp);
				$fecha= $resValidation['fecha'];
				
				$idtelmex=date("Y")."-$folio";
				$solicitud=array(
					"cNombreCliente"=>$nombre." ".$apaterno
					,"cTelTelmex"=>$teltelmex
					,"cDominio"=>$dominio
					,"cEmpleado"=>$empleado
					,"cCanalContrata"=>$canalcontrata
					,"cRFC"=>$rfc
					,"cTelefono"=>$telefono
					,"cEmail"=>$email
					,"cPaginaWeb"=>$paginaweb
					,"cMercado"=>$mercado
					,"cEmpresa"=>$empresa
					,"cCuentaMaestra"=>$cuentamaestra
					,"cHorarioContacto"=>$horariocontacto
					,"cDominioGsuite"=>$dominiogsuite
					,"cDominioOffice"=>$dominiooffice
					,"dFechaEnvio"=>$fecha
					,"serviciosxml"=>$serviciosxml
				);					
				$folioamt=sendWSDLTec($solicitud);
			}
			

			if($folioamt!=null && $folioamt!="Sin respuesta Amatech"){
				//$restec  = json_encode($folioamt);
				$sql="UPDATE formulario_contratacioncrm SET folioamt='$folioamt' WHERE id=$folio;";
				mysqli_query($GLOBALS['con'], $sql) or die(mysqli_error());
			}
			
			echo json_encode(array('error'=>array("codigo"=>"00", "descripcion"=>"Exito", "descTecnica"=>$folio)));
			
		}catch (mysqli_sql_exception $e) { 
			echo json_encode(array('error'=>array("codigo"=>"1003", "descripcion"=>"Error", "descTecnica"=>"Error al consultar")));
			die('Could not enter data: ' . mysqli_error($GLOBALS['con'])); 
		}
	}else{
		echo json_encode(array('error'=>array("codigo"=>"1004", "descripcion"=>"error", "descTecnica"=>"Empty")));
	}
}

function sendWSDLTec($solicitud){
		$result = "";
		$WSDL='https://extranet.amatech.com.mx/NET/Triara/WSTriara/TomaPedidos.asmx?WSDL';
		//$WSDL='http://desarrollo.amatech.com.mx/NET/Triara/WSTriara/TomaPedidos.asmx?WSDL';
		$soapUrl = $WSDL; // asmx URL of WSDL
		// xml post structure	
	   $xml_post_string = '
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
			<IngresaTomaPedido xmlns="http://desarrollo.amatech.com.mx/">
			  <ws>
				<cNombreCliente>'.$solicitud["cNombreCliente"].'</cNombreCliente>
				<cTelTelmex>'.$solicitud["cTelTelmex"].'</cTelTelmex>
				<cDominio>'.$solicitud["cDominio"].'</cDominio>
				<cEmpleado>'.$solicitud["cEmpleado"].'</cEmpleado>
				<cCanalContrata>'.$solicitud["cCanalContrata"].'</cCanalContrata>
				<cRFC>'.$solicitud["cRFC"].'</cRFC>
				<cTelefono>'.$solicitud["cTelefono"].'</cTelefono>
				<cEmail>'.$solicitud["cEmail"].'</cEmail>
				<cPaginaWeb>'.$solicitud["cPaginaWeb"].'</cPaginaWeb>
				'.$solicitud["serviciosxml"].'
				<cMercado>'.$solicitud["cMercado"].'</cMercado>
				<cEmpresa>'.$solicitud["cEmpresa"].'</cEmpresa>
				<cCuentaMaestra>'.$solicitud["cCuentaMaestra"].'</cCuentaMaestra>
				<cHorarioContacto>'.$solicitud["cHorarioContacto"].'</cHorarioContacto>
				<cDominioGsuite>'.$solicitud["cDominioGsuite"].'</cDominioGsuite>
				<cDominioOffice>'.$solicitud["cDominioOffice"].'</cDominioOffice>
				<dFechaEnvio>'.$solicitud["dFechaEnvio"].'</dFechaEnvio>
			  </ws>
			</IngresaTomaPedido>
		  </soap:Body>
		</soap:Envelope>'; 
		$headers = array(
				"Content-type: text/xml;charset=\"utf-8\"",
				//"Accept: text/xml",
				//"Cache-Control: no-cache",
				//"Pragma: no-cache",
				"SOAPAction: http://desarrollo.amatech.com.mx/IngresaTomaPedido", 
				"Content-length: ".strlen($xml_post_string),
		); //SOAPAction: your op URL
		
		$url = $WSDL;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		// converting
		$response = curl_exec($ch);

		$response1 = str_replace("<soap:Body>","",$response);
		$response2 = str_replace("</soap:Body>","",$response1);

		// convertingc to XML
		$parser = simplexml_load_string($response2);
		//echo $xml_post_string;
		$json = $parser->IngresaTomaPedidoResponse;
		//
		$my_array = (array)$json;

		if($my_array!=null && $my_array!=""){
			$result = $my_array['IngresaTomaPedidoResult'];
		}
		
		curl_close($ch);
		
		return $result;

}

function obtenerRegistros(){
	$obj=$GLOBALS['datos'];
	if($obj!=""){
		try{
			$user 						=	$obj->user;
			$fechaIni			 		=	$obj->fechaIni;
			$fechaFin			 		=	$obj->fechaFin;
			if($user=="usrpym32022"){
				$query = "
				SELECT 
					id
					, nombre
					, apaterno
					, teltelmex
					, empleado
					, canalcontrata
					, email
					, telefono
					, mercado
					, empresa
					, ctamaestra
					, folioamt
					, fecha
					, dominio
				FROM formulario_contratacioncrm WHERE CAST(fecha AS DATE) BETWEEN '$fechaIni' AND '$fechaFin';";
				$result = mysqli_query($GLOBALS['con'], $query);

				$tableResponse="ID|NOMBRE|AP PATERNO|TEL TELMEX|EMPLEADO|CANAL CONTRATA|EMAIL|TELEFONO|MERCADO|EMPRESA|CTA MAESTRA|FOLIO AMT|FECHA|DOMINIO*";
				
				while($row = mysqli_fetch_assoc($result)) {
					//echo "ENTRO";
					$tdval="";      
					$tdval.=$row['id']."|";
					$tdval.=$row['nombre']."|";
					$tdval.=$row['apaterno']."|";
					$tdval.=$row['teltelmex']."|";
					$tdval.=$row['empleado']."|";
					$tdval.=$row['canalcontrata']."|";
					$tdval.=$row['email']."|";
					$tdval.=$row['telefono']."|";
					$tdval.=$row['mercado']."|";
					$tdval.=$row['empresa']."|";
					$tdval.=$row['ctamaestra']."|";
					$tdval.=$row['folioamt']."|";
					$tdval.=$row['fecha']."|";
					$tdval.=$row['dominio']."*";
					$tableResponse.=$tdval;
				}
				echo json_encode(array('error'=>array("codigo"=>"00", "descripcion"=>"Exito", "descTecnica"=>$tableResponse)), JSON_UNESCAPED_UNICODE );
			}else{
				echo json_encode(array('error'=>array("codigo"=>"110", "descripcion"=>"Fallo", "descTecnica"=>"Sin autorizacion")));
			}
		}catch (mysqli_sql_exception $e) { 
			echo json_encode(array('error'=>array("codigo"=>"1003", "descripcion"=>"Error", "descTecnica"=>"Error al consultar")));
			die('Could not enter data: ' . mysqli_error($GLOBALS['con'])); 
		}
	}else{
		echo json_encode(array('error'=>array("codigo"=>"1004", "descripcion"=>"error", "descTecnica"=>"Empty")));
	}
}
