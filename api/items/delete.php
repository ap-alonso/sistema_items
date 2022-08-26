<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../class/Items.php';
 
$database = new Database();
$db = $database->getConnection();
 
$items = new Items($db);
 
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) {
	$items->id = $data->id;
	if($items->delete()){    
		http_response_code(200); 
		echo json_encode(array('error' => array("codigo" => "00", "descripcion" => "Exito", "IdItem" => $items->id)));

	} else {    
		http_response_code(503);   
		echo json_encode(array('error' => array("codigo" => "1000", "descripcion" => "Error", "descTecnica" => "Error al consultar")));
	}
} else {
	http_response_code(400);
	echo json_encode(array('error' => array("codigo" => "1003", "descripcion" => "Error", "descTecnica" => "Petición Incompleta")));
}
?>