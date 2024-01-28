<?php
    include_once('inc/odbcss_c.php');
	include_once ('inc/config.php');
	include_once ('inc/activaerror.php');

	//$lapsoProceso = "2013-1I";
	
	//CONSULTA DE DATOS ALMACENADOS
	$Cdatos_p = new ODBC_Conn("CENTURA-DACE","N","N");
		$dSQL = "SELECT A.c_asigna, asignatura, COUNT(A.exp_e) ";
		$dSQL = $dSQL."FROM dace006 A, tblaca008 B ";
		$dSQL = $dSQL."WHERE A.c_asigna = B.c_asigna AND A.lapso='".$lapsoProceso."' AND A.status IN ('P') ";
		$dSQL = $dSQL."GROUP BY  A.c_asigna, asignatura ORDER BY 2 asc ";
	$Cdatos_p->ExecSQL($dSQL);
	$docente=$Cdatos_p->result;
	$z=$Cdatos_p->filas;
	//CONSULTA DE DATOS ALMACENADOS
	$Cdatos_p = new ODBC_Conn("CENTURA-DACE","N","N");
		$dSQL = "SELECT distinct exp_e ";
		$dSQL = $dSQL."FROM dace006 ";
		$dSQL = $dSQL."WHERE status IN ('P') AND lapso='".$lapsoProceso."' ";
	$Cdatos_p->ExecSQL($dSQL);
	$d=$Cdatos_p->filas;

?>

<html>
<head>
<title>RESULTADOS DE LAS PRE-INSCRIPCIONES</title>

<style type="text/css">
<!--
.tit14 {
  text-align: center; 
  font-family: Arial; 
  font-size: 13px; 
  font-weight: bold;
  letter-spacing: 1px;
  font-variant: small-caps;
}
.instruc {
  font-family:Arial; 
  font-size: 12px; 
  font-weight: normal;
  background-color: #FFFFCC;
  text-align: center;
}
.instruc1 {
  font-family:Arial; 
  font-size: 12px; 
  font-weight: normal;
  text-align: right;
}
.boton {
  text-align: center; 
  font-family:Arial; 
  font-size: 11px;
  font-weight: normal;
  background-color:#e0e0e0; 
  font-variant: small-caps;
  height: 20px;
  padding: 0px;
}
.enc_p {
  color:#FFFFFF;
  text-align: center; 
  font-family:Arial; 
  font-size: 11px; 
  font-weight: normal;
  background-color:#3366CC;
  height:20px;
  font-variant: small-caps;
}
.enc_p2 {
  color:#000000;
  font-family:Arial; 
  font-size: 13px; 
  font-weight: bold;
  height:20px;
  font-variant: small-caps;
  text-align: center; 
}
.inact {
  text-align: center; 
  font-family:Arial; 
  font-size: 11px; 
  font-weight: normal;
  
}
.inact2 {
  text-align: left; 
  font-family:Arial; 
  font-size: 11px; 
  font-weight: normal;
  padding-left: 10px;
 
}

-->

/**/

/* tables */
table.tablesorter {
	font-family:arial;
	background-color: #CDCDCD;
	margin:10px 0pt 15px;
	font-size: 8pt;
	width: 100%;
	text-align: left;
}
table.tablesorter thead tr th, table.tablesorter tfoot tr th {
	background-color: #e6EEEE;
	border: 1px solid #FFF;
	font-size: 8pt;
	padding: 4px;
	color: #000000;
}
table.tablesorter thead tr .header {
	background-image: url(bg.gif);
	background-repeat: no-repeat;
	background-position: center right;
	cursor: pointer;
}
table.tablesorter tbody td {
	color: #3D3D3D;
	padding: 4px;
	background-color: #FFF;
	vertical-align: top;
}
/*table.tablesorter tbody td:hover {
	color: #3D3D3D;
	padding: 4px;
	background-color: #FFFFCC;
	vertical-align: middle;
}*/
table.tablesorter tbody tr.odd td {
	background-color:#F0F0F6;
}
table.tablesorter tbody tr:hover td{
	background-color:#FFFF99;
}
table.tablesorter thead tr .headerSortUp {
	background-image: url(asc.gif);
}
table.tablesorter thead tr .headerSortDown {
	background-image: url(desc.gif);
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
	background-color: #8dbdd8;
}

</style>

<script src="jquery.js" type="text/javascript"></script>
<script src="jquery.tablesorter.js" type="text/javascript"></script>

<script>
/*$(document).ready(function() 
  { 
    $("#miTabla").tablesorter(); 
  } 
);*/
/*$(document).ready(function() 
    { 
        $("#myTable").tablesorter(); 
    } 
);*/

</script>

<script language="javascript" type="text/javascript">
	$(function() {
$("#listado").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
}); 
</script>

</head>
<body>

<table align="center" border="0" cellpadding="0" cellspacing="1" width="350">
	<tr>
		<td class="inact">
			<img src="/web/urace/acceso/img/header_urace.png" border="0" alt="">
		</td>
		
<!-- 		<td class="inact" >Universidad Nacional Experimental Polit&eacute;cnica<BR>"Antonio Jos&eacute; de Sucre"<BR>Vicerrectorado&nbsp;<? print $vicerrectorado?><BR><? print $nombreDependencia  ?><BR> <? print $tProceso ?>&nbsp;Lapso&nbsp;<? print $lapsoProceso ?>
		</td> -->
		
	</tr>
	<tr align="center">
		<td style="font-family:Arial;font-size:13px;"><B>Lapso Especial Intensivo <? print $lapsoProceso ?> <BR>Resultados de las Pre-Inscripciones actualizado al <?=date('d/m/Y')?> a las <?php
		/*$h = "4.5";
		$hm = $h*60;
		$ms = $hm*60;*/
		$hora = date("g:i a");
		echo "&nbsp;$hora&nbsp"?></B><BR><HR></td>
		
	</tr>
</table>

<?php
	//echo "<table align=\"center\" border=\"1\" cellpadding=\"0\" cellspacing=\"1\" width=\"530\" style=\"border-collapse: collapse;border-color:black;\">";

	//<table align="center" border="1" cellpadding="0" cellspacing="1" width="70%" style="border-collapse: collapse;border-color:black;" id="listado" class="tablesorter">
	//<table cellpadding="0" cellspacing="0" border="1" width="70%" id="listado" class="tablesorter">
	echo <<<ENC
		<span style="font-family:Arial;font-size:11px;">Presione sobre el encabezado de las columnas para ordenar</span> <input type="button" value="Actualizar" onClick="location.reload();" align="rigth"/>
		<table align="center" border="1" cellpadding="0" cellspacing="1" width="70%" style="border-collapse: collapse;" id="listado" class="tablesorter">
		
		<thead>
			<tr class="enc_p">
				<th width="5%;">NRO&nbsp;&nbsp;</th>
				<th width="15%;">CODIGO</th>
				<th width="70%;">ASIGNATURA</th>
				<th width="10%;">&nbsp;&nbsp;PREINSCR&nbsp;&nbsp;</th>
			</tr>
		</thead>
		
		<tbody>

ENC;
	$total=0;
	for ($i=0;$i<$z;$i++){
		echo "<tr onmouseover=\"this.style.backgroundColor='#FFFF99'\" onmouseout=\"this.style.backgroundColor='#FFFFFF'\">";
		$k=$i;
		
		echo "<td class=\"inact\">".++$k."</td>";
		//for ($j=0;$j<=2;$j++){
			echo "<td class=\"inact\">";
				echo $docente[$i][0];
			echo "</td>";
			echo "<td class=\"inact2\">";
				echo $docente[$i][1];
			echo "</td>";
			echo "<td class=\"inact\">";
				echo $docente[$i][2];
				$total+=$docente[$i][2];
			echo "</td>";
		//}
		echo "</tr>";
	}
	echo "</tbody><tbody><tr><td colspan=\"3\"  class=\"instruc1\">Total Preinscripciones:&nbsp;</td>";
	echo "<td class=\"instruc\">- $total -</td></tr>";
	echo "<tr class=\"enc_p2\"><td colspan=\"4\">Total de Estudiantes Pre Inscritos: - $d -</td></tr>";
	echo "</tbody></table>";
?>
	<table align="center" border="0" cellpadding="0" cellspacing="1" width="350">
	<tr>
		<td class="tit14" colspan="2">
		<input type="button" value="Actualizar" onClick="location.reload();"/>
		</td>
	</tr>
</table>

</body>
</html>
