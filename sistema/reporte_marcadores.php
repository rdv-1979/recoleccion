<?php
    require('../fpdf/fpdf.php');
    require('../bd/conexion.php');

    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFont('Arial','B',8);
    $pdf->Image('../imagenes/logo.png',10,12,30,0,'');
    $fecha = date(':i:s');

    $fecha_actual = "Hoy ".date('d')." de ".date('m').utf8_decode(" del año ").date('Y');
    $hora_actual = date('H')-5;
  
    $pdf->Cell(100,20);
    $pdf->Cell(130,20,$fecha_actual.' '.$hora_actual.''.$fecha,0,1,'C');

    $pdf->SetFont('Arial','I',16);

    $pdf->Cell(55,10);
    $pdf->Cell(75,10,utf8_decode('Sistema de recolección!'),1,1,'C');
        
    $pdf->Cell(55,10,"",0,1);
    $pdf->SetFont('Arial','B',8);
    $pdf->SetDrawColor(0,80,180);
    $pdf->SetFillColor(10,134,112);
    $pdf->SetTextColor(45,23,89);
    $pdf->Cell(10,10,"ID",1,0,'C',True);
    $pdf->Cell(30,10,"Usuario",1,0,'C',True);
    $pdf->Cell(30,10,"Tipo",1,0,'C',True);
    $pdf->Cell(30,10,"Tipo marcador",1,0,'C',True);
    $pdf->Cell(30,10,"Calle",1,0,'C',True);
    $pdf->Cell(30,10,"Fecha",1,0,'C',True);
    $pdf->Cell(20,10,"Estado",1,1,'C',True);

    include('../bd/conexion.php');

    $id = $_REQUEST['id']; 
    
    $sql = mysqli_query($conexion, "SELECT *, s.id as id_marcadores FROM salvar_marcadores s INNER JOIN usuarios u ON
                                    s.usuario_id=u.id WHERE s.id='$id'");
    
    while($datos = mysqli_fetch_array($sql)){
        $pdf->Cell(10,10,$datos['id_marcadores'],1,0,'C');
        $pdf->Cell(30,10,utf8_decode($datos['usuario']),1,0,'C');
        $pdf->Cell(30,10,utf8_decode($datos['tipo']),1,0,'C');
        $pdf->Cell(30,10,utf8_decode($datos['tipo_marcador']),1,0,'C');
        $pdf->Cell(30,10,utf8_decode($datos['calle']),1,0,'C');
        $pdf->Cell(30,10,$datos['fecha_hora'],1,0,'C');
        if($datos['estado'] == '1'){       
            $pdf->Cell(20,10,"Pendiente",1,1,'C');
        }else{
            $pdf->Cell(20,10,"Borrado",1,1,'C');
        }
    }

    $pdf->Output();
?>