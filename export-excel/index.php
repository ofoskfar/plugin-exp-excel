<?php
/**
 * Plugin Name: Excel Export
 * Plugin URI: https://cilantrodigital.com
 * Description: Exportacion de registros DB en excel 
 * Version: 1.0
 * Author: OLS
 * Author URI: https://cilantrodigital.com
 * License: GPL2
 */

/*
ejemplo: [excel_export foo="xxxx"]
 */
//include( 'create_excel.php' );

add_shortcode("excel_export", "xls_export");
 
function xls_export($attrs){
	$tipo = shortcode_atts(
		array(
			'foo' => 'no foo'
		), $attrs, 'excel_export');
	$tabla = "";
        $nombre = "";

	$fh = date("dmY");
	switch ($tipo['foo']) {
		case "registros":
			$tabla .= "codigos_compra";
			break;
		case "agenda":
			$tabla .= "agenda";
			break;
		case "votaciones":
			$tabla .= "votaciones";
			break;
	}

	$nombre .= $tipo['foo'] . "_" . $fh;
	$resp = '<button class="btn-export-xls" onclick=' . "'" . 'iniciar_export("' . $tabla . '", "' . $nombre . '")' . "'" . '>Descargar <span class="glyphicon glyphicon-download-alt"></span></button>';

	return $resp;
}

add_action('wp_enqueue_scripts','script_iniciar');

function script_iniciar(){
	wp_enqueue_script('iniciar_export', plugins_url('/js/exportar_excel.js', __FILE__));
	wp_localize_script('iniciar_export', 'getUrl', array(
    		'pluginsUrl' => plugins_url() . '/export-excel',
	));
}

?>
