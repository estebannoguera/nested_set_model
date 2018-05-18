<?php
    include "ModelDB.php";
    include "ModeloPrueba.php";

	$model = new ModeloPrueba("nested_category");

	#print_r($model);

	#print "hola";

	//print_r($model->raiz());

	//print_r($model->obtener_hojas());

	//echo 'casa';
	print_r($model->subArbol_por_nombre('TUBE'));

?>