<?php
include "config.php";
include "utils.php";

$dbConn =  connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['codigo']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT idestudiantes, Nombre, Apellido, edad estudiante  where codigo=:codigo");
      $sql->bindValue(':codigo', $_GET['codigo']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM estudiante");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
	}
}
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO estudiante
          ( nombre, apellido, edad)
          VALUES
          ( :nombre, :apellido, :edad)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();

    $postCodigo = $dbConn->lastInsertId();
    if($postCodigo)
    {
      $input['codigo'] = $postCodigo;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
  }
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$codigo = $_GET['codigo'];
  $statement = $dbConn->prepare("DELETE FROM  estudiante where codigo=:codigo");
  $statement->bindValue(':codigo', $codigo);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postCodigo = $input['codigo'];
    $fields = getParams($input);

    $sql = "
          UPDATE estudiante
          SET $fields
          WHERE codigo='$postCodigo'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}
?>
