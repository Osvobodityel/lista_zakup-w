<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Lista Zakupów</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="all">
	<div id="form1">
		<form action="lista.php" method="POST">
			Nazwa produktu: <input type="text" name="nazwa" maxlength="20" class="input"><br>
			Cena produktu (w PLN): <input type="number" name="cena" step="any" maxlength="5" class="input"><br>
			Ilość: <input type="number" name="ilosc" maxlength="3" class="input"><br>
			<input type="submit" name="submit" value="Dodaj produkty">
		</form>
	</div>

	<?php
		ob_start();
		$con = mysqli_connect("localhost", "root", "", "lista")or die("Błąd przy połączeniu z bazą danych");
		$zap = "SELECT * FROm lista";
		if(isset($_POST['submit'])) {
			$n = $_POST['nazwa'];
			$c = $_POST['cena'];
			$i = $_POST['ilosc'];
			if(!empty($n)&&!empty($c)&&!empty($i)){
				$zap2 = "INSERT INTO lista VALUES (NULL, '$n', '$i', '$c') ";
				mysqli_query($con, $zap2);
			}
		}


	?>

	<div id="list">
		<table>
			<tr>
				<th>Nazwa produktu</th>
				<th>Cena produktu (PLN)</th>
				<th>Ilość produktów</th>
				<th>Usuń</th>
				<th>Edytuj</th>
				<th>Wartość</th>
			</tr>
			<?php

				$wynik = mysqli_query($con, $zap);
				$licznik = 1;
				$w2=0;

				while ($k=mysqli_fetch_array($wynik)) {
					echo "<tr><td>".$k['nazwa']."</td><td>".$k['cena']."</td><td>".$k['ilosc']."</td><td><a href=\"lista.php?a=usun&amp;id={$k['id']}\"><button>Usuń</button></a></td><td><a href=\"lista.php?a=edytuj&amp;id={$k['id']}\"><button>Edytuj</button></a></td><td>".$w=$k['cena']*$k['ilosc']."</td></tr>";
					@$w2=$w2+$w;
					$licznik++;
				}
			 ?>
		</table>
		<?php
			echo "<p>Łączny koszt zakupów: ".$w2." PLN</p>";


		?>

	</div>

	<?php

		@$a=trim($_REQUEST['a']);
		@$id=trim($_GET['id']);


		if ($a=='usun' and !empty($id)) {
			$u="DELETE FROM lista WHERE id='$id'";
			mysqli_query($con, $u);
			header('Location:lista.php');
		}

		if ($a=='edytuj' and !empty($id)) {
			$edycja="SELECT * FROM lista WHERE id='$id'";
			$wynik=mysqli_query($con, $edycja);
			$edytowany=mysqli_fetch_array($wynik);
			echo '<form action="lista.php" method="POST" id="form2">
					<input type="hidden" name="a" value="zapisz" class="input">
					<input type="hidden" name="id" value="'.$id.'" class="input">
					Nazwa: <input type="text" name="nazwa" maxlength="20" value="'.$edytowany['nazwa'].'" class="input"><br>
					Cena: <input type="number" name="cena" maxlength="5" value="'.$edytowany['cena'].'" class="input"><br>
					Ilość: <input type="number" name="ilosc" maxlength="3" value="'.$edytowany['ilosc'].'" class="input"><br>
					<input type="submit" value="Zmień">
				</form>';
		}

		if ($a=='zapisz') {
			$id=$_POST['id'];
			$nazwa=trim($_POST['nazwa']);
			$cena=trim($_POST['cena']);
			$ilosc=trim($_POST['ilosc']);
			$aktualizacja="UPDATE lista SET nazwa='$nazwa', cena='$cena', ilosc='$ilosc' WHERE id='$id'";
			mysqli_query($con, $aktualizacja);
			header('Location:lista.php');
		}

		mysqli_close($con);
	?>
</div>
</body>
</html>