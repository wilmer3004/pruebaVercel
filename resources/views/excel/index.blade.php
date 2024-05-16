
{{-- Tabla que se convierte en excel para descarga --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Excel</title>
@vite(['resources/js/app.js'])
</head>
<body>

<table id="tableExcel" style="display: none; ">
    <tr class="meses">
        <td colspan="8" style="text-align: center; font-size: 20px; font-family: Yu gothic; font-weight: bold; height: 35px; background-color: rgb(208, 214, 245); border-right: 1px solid black">PROGRAMACION FORMACION TITULADA PRESENCIAL</td>
    </tr>
    <tr class="nameDays">
        <td colspan="8" style="text-align: center; font-size: 20px; font-family: Yu gothic; font-weight: bold; height: 35px; background-color: rgb(208, 214, 245); border-right: 1px solid black">CENTRO DE SERVICIOS FINANCIEROS</td>
    </tr>
    <thead style="" class="Days">
        <tr>
            <th style="width: 150px; height: 40px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Ambiente <button style="cursor: pointer; border: none; background-color: transparent"></button></th>
            <th style="width: 130px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Ficha <button style="cursor: pointer; border: none; background-color: transparent"></button></th>
            <th style="width: 130px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Trimestre</th>
            <th style="width: 300px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Programa</th>
            <th style="width: 110px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Fecha Fin Lectiva</th>
            <th style="width: 150px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Competencia</th>
            <th style="width: 250px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Instructor</th>
            <th style="width: 130px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Hora</th>
        </tr>
    </thead>
    <tbody id="trbody">

    </tbody>
</table>


</body>
</html>


{{-- Tabla para ver los trimestres y las fichas que esta programadas en ese trimestre --}}
<button type="button" class="btn btn-primary" data-toggle="modal" onclick="abrirModal()">
<i class="fa-regular fa-file-excel me-2"></i>
Descargar excel
</button>


<!-- Modal -->
<div class="modal fade" id="tableTrimestres" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Trimestres</h5>
        </div>
    <div class="modal-body" style="width: 100%">

        <table id="tableTrimestre" style="width: 100%" >

        </table>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" data-tooltip="Cerrar">Close</button>
    </div>
</div>
</div>
</div>


@include('excel.js')

