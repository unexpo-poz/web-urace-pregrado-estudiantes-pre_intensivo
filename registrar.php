<?php
    include_once('inc/odbcss_c.php');
	include_once ('inc/config.php');
	include_once ('inc/activaerror.php');
	ini_set('magic_quotes_sybase','1'); // para centura SQLBase Server

    $datos_p = array();
    $asignat = array();
	$depo    = array();
    $errstr  = "";
	$sede    = "";

	//$Cmat    = new ODBC_Conn($sede,"usuario2","usuario2",$ODBCC_conBitacora,'insc.log');
    $fecha  = date('Y-m-d', time() - 3600*date('I'));
    $hora   = date('h:i:s', time() - 3600*date('I'));
    $ampm   = date('A', time() - 3600*date('I'));
    $todoOK = true;
    $secc   =  "";
    $statusI = array();
    $inscrito = 0;

    function print_error($f,$sqlerr){
    
    print "<pre>".$f."\n".$sqlerr."</pre>";
    }
    
	function leer_datos_p($exp_e) {
        global $datos_p;
        global $errstr;
        global $E;
		global $sede;
		global $ODBCC_sinBitacora;
		global $masterID;
    
		if ($exp_e != ""){
            $Cusers = new ODBC_Conn("USERSDB","scael","c0n_4c4");
			$uSQL	= "SELECT userid FROM usuarios WHERE userid='".$exp_e."' ";
			$uSQL  .= "AND password='".$_POST['contra']."'";
			$Cusers->ExecSQL($uSQL);
			$claveOK = $Cusers->filas == 1; 
			if(!$claveOK) { //use la clave maestra
				$uSQL = "SELECT tipo_usuario FROM usuarios WHERE password='".$_POST['contra']."'";
				$Cusers->ExecSQL($uSQL);
				if ($Cusers->filas == 1) {
					$claveOK = (intval($Cusers->result[0][0],10) > 1000);
                }     
			}
			if ($claveOK) {		
				$Cdatos_p = new ODBC_Conn($sede,"c","c",$ODBCC_sinBitacora);
				$dSQL = " SELECT ci_e, exp_e, nombres, apellidos,c_uni_ca, nombres2, apellidos2 ";
				$dSQL = $dSQL."FROM DACE002 WHERE exp_e='".$exp_e."'";
				$Cdatos_p->ExecSQL($dSQL);
				$datos_p = $Cdatos_p->result[0];
				return ($Cdatos_p->filas == 1);
			}
            else return (false);
        }
        else return(false);      
    }
    
    function reportarError($errstr,$impmsg = true) {
	//global $errstr;
    if($impmsg) {
       print <<<E001
   
    <tr><td><pre> 
            Disculpe, Existen problemas con la conexi&oacute;n al servidor, 
            por favor contacte al personal de Control de Estudios o intente m&aacute;s tarde
    </pre></td></tr>
E001
;
    }
    $error_log=date('h:i:s A [d/m/Y]').":\n".$errstr."\n";
//    file_put_contents('errores.log', $error_log, FILE_APPEND);
}
    function consultarDatos() {
        
        global $ODBCSS_IP;
        global $datos_p; 
        global $asignat;
        global $errstr;
        global $lapso, $lapsoProceso;
        global $inscribe;
        global $sede;
		global $Cmat;
		global $inscrito;
		global $depo;
        
		$actBitacora = (intval('0'.$inscrito) != 1 || intval('0'.$inscribe)==2 ); 
		//actualiza bitacora si no es solo reporte;
        $todoOK = true;       
        //$Cdep = new ODBC_Conn($sede,"usuario2","usuario2", $ODBCC_conBitacora, $laBitacora);
        $dSQL = "SELECT A.c_asigna, asignatura, unid_credito, seccion, status FROM tblaca008 A, dace006 B ";
        $dSQL = $dSQL."WHERE exp_e='".$datos_p[1]."' AND lapso='$lapso' AND A.c_asigna = B.c_asigna";
        $Cmat->ExecSQL($dSQL,__LINE__); 
        if ($todoOK) {
            $asignat = $Cmat->result;
			$dSQL = "SELECT n_planilla, monto FROM depositos WHERE lapso='".$lapsoProceso."' and exp_e='".$datos_p[1]."'";
			$Cmat->ExecSQL($dSQL);
            $depo =$Cmat->result;
			//print '<pre>';
			//print $dSQL;
			//print_r($asignat);
			//print '</pre>';
            if ($actBitacora) {
                $dSQL = "UPDATE orden_inscripcion set inscrito='1'";
                $dSQL = $dSQL." WHERE ord_exp='$datos_p[1]'";
                $Cmat->ExecSQL($dSQL, __LINE__); 
				//actualizamos sexo y fecha de nacimiento:
                $dSQL = "UPDATE dace002 set sexo='".$_POST['sexo']."', ";
				$dSQL = $dSQL."f_nac_e='".$_POST['f_nac_e']."'"; 
                $dSQL = $dSQL." WHERE exp_e='$datos_p[1]'";
                //$Cmat->ExecSQL($dSQL, __LINE__,$actBitacora); 
            }
         }
		 $Cmat->finalizarTransaccion("usuario: ".$datos_p[1]." - Finaliza transaccion.");
        return($todoOK); 
		
    }

    function reportarPreInscripcion() {
        
        global $asignat, $datos_p, $depo;
        $tot_dep = 0;
		$firma = "";        
		$total = count($depo);
        for ($i=0; $i<$total;$i++){
            $tot_dep += $depo[$i][1];
		}
        $tot_uc = 0;
        $total = count($asignat);
        for ($i=0; $i<$total;$i++){
            $tot_uc += intval($asignat[$i][2]);
		}

        print <<<R001
    <tr><td>&nbsp;</td>
    </tr>
        <tr><td width="750">
        <TABLE align="center" border="1" cellpadding="3" cellspacing="1" width="550"
				style="border-collapse: collapse;">
        <TR><TD>
        <table align="center" border="0" cellpadding="0" cellspacing="1" width="550">
            <tr>
                <td style="width: 60px;" nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="matB">C&Oacute;DIGO</div></td>
                <td style="width: 300px;" bgcolor="#FFFFFF">
                    <div class="matB">ASIGNATURA</div></td>
                <td style="width: 60px;" nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="matB">U.C.</div></td>
                <td style="width: 60px;" nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="matB">SECCI&Oacute;N</div></td>
                <td style="text-align:center; width: 70px;" nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="matB">ESTATUS</div></td>
            </tr>

R001
;
        $total=count($asignat);
        for ($i=0;$i<$total;$i++) {
           $sEstatus = array(2=>'RETIRADA', 7=>'INSCRITA', 9=>'INCLUIDA','C'=>'CENSADA', 'P' =>'PREINSCR','A'=>'AGREGADA','Y'=>'EN COLA','R'=>'RET. REGL.');
			if ($asignat[$i][4] !='C'){
				$firma .= $asignat[$i][0].$asignat[$i][3].$asignat[$i][4]." ";
				if ($asignat[$i][3] == '') {
					$asignat[$i][3] = '-';
				}
				print <<<R002
            <tr>
                <td nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="mat">{$asignat[$i][0]}</div></td>
                <td bgcolor="#FFFFFF">
                    <div class="mat">{$asignat[$i][1]}</div></td>
                <td nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="mat">{$asignat[$i][2]}</div></td>
                <td nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="mat">{$asignat[$i][3]}</div></td>
                <td nowrap="nowrap" bgcolor="#FFFFFF">
                    <div class="mat">{$sEstatus[$asignat[$i][4]]}</div></td>
            </tr>

R002
;
			}
        }
        print <<<R0031
        </table>
        </TR></TD></TABLE>
R0031
;
		// imprime los depositos:

        $total=count($depo);
        if ($total > 1){
            $ptitulo = " las planillas ";
}
        else $ptitulo = " la planilla ";
        print <<<R006
        <tr><td>&nbsp;</td>
        </tr>
        <tr> <td class="tit14">Datos de
R006
;
        print $ptitulo."de dep&oacute;sito";
        print <<<R007
            </td>
        </tr>
        <tr><td width="750">              
        <TABLE align="center" border="1" cellpadding="3" cellspacing="1" width="550"
				style="border-collapse: collapse;">
        <TR><TD>
            <table align="center" border="0" cellpadding="0" cellspacing="1" width="360">
             <tr class="matB" style="width: 150px;" nowrap="nowrap">
                <td>
                    Planilla No.</td>
                <td style="text-align: right;">Monto Bs.</td>
                   <td>&nbsp;</td>
                         
            </tr>
R007
;
        for ($i=0;$i<$total;$i++) {
            print <<<R008
            <tr>
                <td class="depo">
R008
;        
            print $depo[$i][0];// No. planilla
            print <<<R009
                </td>
                <td class="depo" style="text-align: right;">
R009;
            $dd=round($depo[$i][1]*100)/100;
			print number_format($dd,2,',','.')."</td>";// monto 
            print <<<R010
                    
                    <td class="depo" style="text-align: left; width: 50px;">
                    
					</td>
            </tr>
R010
;       }
        print <<<R011
        <tr><td class="matB" style="text-align: right;" >
                Total dep&oacute;sito:</td>
            <td class="depo" style="text-align: right;">
R011
;
        print number_format($tot_dep,2,',','.')."</td>";
        print <<<R012
                    <td class="depo" style="text-align: left; width: 50px;">
                    
					</td>
            </tr>
        </tr>
        </table>
        </TD></TR></TABLE></td></tr>
R012
;


		$key1 = substr(md5("$datos_p[0]"),0,16);
		$key2 = substr(md5("$datos_p[1]"),0,16);

        print <<<R003
		<tr><td>
        <table align="center" border="0" cellpadding="0" cellspacing="1" width="550">
          <tr style="font-size: 2px;">
             <td colspan="2" > &nbsp; </td>
          </tr>
          <tr><form name="imprime" action="">
               <td valign="bottom"><p align="left">
                    <input type="button" value=" Imprimir " name="bimp"
                         style="background:#FFFF33; color:black; font-family:arial; font-weight:bold;" onclick="imprimir(document.imprime)"></p> 
               </td>
               <td valign="bottom"><p align="left">
                       <input type="button" value="Finalizar" name="bexit"
                        onclick="verificarSiImprimio()"></p> 
                </td></form>
          </tr>
          <tr style="font-size: 2px;">
             <td>&nbsp;</td>
             <td>&nbsp;<br>
                </td>
		<tr>
                <td colspan="2" class="nota">
                    <b>IMPORTANTE:</b><br>
                    Los datos de los dep&oacute;sitos ser&aacute;n verificados con el banco.
                    En caso de existir alguna discrepancia, la preinscripci&oacute;n
                    ser&aacute; anulada.<br><br>
					<b>La preinscripci&oacute;n no asegura la apertura de la asignatura</b>. Esto depende de la disponibilidad del profesor y del n&uacute;mero de estudiantes preinscritos.
                </td>
		</tr>
		<tr>
                <td colspan="2" class="nota"><br>
                La carga acad&eacute;mica inscrita por  el estudiante en esta
                planilla est&aacute; sujeta a control posterior por parte de Control de Estudios
                en relaci&oacute;n al cumplimiento de los prerrequisitos y 
                correquisitos sustentados en los pensa vigentes y a las cargas
                acad&eacute;micas m&aacute;ximas establecidas en el
                Reglamento de Evaluaci&oacute;n y Rendimiento Estudiantil vigente.
                La violaci&oacute;n de los requisitos y normativas antes mencionados
                conllevar&aacute; a la eliminaci&oacute;n de las asignaturas que no
                los cumplan.
                </td>
          </tr>
		  <tr><td colspan="2" class="matB"><br>C&Oacute;DIGO DE VALIDACI&Oacute;N:<br></td></tr>
		  <tr><td colspan="2" class="dp1"><br>$key1$key2<br></td></tr>
		  <tr><td colspan="2" class="matB">
			<IMG SRC="inc/barcode.php?barcode={$key1}&width=350&height=25&text=0" align="center">
		    </td>
		  </tr>
		  <tr><td colspan="2" class="nota">&nbsp;</td></tr>
          <tr><td colspan="2" class="matB">
			<IMG SRC="inc/barcode.php?barcode={$key2}&width=350&height=25&text=0" align="center">
		    </td>
		  </tr>
          </table>
        </tr>
        </table>
    </td>
    </tr>

R003
;
        
    }
       
    function asignaturasCorrectas() {
    // Revisa si las asignaturas que pretende inscribir son legales
	// es decir, si estan en su lista de materias_inscribir
		global $lapso, $datos_p;
		$correctas = true;       
        $asig	= array();
        $asig	= explode(" ",$_POST['asignaturas']);
        array_pop($asig);
        $total_a = count($asig);
		$total_mat = 0;
		if ($total_a > 0) {
			$listaAsig = '';
			$i = 0;
			while ($i<$total_a) {
				$listaAsig .= $asig[$i] . "','";
				$i=$i+4;
				$total_mat++;
			}
			$listaAsig = "('".$listaAsig."')";
            $Cdep  = new ODBC_Conn($_POST['sede'],"c","c",true);
            $dSQL  = "SELECT  c_asigna FROM materias_ins_int WHERE c_asigna in ".$listaAsig;
			$dSQL .= " AND exp_e='$datos_p[1]'";
            $Cdep->ExecSQL($dSQL,__LINE__,true);
            //$correctas = ($Cdep->filas == $total_mat); 
			$correctas = true;// 17/07/2015
		}            
		return ($correctas);
	}

    function borrarPreinscritas($lapso){
			    
        global $Cmat;
        global $datos_p;
        global $errstr; 
            
        $dSQL   = "DELETE FROM dace006 where status='P' ";
        $dSQL   = $dSQL . "AND exp_e='$datos_p[1]' AND lapso='$lapso'";
        $Cmat->ExecSQL($dSQL,__LINE__, true);
    }

	function asignaturaCensada($asig, $lapso, $exp) {

		global $Cmat;

		$pSQL  = "SELECT exp_e from dace006 where c_asigna='$asig' AND ";
		$pSQL .= "lapso='$lapso' AND exp_e='$exp' and status='C'";
        $Cmat->ExecSQL($pSQL,__LINE__,true);
        return ($Cmat->filas == 1);
	}

	
	function preinscAsignatura($asig, $repite, $lapso){
            
        global $Cmat;
        global $todoOK;
        global $datos_p;
        global $errstr; 
        global $fecha;
        
		if (asignaturaCensada($asig, $lapso, $datos_p[1])){
			$dSQL  = "UPDATE dace006 SET acta='0', ";
			$dSQL .= "status='P', status_c_nota='$repite', ";
			$dSQL .= "fecha='$fecha' WHERE lapso='$lapso' ";
			$dSQL .= "AND c_asigna='$asig' AND exp_e='$datos_p[1]'";
		}
		else {
			$dSQL  = "INSERT INTO dace006 (acta, lapso, c_asigna, exp_e, status, ";
			$dSQL .= "status_c_nota, fecha) VALUES ('0','$lapso','$asig', ";
			$dSQL .= "'$datos_p[1]','P','$repite','$fecha')";
		}
		$Cmat->ExecSQL($dSQL,__LINE__,true);
		$inscrita = ($Cmat->fmodif == 1);
        return($inscrita);
    }
    
    function registrarAsig() {
        
        global $ODBCSS_IP;
        global $datos_p;
        global $errstr;
        global $lapso;
        global $todoOK;
		global $Cmat;
                        
        $aInscrita = false; 
        $dAsig     = array();
        $dAsig   = explode(" ",$_POST['asignaturas']);
        array_pop($dAsig);
        $total_a = count($dAsig);
        $i = 0;
        borrarPreinscritas($lapso);// Borrar la preinscripcion del estudiante        
        while ($i<$total_a) {
            $asig = $dAsig[$i];
            $iSec = $dAsig[$i+1];
            $iRep = $dAsig[$i+2];
            if ($todoOK) {
               $todoOK = preinscAsignatura($asig, $iRep, $lapso);
                    if (!$todoOK) {
                        return $todoOK;
                    }
                }
            $i=$i+4;
        }
        return $todoOK;      
    }




    function registrarDepositos() {
        
        global $datos_p;
		global $fecha;
		global $Cmat;
		global $lapsoProceso;
               
        $pded = array();
        $mdep = array();       
        $dep = array();
        $dep = explode(" ",$_POST['depositos']);
        array_pop($dep);
		//print_r($dep);
        $total_d = count($dep);
        if ($total_d > 0) {
			$Cmat->iniciarTransaccion("usuario: ".$datos_p[1]." - Inicia transaccion.");
            $i=0;
            while($i<$total_d){
                $pdep[]=$dep[$i];
				$fdep[]=$dep[++$i];
				$hdep[]=$dep[++$i];
                $mdep[]=$dep[++$i];
                ++$i;  
            } 
            $total_d = count($mdep);
            for($i=0;$i<$total_d;$i++) {
				$sSQL = "SELECT * FROM depositos WHERE n_planilla='".$pdep[$i]."' ";
				$Cmat->ExecSQL($sSQL,__LINE__,true);
				if($Cmat->filas == 0){// Si la planilla no esta registrada previamente.

					$dSQL = " INSERT INTO depositos (n_planilla, monto, exp_e, fecha, hora, lapso) ";
					$dSQL = $dSQL."VALUES ('".$pdep[$i]."','".$mdep[$i]."','".$datos_p[1]."','".$fdep[$i]."', '".$hdep[$i]."', '".$lapsoProceso."' )";
					$Cmat->ExecSQL($dSQL, __LINE__, true);
					/*print '<pre>';
					print $dSQL;
					print '</pre>';*/				
				}
			}
        }
    }


     function imprimeH() {
        
        global $hora;
        global $ampm;
        global $datos_p;
        global $lapso, $tLapso;
        global $inscribe;
		
		$foto = $datos_p[0].".jpg";
        
        $fecha = date('d/m/Y', time() - 3600*date('I'));
        if ($inscribe == '1') {
            $titulo = "Inscripci&oacute;n";
        }
        else if ($inscribe == '2'){
            $titulo = "Inclusi&oacute;n y Retiro";
        }
        else if ($inscribe == '3'){
            $titulo = "Preinscripci&oacute;n Intensivo";
        }
        print <<<TITULO
    <tr><td class="dp">&nbsp;</td><tr> 
    <tr>
        <td width="750">
        <p class="tit14">
        Planilla de $titulo $tLapso</p></td>
    </tr>
TITULO
;
?>
    <tr><td width="750">
        <table align="center" border="0" cellpadding="0" cellspacing="1" width="550">
            <tr><td class="dp">&nbsp;</td><tr> 
            <tr><td class="dp" style="text-align: right;"> 
<?php 
        print "Fecha:&nbsp; $fecha &nbsp; Hora: $hora $ampm </td></tr>";
?>   
            <tr><td class="dp">&nbsp;</td><tr> 
 	   </table>
       </td>
    </tr>
    <tr>
		<td width="750" class="tit14">
        Datos del Estudiante
		</td>
	</tr>
    <tr><td class="dp">&nbsp;</td><tr> 
	<tr>
		<td>
        <table align="center" border="0" cellpadding="0" cellspacing="1" width="550"
				style="border-collapse: collapse;">
            <tbody>
				<tr>
					<td rowspan="3">
						<img border=1 width=115 height=140 alt="Foto" title="foto" src='/img/fotos/<?php echo $foto; ?>' >
					</td>
                    <td style="width: 250px; padding-left: 20px;" bgcolor="#FFFFFF">
                        <div class="dp">Apellidos:</div>
						<div class="dp"><?php echo $datos_p[3]." ".$datos_p[6]; ?></div>
					</td>
                    <td style="width: 250px; " bgcolor="#FFFFFF">
                        <div class="dp">Nombres:</div>
						<div class="dp"><?php echo $datos_p[2]." ".$datos_p[5]; ?></div>
					</td>
				</tr>
				<tr>
                    <td style="width: 110px;padding-left: 20px;" bgcolor="#FFFFFF">
                        <div class="dp">C&eacute;dula:</div>
						<div class="dp"><?php echo $datos_p[0]; ?></div>
					</td>
                    <td style="width: 114px;" bgcolor="#FFFFFF">
                        <div class="dp">Expediente:</div>
						<div class="dp"><?php echo $datos_p[1]; ?></div>
					</td>
                </tr>
				<tr>
                    <td style="width: 570px;padding-left: 20px;" bgcolor="#FFFFFF" colspan=2>
                        <div class="dp">Especialidad: <?php echo $_POST['carrera']; ?> </div></td>
				</tr>
			</tbody>
        </table>
    </td>
    </tr>
                   
<?php
        print <<<P002
                
P002
; 
    } //imprime_h   
?>
    
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
<?php    
    $formOK = false;
	$inscribeN = 0;
	if (isset($_SERVER['HTTP_REFERER'])) {
		$formOK = ($_SERVER['HTTP_REFERER'] == $raizDelSitio .'planilla_r.php');
	}

    if (isset($_POST['inscribe'])){
       $inscribe = $_POST['inscribe'];
       $inscribeN = intval('0'.$inscribe);
    }
    if($formOK && isset($_POST['exp_e']) && ($inscribeN>0)) {
		$lapso		= $_POST['lapso'];    
		$inscrito	= intval($_POST['inscrito']);
		$sede		= $_POST['sede'];
	    $Cmat		= new ODBC_Conn($sede,"usuario2","usuario2",$ODBCC_conBitacora, $laBitacora);
		$formOK		= leer_datos_p($_POST['exp_e']);
		
/////////////////////////////////////////////////////////////////////////////
# Consulta de datos necesarios
$Cdat = new ODBC_Conn($sede,"usuario2","usuario2", $ODBCC_conBitacora, $laBitacora);
$mSQL = "SELECT pensum,c_uni_ca FROM dace002 WHERE exp_e='".$_POST['exp_e']."'";
$Cdat->ExecSQL($mSQL, __LINE__,false);

$e=$_POST['exp_e'];
$p=$Cdat->result[0][0];
$c= $Cdat->result[0][1];

# Consulta de inscritas
$Cm = new ODBC_Conn($sede,"c","c", $ODBCC_conBitacora, $laBitacora);
$mSQL = "SELECT c_asigna FROM dace006 WHERE exp_e='".$_POST['exp_e']."' ";
$mSQL .= "and lapso='".$lapsoProceso."' and (status='7' or status='A' or status='Y')";
$Cm->ExecSQL($mSQL, __LINE__,false);

$inscritas=$Cm->result;

if (count($inscritas) > 0){
	
	# Tomamos la nuevas materias a agregar
	$materias	= array();
    $materias	= explode(" ",$_POST['asignaturas']);
    array_pop($materias);
    $total_ag = count($materias)/4;
	
	$agregadas = array();
	$i=0;
	$j=$i;
	
	while ($i<$total_ag) {
		$agregadas[] = $materias[$j];
		$j=$j+4;
		$i++;
	}
	# Fin. >>> el array $agregadas contiene los codigos de las asignaturas a agregar.

	# Tomamos la materias ya inscritas para armarlas en el array.
	$k=0;
	$ins="";
	while ($k<count($inscritas)){
		$ins.=implode($inscritas[$k])." ";
		$k++;
	}
	$inscritas	= explode(" ",$ins);
	array_pop($inscritas);
	# Fin. >>> el array $inscritas contiene los codigos de las asignaturas inscritas.
	
	# Unimos los dos arrays ($agregadas+$inscritas) para validarlas todas.
	$todas = array();
	$todas=array_merge($agregadas,$inscritas);
	# Quitamos los duplicados
	$todas = array_unique($todas);
	#contamos las asignaturas
	$todas_mat=count($todas);
	# Fin. >>> el array $todas contiene los codigos de todas asignaturas (inscritas y por agregar).
	
	
	# Consulta para Repitencia
	$repitencias = Array();
	$x=0;
	while ($x<$todas_mat) {
		$Crep = new ODBC_Conn($sede,"c","c", $ODBCC_conBitacora, $laBitacora);
		$mSQL = "SELECT repite FROM materias_ins_int ";
		@$mSQL.= "WHERE exp_e='".$e."' AND c_asigna='".$todas[$x]."'";
		$Crep->ExecSQL($mSQL, __LINE__,false);
		if(isset($Crep->result[0]))$repitencias[$x]=$Crep->result[0];
		$x++;
	}
	# Fin. >>> el array $repitencias contiene los valores de rep_sta para cada asignatura.
	
	@$maxRep=max($repitencias);
	$repite=$maxRep[0];
	#echo $repite;
	# $repite contiene el valor maximo de repitencia para validar la cantidad a cursar.
	if ($repite==1) $maxUC=18;
	elseif ($repite>=2) $maxAsig=2;
	#echo $maxUC;
	# Validacion para mas de dos repitencias
	if (isset($maxAsig)){
		if($todas_mat>$maxAsig){
					#echo "SOLO PUEDE VER DOS ASIGNATURAS <BR>";
					$formOK=false;
					echo '<script languaje=\"javacript\">alert("Lo siento, estas intentando inscribir mas asignaturas de lo permitido.\n\nIngresa de nuevo al sistema e intentalo de nuevo.");window.close();</script>';
		}
	}
	
	# Consulta de unidades de Credito y Tres Semestre Consecutivos.
	$y=0;
	$sem_alto=$y;
	$sem_bajo=15;
	$uc_ins=$y;
	# Contamos las unidades de credito y tomamos los semestres
	while ($y<$todas_mat) {
		$Crep = new ODBC_Conn($sede,"c","c", $ODBCC_conBitacora, $laBitacora);
		$mSQL = "SELECT semestre,u_creditos from tblaca009 ";
		$mSQL = $mSQL."WHERE pensum='".$p."' AND c_uni_ca='".$c."' ";
		@$mSQL = $mSQL."AND c_asigna='".$todas[$y]."'"; 
		$Crep->ExecSQL($mSQL, __LINE__,false);
		
		#Almacenamos los resultados en variables
		@$sem=$Crep->result[0][0];
		@$uc=$Crep->result[0][1];

		#Para las electivas (semestre 11) restamos 2 para que deje inscribirle
		if($sem > 10){
			$sem=$sem-2;		
		}
		
		# Capturamos el semestre mas bajo
		if($sem<=$sem_bajo){
			$sem_bajo=$sem;
		}
		
		# Capturamos el semestre mas alto
		elseif($sem>=$sem_alto){
			$sem_alto=$sem;
		}

		# Acumulamos las unidades de credito
		$uc_ins+=$uc;
		$y++;
		#echo $Crep->result[0][0]."<br>";
	}
	
	# Validacion para una repitencia (18 Unidades de Credito como Maximo)
	if (isset($maxUC)){
		if($uc_ins>$maxUC){
			#echo "SOLO PUEDE VER 18 UNIDADES DE CREDITO <BR>";
			$formOK=false;
			echo '<script languaje=\"javacript\">alert("Lo siento, estas intentando inscribir mas creditos de los permitido.\n\nIngresa de nuevo al sistema e intentalo de nuevo.");window.close();</script>';
		}
	}
	#echo $uc_ins;
	# Validacion para tres semestres consecutivos
	$dif=$sem_alto-$sem_bajo;
	if (isset($sem_bajo)&&isset($sem_alto)){
		if($dif>=3){
			#echo "VIOLA TRES SEMESTRES CONSECUTIVOS <BR>";
			$formOK=false;
			echo '<script languaje=\"javacript\">alert("Lo siento, estas intentando inscribir asignaturas con mas de tres semestres de separacion.\n\nIngresa de nuevo al sistema e intentalo de nuevo.");window.close();</script>';
		}
	}
}//Fin count($inscritas)>0*/
/////////////////////////////////////////////////////////////////////////////


		if ($formOK) {
			$formOK	= asignaturasCorrectas();
		}
	}
	if ($formOK) {
?>  

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<?php
			print $noCache; 
			print $noJavaScript; 
		?>
		<title>Planilla de Inscripci&oacute;n <?php print $tLapso; ?></title>
		<script languaje="Javascript">
		<!--
<?php
       	print "Estudiante = \"".$datos_p[2]."\";\n";
?>
        var Imprimio = false;
        
        function imprimir(fi) {
            with (fi) {
                bimp.style.display="none";
                bexit.style.display="none";
                window.print();
                Imprimio = true;
                msgI = Estudiante + ':\nSi mandaste a imprimir tu planilla\n';
                msgI = msgI + "pulsa el botón 'Finalizar' y ve a retirar tu planilla por la impresora,\n";
                msgI = msgI + 'de lo contrario vuelve a pulsar Imprimir\n';
                //alert(msgI);
                bimp.style.display="block";
                bexit.style.display="block";
            }
        }
        function verificarSiImprimio(){
            window.status = Estudiante + ': NO TE VAYAS SIN IMPRIMIR TU PLANILLA';
            if (Imprimio){
                window.close();
            }
            else {
                msgI = '            ATENCION!\n' + Estudiante;
                alert(msgI +':\nNo te vayas sin imprimir tu planilla');
            }
        }
		<!--
        document.writeln('</font>');
		//-->
        </script>
		<style type="text/css">
		<!--
		.titulo {
			text-align: center; 
			font-family:Arial; 
			font-size: 13px; 
			font-weight: normal;
			margin-top:0;
			margin-bottom:0;	
		}
		.tit14 {
			text-align: center; 
			font-family: Arial; 
			font-size: 13px; 
			font-weight: bold;
			letter-spacing: 1px;
			font-variant: small-caps;
		}

		.nota {
			text-align: justify; 
			font-family: Arial; 
			font-size: 9px; 
			font-weight: normal;
			color: black;
		}
		.mat {
			text-align: center; 
			font-family: Arial; 
			font-size: 10px; 
			font-weight: normal;
			color: black;
			vertical-align: top;
		}
		.tot {
			text-align: left; 
			font-family: Arial; 
			font-size: 10px; 
			font-weight: normal;
			color: black;
			vertical-align: top;
		}
		.matB {
			font-family:Arial; 
			font-size: 10px; 
			font-weight: bold;
			color: black; 
			text-align: center;
			vertical-align: top;
			height:20px;
			font-variant: small-caps;
		}
		.dp {
			text-align: left; 
			font-family: Arial; 
			font-size: 14px;
			font-weight: bold;
			background-color: #FFFFFF; 
			font-variant: small-caps;
		}
		.dp1 {
			text-align: center; 
			font-family: Arial; 
			font-size: 11px;
			font-weight: normal;
			background-color: #FFFFFF; 
			font-variant: small-caps;
		}
		.depo {
			text-align: center; 
			width: 150px;
			background-color: #FFFFFF;
            font-size: 12px;
			color: black;
			font-family: courier;
		}
		-->
		</style>
		</head>
        <body  <?php global $botonDerecho; echo $botonDerecho; ?> onload="javascript:self.focus();" 
		      onclose="return false">
		<table align="left" border="0" width="750" id="table1" cellspacing="1" cellpadding="0" 
			   style="border-collapse: collapse">
    <tr><td>
		<table border="0" width="750" cellpadding="0">
		<tr>
		<td width="125">
		<p align="right" style="margin-top: 0; margin-bottom: 0">
		<img border="0" src="/img/logo_unexpo.png" 
		     width="50" height="50"></p></td>
		<td width="500">
		<p class="titulo">
		Universidad Nacional Experimental Polit&eacute;cnica</p>
		<p class="titulo">
		Vicerrectorado <?php echo $vicerrectorado; ?></font></p>
		<p class="titulo">
		<?php echo $nombreDependencia ?></font></td>
		<td width="125">&nbsp;</td>
		</tr><tr><td colspan="3" style="background-color:#D0D0D0;">
		<font style="font-size:1pt;"> &nbsp;</font></td></tr>
	    </table></td>
    </tr>
<?php
		
		registrarDepositos();
		$inscOK = registrarAsig();
        if ($inscOK){
            $datosOK = consultarDatos();
			if ($datosOK) {
				imprimeH();
				reportarPreInscripcion();
			}
			else {
                imprimeH();
                reportarError($errstr);
			}
                print <<<FINAL1
        </td></tr>
        </table>
        </body>
		$noCacheFin
        </html>
FINAL1
;
                exit;        
        }//if insc_ok
        else {
            imprimeH();
            reportarError($errstr);
            print <<<FINAL2
        </td></tr>
        </table>
        </body>
        </html>
FINAL2
;        
        }
    } //if($formOK)
    else {
?>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <META HTTP-EQUIV="Refresh"
        CONTENT="25;URL=<?php echo $raizDelSitio; ?>">
        </head>
        <body>
        </body>
        </html>
<?php
    }

?>
