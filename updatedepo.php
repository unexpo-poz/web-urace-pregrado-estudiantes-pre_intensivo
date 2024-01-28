<?php
    include_once('inc/odbcss_c.php');
	include_once ('inc/config.php');
	include_once ('inc/activaerror.php');

	$fechah  = date('Y-m-d', time() - 3600*date('I'));
	$hora   = date('h:i:s', time() - 3600*date('I'));

	//TRECERA PARTE, MODIFICAR LOS DATOS
	if (isset($_POST['modifica'])){
		//print_r($HTTP_POST_VARS);
		$exp=$_POST['exp'];
		$login=$_POST['login'];
		$pass=$_POST['pass'];

		$Cdatos_p = new ODBC_Conn("CENTURA-DACE","$login","$pass");
				$dSQL = "DELETE from depositos ";
				$dSQL = $dSQL."WHERE exp_e='$exp' AND lapso='$lapsoProceso' ";
		$Cdatos_p->ExecSQL($dSQL);

		for ($i=1;$i<=$_POST['k'];$i++){
			//echo $i;
			//MODIFICACION DE DATOS DE LOS DEPOSITOS
			$depo=$_POST['depo_'.$i];
			$monto=$_POST['monto_'.$i];
			$fecha=$_POST['fecha_'.$i];
			$Cdatos_p = new ODBC_Conn("CENTURA-DACE","$login","$pass");
				$dSQL = "INSERT INTO depositos (n_planilla,monto,exp_e,fecha,lapso) ";
				$dSQL = $dSQL."values ('$depo','$monto','$exp','$fecha','$lapsoProceso') ";
			$Cdatos_p->ExecSQL($dSQL);
			//Insertamos las calificaciones en AUDITORIA
			$Caud = new ODBC_Conn("CENTURA-DACE","$login","$pass");
				$mSQL = "INSERT INTO AUDITORIAD ";
				$mSQL= $mSQL."(USER_ID,TABLA,CAMPO1,CAMPO2,CAMPO3,CAMPO4,FECHA,HORA,QUE) ";
				$mSQL= $mSQL." VALUES ('$login','$exp','$depo','$monto','$fecha', ";
				$mSQL= $mSQL."'$lapsoProceso','$fechah','$hora','Modificar Deposito')";	
			$Caud->ExecSQL($mSQL);
		}
	}
?>

<html>
<head>
<title>MODIFICAR DATOS DE LOS DEPOSITOS</title>

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
.datospf {
  text-align: left; 
  font-family:Arial; 
  font-size: 11px;
  font-weight: normal;
  background-color:#FFFFFF; 
  font-variant: small-caps;
  border-style: solid;
  border-width: 1px;
  border-color: #96BBF3;
  text-transform:uppercase;
}


-->
</style>
</head>
<body>
<table align="center" border="0" cellpadding="0" cellspacing="1" width="350">
	<tr>
		<td class="inact"><IMG SRC="imagenes/unex15.gif" WIDTH="75" HEIGHT="75" BORDER="0" ALT="">
		</td>
		
		<td class="inact" >Universidad Nacional Experimental Polit&eacute;cnica<BR>"Antonio Jos&eacute; de Sucre"<BR>Vicerrectorado&nbsp;<? print $vicerrectorado?><BR><? print $nombreDependencia  ?><BR> <? print $tProceso ?>&nbsp;Lapso&nbsp;<? print $lapsoProceso ?>
		</td>
		
	</tr>
	<tr>
		<td class="tit14" colspan="2"><BR>
		MODIFICAR DEPOSITO<BR>
		<?=date('d/m/Y')?> <?date_default_timezone_set("America/Caracas");
	$hora_local  = mktime(date('H'),date('i')-30); 
	$hora=getdate($hora_local);print "&nbsp;$hora[hours]:$hora[minutes]&nbsp"?></td>
		
	</tr>
</table>

<?php
if (($_POST['login']!="") and ($_POST['pass']!="")){
	$login=$_POST['login'];
	$pass=$_POST['pass'];
	echo <<<ENC
	<form name="depo" method="POST" action="updatedepo.php">
		<table align="center" border="0" cellpadding="0" cellspacing="1" width="100" style="border-collapse: collapse;border-color:black;">
			<tr class="enc_p2">
				<td width="10">Cedula:</td>
				<td width="60">
					<input name="cedula" maxlength="8" class="datospf" style="width: 130px;" type="text">
				</td>
				<td width="60">
					<INPUT TYPE="submit" value="Consultar">
					<INPUT TYPE="hidden" name="login" value={$login}>
					<INPUT TYPE="hidden" name="pass" value={$pass}>
				</td>
			</tr>
		</table>
	</form>
ENC;
}else echo "<script>document.location.href='mdepo.php';</script>\n";
//SEGUNDA PARTE, CONSULTAR DEPOSITOS
if (isset($_POST['cedula'])){
//print_r($HTTP_POST_VARS);
	$ci=$_POST['cedula'];
	$login=$_POST['login'];
	$pass=$_POST['pass'];

	//CONSULTA DE DATOS DEL ESTUDIANTE
	$Cdatos_p = new ODBC_Conn("CENTURA-DACE","$login","$pass");
		$dSQL = "SELECT exp_e,nombres,apellidos ";
		$dSQL = $dSQL."FROM dace002 ";
		$dSQL = $dSQL."WHERE ci_e='$ci' ";
	$Cdatos_p->ExecSQL($dSQL);
	$datp=$Cdatos_p->result;
	$exp=$datp[0][0];
	$nombre=$datp[0][1];
	$apellido=$datp[0][2];

	//CONSULTA DE DATOS DE LOS DEPOSITOS
	$Cdatos_p = new ODBC_Conn("CENTURA-DACE","$login","$pass");
		$dSQL = "SELECT n_planilla,monto,fecha ";
		$dSQL = $dSQL."FROM depositos ";
		$dSQL = $dSQL."WHERE exp_e='$exp' AND lapso='$lapsoProceso' ";
	$Cdatos_p->ExecSQL($dSQL);
	$depo=$Cdatos_p->result;
	$z=$Cdatos_p->filas; 

echo <<<ENC2
		<table align="center" border="0" cellpadding="0" cellspacing="1" width="480" style="border-collapse: collapse;border-color:black;">
			<tr class="enc_p">
				<td width="100">EXPEDIENTE</td>
				<td width="170">NOMBRES</td>
				<td width="170" colspan="2">APELLIDOS</td>
				
			</tr>
			<tr class="enc_p2">
				<td>$exp</td>
				<td>$nombre</td>
				<td colspan="2">$apellido</td>
			</tr>
		</table>
		<form name="depo1" method="POST" action="updatedepo.php">
		<table align="center" border="1" cellpadding="0" cellspacing="1" width="300" style="border-collapse: collapse;border-color:black;">
			<tr class="enc_p">
				<td width="10">NRO</td>
				<td width="100">Planilla</td>
				<td width="60">Monto</td>
				<td width="100">Fecha</td>
			</tr>
	
ENC2;

	for ($i=0;$i<$z;$i++){
		echo "<tr>";
		$k=$i;
		echo "<td class=\"inact\">".++$k."</td>";
			echo "<td class=\"inact\">\n";
				echo "<input name=\"depo_".$k."\" maxlength=\"8\" class=\"datospf\" style=\"width: 60px;\" value=\"".$depo[$i][0]."\">\n";
				//echo $depo[$i][0];	
			echo "</td>\n";
			echo "<td class=\"inact2\">\n";
				echo "<input name=\"monto_".$k."\" maxlength=\"4\" class=\"datospf\" style=\"width: 30px;\" value=\"".$depo[$i][1]."\">\n";
				//echo $depo[$i][1];
			echo "</td>\n";
			echo "<td class=\"inact\">\n";
				echo "<input name=\"fecha_".$k."\" class=\"datospf\" style=\"width: 60px;\" value=\"".$depo[$i][2]."\" readonly=\"readonly\">\n";
			echo "</td>\n";
		echo "</tr>";

	}
	echo "<tr>";
			echo "<td colspan=\"4\" align=\"center\">\n";
				echo "<BR><INPUT TYPE=\"submit\" value=\"Modificar\">\n";
				echo "<INPUT TYPE=\"hidden\" name=\"modifica\" value=\"1\">\n";
				echo "<INPUT TYPE=\"hidden\" name=\"k\"  value=\"".$k."\">\n";
				echo "<INPUT TYPE=\"hidden\" name=\"exp\"  value=\"".$exp."\">\n";
				echo "<INPUT TYPE=\"hidden\" name=\"login\" value=\"".$login."\">\n";
				echo "<INPUT TYPE=\"hidden\" name=\"pass\" value=\"".$pass."\">\n";
			echo "</td>\n";
		echo "</tr>";
	echo "</table></form>";
}
 
	

?>
</body>
</html>
