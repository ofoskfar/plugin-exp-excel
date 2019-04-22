function iniciar_export(tabla, prefijo){
	$(".procesando").remove();
	var url = getUrl.pluginsUrl + '/create_excel.php?tab=' + tabla + '&pref=' + prefijo;
	window.open(url, '_blank');
}
