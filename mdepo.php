<?php
    include_once('inc/odbcss_c.php');
	include_once ('inc/config.php');
	include_once ('inc/activaerror.php');
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
		INGRESO AL SISTEMA<BR>
		<?=date('d/m/Y')?> <?date_default_timezone_set("America/Caracas");
	$hora_local  = mktime(date('H'),date('i')-30); 
	$hora=getdate($hora_local);print "&nbsp;$hora[hours]:$hora[minutes]&nbsp"?></td>
		
	</tr>
</table>

<?
	echo <<<ENC
	<form name="depo" method="POST" action="updatedepo.php">
		<table align="center" border="0" cellpadding="0" cellspacing="1" width="100" style="border-collapse: collapse;border-color:black;">
			<tr class="enc_p2">
				<td width="10">Usuario:&nbsp;</td>
				<td width="60">
					<input name="login" maxlength="8" class="datospf" style="width: 130px;" type="text">
				</td>
			</tr>
			<tr class="enc_p2">
				<td width="10">Clave:&nbsp;</td>
				<td width="60">
					<input name="pass" class="datospf" style="width: 130px;" type="password">
				</td>
			</tr>
			<tr class="enc_p2" >
				<td align="center" colspan="2">
					<INPUT TYPE="submit" value="ENTRAR">
				</td>
			</tr>
		</table>
	</form>
ENC;


?>
</body>
</html>
