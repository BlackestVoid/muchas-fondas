<?php

$nombre = $_FILES["fotos"]["name"];
$temporal = $_FILES["fotos"]["tmp_name"];
$ruta = "../assets/img/subida/";
$absoluta = $ruta.$nombre;

if(move_uploaded_file($temporal,$absoluta)){
    echo "carga exitosa";
}else{
    echo "algo fallo";
}
?>