<?php 
	include ("hasil_proses.php");
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	
<body background="88.jpg">
 	<title>JST</title>
 	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>  	
	<script type="text/javascript" src="js/npm.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$("#btn-data").click(function () {
				$("#data_asli").hide();
				$("#data_angka").fadeIn();
			})

			$("#btn-angka").click(function () {
				$("#data_asli").fadeIn();
				$("#data_angka").hide();
			})

			$("#btn-normal").click(function () {
				$("#data_normal").slideToggle();
			})

			$("#btn-hitung").click(function () {
				$("#data_hitung").slideDown();
			})
			
		})
	</script>	
 </head>
 <body>
 	<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-link" href="halamanawal.php"><button class="btn btn-primary" >Klasifikasi Gizi Mahasiswa</button></a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active"><a href="halamanawal.php">Home</a></li>
					</ul>
				</div>
			</div>
	</nav>

	<div class="container" style="margin-top: 80px;">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3>Data Gizi Mahasiswa</h3>
			</div>
			<div class="panel-body" id="data_asli">
				<table class="table table-striped table-hover">
					<thead>
						<th>Data ke</th>
						<th>Tinggi Badan</th>
						<th>Berat Badan</th>
						<th>Aktivitas</th>
						<th>Status Gizi</th>
					</thead>
					<tbody>
						<?php 
							for ($i=1; $i <= $jml_data; $i++) { 
								echo "<tr>";
								echo "<td>". $i ."</td>";
								echo "<td>". $tinggiBadan[$i] ."</td>";
								echo "<td>". $beratBadan[$i] ."</td>";
								echo "<td>". $aktivitas[$i] ."</td>";
								echo "<td>". $target[$i] ."</td>";
								echo "</tr>";
							}
						 ?>
					</tbody>
				</table>
				<button type="button" class="btn btn-primary btn-block" id="btn-data">Data Gizi Dalam Bentuk Angka</button>
			</div>

			<div class="panel-body" id="data_angka" style="display: none;">
				<table class="table table-striped table-hover">
					<thead>
						<th>Data ke</th>
						<th>Tinggi Badan</th>
						<th>Berat Badan</th>
						<th>Aktivitas</th>
						<th>Status Gizi</th>
					</thead>
					<tbody>
						<?php 
							for ($i=1; $i <= $jml_data; $i++) { 
								echo "<tr>";
								echo "<td>". $i ."</td>";
								echo "<td>". $tinggiBadan[$i] ."</td>";
								echo "<td>". $beratBadan[$i] ."</td>";
								echo "<td>". $aktivitasAngka[$i] ."</td>";
								echo "<td>". $targetAngka[$i] ."</td>";
								echo "</tr>";
							}
						 ?>
					</tbody>
				</table>
				<button type="button" class="btn btn-primary btn-block" id="btn-angka">Data Gizi Asli</button>
				<button type="button" class="btn btn-danger btn-block" id="btn-normal">Normalisasi Data</button>
			</div>
		</div>

		<div class="panel panel-primary" id="data_normal" style="display: none;">
			<div class="panel-heading">
				<h3>Data Hasil Normalisasi</h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped table-hover">
					<thead>
						<th>Data ke</th>
						<th>Tinggi Badan</th>
						<th>Berat Badan</th>
						<th>Aktivitas</th>
						<th>Status Gizi</th>
					</thead>
					<tbody>
						<?php 
							for ($i=1; $i <= $jml_data; $i++) { 
								echo "<tr>";
								echo "<td>". $i ."</td>";
								echo "<td>".$normalisasiTB[$i]."</td>";
								echo "<td>".$normalisasiBB[$i]."</td>";
								echo "<td>".$normalisasiAk[$i]."</td>";
								echo "<td>".$targetAngka[$i]."</td>";
								echo "</tr>";
							}
						 ?>
					</tbody>
				</table>
				<button type="button" class="btn btn-primary btn-block" id="btn-hitung">Hitung Dengan Perceptron</button>
			</div>
		</div>
	
		<div class="panel panel-primary" id="data_hitung" style="display: none">
			<div class="panel-heading">
				<h3>Hasil Perhitungan</h3>
			</div>
			<div class="panel-body">
			<?php
			include ('metode_perceptron.php');
			$epoh = 1;
			$perceptron = new Perceptron();
			while (true) {
				echo "<h4>EPOH ke-$epoh</h4>";
				for ($i=1; $i <= $jml_data; $i++) {
					$y_in = $perceptron->hitung_yin($normalisasiTB[$i],$normalisasiBB[$i],$normalisasiAk[$i]);
					$hasilAktivasi = $perceptron->set_aktivasi($y_in);
					$perceptron->cek_target($targetAngka[$i],$hasilAktivasi,$normalisasiTB[$i],$normalisasiBB[$i],$normalisasiAk[$i]);
					echo "<table class='table table-striped table-hover'>
							<thead>
							<th>Data ke</th>
							<th>y_in</th>
							<th>hasil_aktivasi</th>
							<th>target</th>
							<th>Bobot dan Bias</th>
							</thead>
							<tbody>
							<tr>
							<td>$i</td>
							<td>".round($y_in,2)."</td>
							<td>$hasilAktivasi</td>
							<td>".$targetAngka[$i]."</td>
							<td>W1 = ".$perceptron->get_bobot1().", W2 = ".$perceptron->get_bobot2().", W3 = ".$perceptron->get_bobot3().", B = ".$perceptron->get_bias()."</td>
							</tr>
							</tbody>
						  </table>";
					}
					if($perceptron->get_batas() == $jml_data){
						break;
					}else {
						$perceptron->set_batas();
						$epoh++;
					}
				}
			?>
			</div>
		</div>

	</div>
 </body>
 </html>