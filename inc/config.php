<?php
$enProduccion		= true;
$raizDelSitio		= 'http://'.$_SERVER['SERVER_NAME'].'/web/urace/pregrado/estudiantes/pre_intensivo/';
$urlDelSitio		= 'http://www.poz.unexpo.edu.ve/';
$tProceso			= 'Preinscripciones Intensivo';
$lapsoProceso		= '2022-1I';
$tLapso				= 'Lapso '.$lapsoProceso;
$laBitacora			= $_SERVER[DOCUMENT_ROOT].'/log/pregrado/estudiantes/intensivo/preintensivo_'.$lapsoProceso.'.log';
$inscHabilitada		= false;// Habilita/Deshabilita el sistema

$soloOfertadas		= true; //habilita en la planilla solo las materias ofertadas
$sedesUNEXPO		= array (	
						'BQTO' => array('BQTO', 'CARORA'), 
						'CCS'  => array('DACECCS'),
						'POZ'  => array('CENTURA-DACE')
						//'POZ'  => array('DACEPOZ')
				);

//$sedeActiva = 'BQTO';
//$sedeActiva = 'CCS';
$sedeActiva = 'POZ';
$pensumPoz = '5';

$nucleos = $sedesUNEXPO[$sedeActiva];

//$vicerrectorado		= "Luis Caballero Mej&iacute;as";
//$vicerrectorado		= "Barquisimeto";
 $vicerrectorado	= "Puerto Ordaz";
$nombreDependencia	= 'Unidad Regional de Admisi&oacute;n y Control de Estudios';

//Unidad Tributaria y Costo de las materias:
$unidadTributaria	= 1200000.00;
//$valorPreMateria	= $unidadTributaria*0.2;
$valorPreMateria	= 500000.00;// Ajuste Manual aprobado por CDR
$maxDepo			= 0;

$banco		= "Caron&iacute;";
$cuenta	= "0128-0038-0138-21-541103";
$titular	= "Ingresos Propios UNEXPO";

$bancov	= "Venezuela";
$cuentav	= "0102-0529-80-0000000479";
$titularv	= "Presupuesto Ordinario";

// Proteccion de las paginas contra boton derecho, no javascript y navegadores no soportados:
if ($enProduccion){
	$botonDerecho = 'oncontextmenu="return false"';
	$noJavaScript = '<noscript><meta http-equiv="REFRESH" content="0;URL=no-javascript.php"></noscript>';
	$noCache	  = "<meta http-equiv=\"Pragma\" content=\"no-cache\">\n";
	$noCache	 .= '<meta http-equiv="Expires" content="-1">';
	$noCacheFin	  = '<head><meta http-equiv="Pragma" content="no-cache"></head>';
}
else {
	$botonDerecho = '';
	$noJavaScript = '';
	$noCache	  = '';
	$noCacheFin	  = '';
}
?>