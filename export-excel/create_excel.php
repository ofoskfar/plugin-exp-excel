<?php
require( '../../../wp-load.php' );
include ( "php-export-data.class.php" );

global $wpdb;
$pref = $wpdb->prefix;
$tab = strip_tags($_GET['tab']);
$prefijo = strip_tags($_GET['pref']);
$excel = new ExportDataExcel('browser');
$excel->filename = "reporte_" . $prefijo . ".xls";

$data = array();
switch ($tab) {
	case "codigos_compra":
		array_push($data, array("Usuario","Correo","Numero de orden", "Fecha y hora de registro"));
		$reg_cc = $wpdb->get_results( 'SELECT * FROM codigos_compra WHERE reporte = 0' );
	
		foreach($reg_cc as $rc){
			$reg_us = $wpdb->get_row( 'SELECT * FROM ' . $pref . 'users WHERE ID = ' . $rc->usuario );
			$reng = array("$reg_us->display_name","$reg_us->user_email","$rc->codigo","$rc->fh_registro");
			array_push($data, $reng);
			$tabla = $pref . 'users';
			$wpdb->update("$tabla", array('reporte' => 1), array('id' => $rc->id));
		}
		break;
	case "agenda":
		$reg_age = $wpdb->get_results( 'SELECT * FROM agenda WHERE status = 1 AND reportes = 0 ORDER BY fecha_solicitada, hora_solicitada' );

		array_push($data, array("Usuario","Correo","Alta de Solicitud","Solicitud On to One","Profesor","Correo","Inicio de profesor","Cierre de profesor","Inicio de usuario","Cierre de usuario"));
		foreach($reg_age as $ra){
			$id_user = $ra->id_user;
			$reg_us = $wpdb->get_row( 'SELECT * FROM ' . $pref . 'users WHERE ID = ' . $id_user );
			$reg_prof = $wpdb->get_row( 'SELECT * FROM ' . $pref .'users WHERE ID = ' . $ra->id_prof );
			$reng = array("$reg_us->display_name","$reg_us->user_email","$ra->fh_alta","$ra->fecha_solicitada . ' -> ' . $ra->hora_solicitada","$reg_prof->display_name","$reg_prof->user_email","$ra->start_prof","$ra->end_prof","$ra->start_usu","$ra->end_usu");	
			$wpdb->update('agenda', array('reportes' => 1), array('id' => $ra->id));
		}
		break;
	case "votaciones":
		$reg_vot = $wpdb->get_results( 'SELECT * FROM votaciones' );
		array_push($data, array("Curso","Votos"));
		foreach($reg_vot as $rv){
			$reng = array("$rv->curso","$rv->votos");
			array_push($data, $reng);
		}
		break;
}


$excel->initialize();
foreach($data as $row) {
	$excel->addRow($row);
}
$excel->finalize();
