<html>
	<head>
		<title>Cerca</title>
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
		<link rel="stylesheet" type="text/css" href="stile.css">
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
			echo "<table class='flat-table'>";
			echo"<tbody>";
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
			echo"</tbody>";
			echo "</table>";
			curl_close($ch);
			echo "<form id='forma' method='post' onsubmit='return controllo();'><br/>";
			echo "<table align='center' class='flat-table'>";
			echo"<tbody>";
			echo "<tr>";
			echo " <td>Numero righe (1-50): </td><td><input type='text' value='$tot' name='tot' id='tot' /></td>";
			echo "</tr>";
			echo "<tr>";
			echo " <td>Citta': </td><td><input type='text' value='$citta' name='citta' id='citta' /></td>";
			echo "</tr>";
			echo "<tr>";
			echo " <td>Cosa vuoi cercare?: </td><td><input type='text' value='$qu' name='qu' id='qu' /></td><br/>";
			echo "</tr>";
			echo"</tbody>";
			echo "</table>";
			echo " <input type='submit' value='Aggiorna tabella' class='btn'/>";
			echo "</form>";
		?>
	</body>
</html>
