<?php
include '../config/dbdata.php';
include '../config/connection.php';
require_once('../util/Util.php');
$dbConn =  connect($db);
/*
  listar todos los posts o solo uno
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{  
  if(Util::VerifyToken() == false)
  {
    echo json_encode(['msg' => 'Error Auth']);
  }
  else{
    if (isset($_GET['id']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM establecimiento where idEstablecimiento=:id");
      $sql->bindValue(':id', $_GET['id']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM establecimiento");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
    }
	}
}
// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{  
  if(Util::VerifyToken() == false)
  {
    echo json_encode(['msg' => 'Error Auth']);
  }
  else{
    $input = $_POST;
    $sql = "INSERT INTO establecimiento
          (idEstablecimiento, idEstablecimiento)
          VALUES
          (:idEstablecimiento, :idEstablecimiento)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $Id = $dbConn->lastInsertId();
    if($Id)
    {
      $input['idEstablecimiento'] = $Id;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
    }
	}
}
//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
  if(Util::VerifyToken() == false)
  {
    echo json_encode(['msg' => 'Error Auth']);
  }
  else{
    $id = $_GET['id'];
    $statement = $dbConn->prepare("DELETE FROM establecimiento where idEstablecimiento=:id");
    $statement->bindValue(':id', $id);
    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
  }
}
//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
  if(Util::VerifyToken() == false)
  {
    echo json_encode(['msg' => 'Error Auth']);
  }
  else{
    $input = $_GET;
    $postId = $input['id'];
    $fields = getParams($input);
    $sql = "
          UPDATE establecimiento
          SET $fields
          WHERE idEstablecimiento='$postId'
           ";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
  }
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
?>