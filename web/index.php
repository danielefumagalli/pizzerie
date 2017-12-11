<html>
	<head>
		<title>Cerca</title>
		<style>
			@import url('https://fonts.googleapis.com/css?family=Fira+Sans');
			body {
  				background-color: white;
  				font-family: "Fira+Sans", helvetica, arial, sans-serif;
  				font-size: 15px;
  				font-weight: 400;
  				text-rendering: optimizeLegibility;
				}

			div.table-title {
  				display: block;
  				margin: auto;
				max-width: 600px;
				padding:5px;
				width: 100%;
				}

			.table-title h3 {
			   color: #3366CC;
			   font-size: 30px;
			   font-weight: 400;
			   font-style:normal;
			   font-family: "Fira+Sans", helvetica, arial, sans-serif;
			   text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
			   text-transform:uppercase;
			}

			.table-fill {
			  background: white;
			  border-radius:3px;
			  border-collapse: collapse;
			  height: 320px;
			  margin: auto;
			  max-width: 600px;
			  padding:5px;
			  width: 100%;
			  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
			  animation: float 5s infinite;
			}

			th {
			  color:#D5DDE5;
			  background:#1b1e24;
			  border-bottom:4px solid #9ea7af;
			  border-right: 1px solid #343a45;
			  font-size:23px;
			  font-weight: 100;
			  padding:24px;
			  text-align:left;
			  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
			  vertical-align:middle;
			}

			th:first-child {
			  border-top-left-radius:3px;
			}

			th:last-child {
			  border-top-right-radius:3px;
			  border-right:none;
			}

			tr {
			  border-top: 1px solid #C1C3D1;
			  border-bottom-: 1px solid #C1C3D1;
			  color:#666B85;
			  font-size:16px;
			  font-weight:normal;
			  text-shadow: 0 1px 1px rgba(256, 256, 256, 0.1);
			}

			tr:hover td {
			  background:#66FF33;
			  color:#FFFFFF;
			  border-top: 1px solid #22262e;
			}

			tr:first-child {
			  border-top:none;
			}

			tr:last-child {
			  border-bottom:none;
			}

			tr:nth-child(odd) td {
			  background:#EBEBEB;
			}

			tr:nth-child(odd):hover td {
			  background:#66FF33;
			}

			tr:last-child td:first-child {
			  border-bottom-left-radius:3px;
			}

			tr:last-child td:last-child {
			  border-bottom-right-radius:3px;
			}

			td {
			  background:#FFFFFF;
			  padding:20px;
			  text-align:left;
			  vertical-align:middle;
			  font-weight:300;
			  font-size:18px;
			  text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
			  border-right: 1px solid #C1C3D1;
			}

			td:last-child {
			  border-right: 1px;
			}

			th.text-left {
			  text-align: left;
			}

			th.text-center {
			  text-align: center;
			}

			th.text-right {
			  text-align: right;
			}

			td.text-left {
			  text-align: left;
			}

			td.text-center {
			  text-align: center;
			}

			td.text-right {
			  text-align: right;
			}

			.btn {
			    width: 451px;
			    background-color: #4CAF50;
			    color: white;
			    padding: 14px 20px;
			    margin: 8px 0;
			    border: none;
			    border-radius: 4px;
			    cursor: pointer;
			}

			.btn:hover {
			    background-color: #FF3300;
			}
		</style>
		<script>
			function controllo()
			{
				var valore=document.getElementById("tot").value;
				var esito=false;
				var verifica=/^\d{1,2}$/
				if(document.getElementById("tot").value!=""&&document.getElementById("citta").value!=""&&document.getElementById("qu").value!="")
					if(valore.match(verifica)&&parseInt(valore)<51)
						esito=true;
				return esito;
			}
		</script>
	</head>
	<body>
		<?php
			if(isset($_POST["tot"]))
			{
				$tot=$_POST["tot"];
			}
			else
			{
				$tot=20;
			}
			if(isset($_POST["citta"]))
			{
				$citta=$_POST["citta"];
			}
			else
			{
				$citta="bergamo";
			}
			if(isset($_POST["qu"]))
			{
				$qu=$_POST["qu"];
			}
			else
			{
				$qu="pizzeria";
			}
			# Indirizzo dell'API da richiedere
			$indirizzo_pagina="https://api.foursquare.com/v2/venues/search?v=20161016&query=$qu&limit=$tot&intent=checkin&client_id=SPCT4IEW2KZ2QI4BVHRPF42I40TCQEMQHWFHFHZEMBQYBGI5&client_secret=HISBBKGJ3RSH54EVH00YP3ZFLSXIO1ABH0H4VYH5LWXPN4WC&near=$citta";
			# Chiama l'API e la immagazzina in $json
			$ch = curl_init() or die(curl_error());
			curl_setopt($ch, CURLOPT_URL,$indirizzo_pagina);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$json=curl_exec($ch) or die(curl_error());
			# Decodifico json
			$data = json_decode($json);
			echo "<table>";
				echo "<tr>";
					echo "<th>NOME</th>";
					echo "<th>LATITUDINE</th>";
					echo "<th>LONGITUDINE</th>";
				echo "</tr>";
				for($i=0; $i<$tot; $i++)
				{
					echo "<tr>";
						echo "<td>";
						echo $data->response->venues[$i]->name;
						echo "</td>";
						echo "<td>";
						echo $data->response->venues[$i]->location->lat;
						echo "</td>";
						echo "<td>";
						echo $data->response->venues[$i]->location->lng;
						echo "</td>";
					echo "</tr>";
				}
			echo "</table>";
			curl_close($ch);
			echo "<form id='forma' method='post' onsubmit='return controllo();'><br/>";
			echo "<table>";
			echo "<tr>";
			echo " <td>Numero righe (1-50): </td><td><input type='text' value='$tot' name='tot' id='tot' /></td>";
			echo "</tr>";
			echo "<tr>";
			echo " <td>Citta': </td><td><input type='text' value='$citta' name='citta' id='citta' /></td>";
			echo "</tr>";
			echo "<tr>";
			echo " <td>Cosa vuoi cercare?: </td><td><input type='text' value='$qu' name='qu' id='qu' /></td><br/>";
			echo "</tr>";
			echo "</table>";
			echo " <input type='submit' value='Aggiorna tabella' class='btn'/>";
			echo "</form>";
		?>
	</body>
</html>
