<?php
include_once('inc/vImage.php'); 
include_once('inc/config.php'); 

imprima_enc();
if ($inscHabilitada){
	imprima_form();
}
else {
	print <<<x001
		<font style="font-family:arial; font-size:14px; color:red;">
		Disculpe, el proceso ha finalizado.
		</font>
x001
;
}
imprima_final();

function imprima_enc(){
	global $tProceso, $lapsoProceso, $enProduccion, $tLapso;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php
	print $noCache;
?>
<title><?php echo $tProceso . $tLapso; ?></title>

<script languaje="Javascript">
<!--
	if ((navigator.userAgent.indexOf("Opera")>=0) || (navigator.userAgent.indexOf("Safari")>=0)){
		alert("Disculpe, su cliente http no esta soportado en este sistema. Use Mozilla, Netscape o Internet Explorer"); 
		location.replace("no-soportado.php");	//	return; 
	}
// -->
</script>

  <script language="Javascript" src="md5.js">
   <!--
    alert('Error con el fichero js');
    // -->
  </script>
  <script languaje="Javascript">
<!--

  function validar(f) {
	if ((f.cedula_v.value == "")||(f.contra_v.value == "")) {
		alert("Por favor, escriba su cédula y clave antes de pulsar el botón Entrar");
		return false;
	} 
	else {
		f.contra.value = hex_md5(f.contra_v.value);
		f.contra_v.value = "";
		f.cedula.value = f.cedula_v.value;
		f.cedula_v.value = "";
		f.vImageCodP.value = f.vImageCodC.value;
		f.vImageCodC.value = "";
		window.open("","planilla","left=0,top=0,width=790,height=580,toolbar=0,menubar=0,scrollbars=1,resizable=1,status=1");
		<?php if ($enProduccion){ ?>
		setTimeout("location.reload()",90000);
		<?php } ?>
		return true;
	}

}
//-->
  </script>          
<style type="text/css">
<!--
#prueba {
  overflow:hidden;
  color:#00FFFF;
  background:#F7F7F7;
}

.instruc {
  font-family:Arial; 
  font-size: 13px; 
  font-weight: normal;
  background-color: #FFFF66;
}
.pasos {
  font-family:Arial; 
  font-size: 13px; 
  font-weight: normal;
  background-color: #a2bfe9;
}
.normal {
  font-family:Arial; 
  font-size: 12px; 
  font-weight: normal;
  background-color: white;
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

-->
</style>  

</head>


<body <?php global $botonDerecho; echo $botonDerecho; ?>>

<table id="table1" style="border-collapse: collapse;" border="0" cellpadding="0" cellspacing="1" width="750">

  <tbody>
  <tr>
    <td width="750" colspan="2">
				
          <p align="center" style="font-family:arial; font-weight:bold; font-size:20px;">
<?php			echo $tProceso .' '. $tLapso; 
?>		  </p>

	
    </td>
  </tr>
<!-- <tr>
	    <td align="center">
		<span style="font-family:Arial;color:red;font-size:14px;font-weight:bold;text-decoration:blink;">
		ATENCIÓN: El sistema de Preinscripción seguirá habilitado hasta el <span style="color:blue;">30/07/2009 a las 5:00 p.m.</span></span><br>
		<span style="font-family:Arial;color:red;font-size:16px;font-weight:bold;text-decoration:blink;">&Uacute;LTIMA PR&Oacute;RROGA</span>
		</td>
    </tr> -->
  <tr>

       <td width="750" align="center" colspan="2">
<?php
}
function imprima_form(){
?>

	   <font class="normal"><br>Por
favor escribe tus datos y el c&oacute;digo de seguridad, luego pulsa el bot&oacute;n "Entrar" para
          poder acceder a la preinscripci&oacute;n</font></td>
   </tr>
  <tr>
      <td width="777" align="center" colspan="2">
      <form method="post" name="chequeo" onsubmit="return validar(this)" 
            action="planilla_r.php" target="planilla" >
          <p class="normal">&nbsp; C&eacute;dula:&nbsp;
        <input name="cedula_v" size="15" tabindex="1" type="text">&nbsp; &nbsp;
		Clave:&nbsp;<input name="contra_v" size="20" tabindex="2" type="password">&nbsp;&nbsp;  
  &nbsp; C&oacute;digo de la derecha:&nbsp;
  <input name="vImageCodC" size="5" tabindex="3" type="text">&nbsp;
  <img src="inc/img.php?size=4" height="30" style="vertical-align: middle;">
  <input value="Entrar" name="b_enviar" tabindex="3" type="submit"> 
  <input value="x" name="cedula" type="hidden"> 
  <input value="x" name="contra" type="hidden">
  <input value="" name="vImageCodP" type="hidden"> 
</p>
      </form>

<?php //imprima_form
}

function imprima_final(){
	global $unidadTributaria,$valorPreMateria;
?>
	  </td>
    </tr>
	 <tr>
	    <td class ="pasos" width="100%"><b>PASOS A SEGUIR:</b>
      <ul>
        <li>Deposita en la cuenta corriente Nro. <strong>0128-0038-01-3821541103</strong> del Banco Caron&iacute; la cantidad de <strong>Once Bol&iacute;vares</strong> (11 Bs.) por cada asignatura (Una planilla de dep&oacute;sito por asignatura) a nombre de
		<strong>CURSOS INTENSIVOS UNEXPO</strong>. <strong>(NO SE ACEPTAN TRASFERENCIAS BANCARIAS)</strong>. </li>
		<li>Una vez procesada la preinscripci&oacute;n imprime dos (2) planillas y f&iacute;rmalas.</li>
		<li>Identifica al dorso de cada planilla de dep&oacute;sito con tus apellidos, nombres, Nro. de c&eacute;dula, tel&eacute;fono, c&oacute;digo y nombre de la asignatura.</li>
		<li>Entrega en el Dpto. de Estudios Generales, las planillas impresas junto con los dep&oacute;sitos ya identificados, en horario comprendido entre 9:00 am y 5:00 pm a partir del d&iacute;a Jueves 02/07/2009 hasta el Jueves 16/07/2009.</li>
       </ul>
	   </td>
    </tr>
    <tr>
      <td class ="instruc" width="100%"><b>NOTAS:</b>
      <ul>
        <li>Los datos de los dep&oacute;sitos ser&aacute;n verificados con el banco. 
		En caso de existir alguna discrepancia, la preinscripci&oacute;n ser&aacute; anulada. </li>
       <li>La preinscripci&oacute;n
		<span style="font-weight: bold; color: #3300CC;"> NO ASEGURA LA APERTURA DE LA ASIGNATURA</span>: Esto depende de la disponibilidad del profesor y del n&uacute;mero de preinscritos. </li>
		<li><font style="font-weight: bold; color: #3300CC;">
			NO PUEDES</font> cambiar los datos de los dep&oacute;sitos registrados en una 
			preinscripci&oacute;n previa.</li>
		<li> <strong>Consulta el Reglamento de Intensivos Vigente para aclarar las dudas.</strong>
		<a href="http://www.poz.unexpo.edu.ve/web/descargas/pdf/Reglamento_Cursos_Intensivos.pdf" target="_blank">
		<strong>Haz clic aqu&iacute;</strong></a></li>

       </ul>
	   </td>
	  </tr>
  </tbody>
</table>
</body>
<?php
//Evitar que la pagina se guarde en cache del cliente
print $noCacheFin;
?>
</html>
<?php
} //imprima_final	 
?>
