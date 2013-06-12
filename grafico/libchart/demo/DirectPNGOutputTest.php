<?php
	include "../libchart/classes/libchart.php";

	header("Content-type: image/png");

	$chart = new PieChart(900, 500);

	$dataSet = new XYDataSet();
	$dataSet->addPoint(new Point("Sucursal 1", 10));
	$dataSet->addPoint(new Point("Sucursal 2", 40));
	$dataSet->addPoint(new Point("Sucursal Central", 20));
	$dataSet->addPoint(new Point("Sucursal Farell", 30));
	$chart->setDataSet($dataSet);

	$chart->setTitle("Reporte de Las Ventas Por Sucursal");
	$chart->render();
?>