<?php

require('header.php');

if (isset($_GET['wyloguj'])==1) {
	$_SESSION['zalogowany'] = false;
	session_destroy();
}

   
if(!isset($_SESSION['zalogowany'])) {
	$_SESSION['zalogowany'] = false;
}


function filtruj($zmienna) {
    if(get_magic_quotes_gpc())
        $zmienna = stripslashes($zmienna); // usuwamy slashe
 
   // usuwamy spacje, tagi html oraz niebezpieczne znaki
    return mysql_real_escape_string(htmlspecialchars(trim($zmienna)));
}
 
if (isset($_POST['loguj'])) {
   $login = $_POST['login'];
   $haslo = $_POST['haslo'];
 
   // sprawdzamy czy login i hasło są dobre
    $checkLogin = $mysqli -> query("SELECT login, password FROM user WHERE login = '".$login."' AND password = '".$haslo."';");
    $checkIsTrue = $checkLogin -> num_rows;
    
   if ($checkIsTrue > 0) {
 
      $_SESSION['zalogowany'] = true;
      $_SESSION['login'] = $login;	
 
 
   }
   else echo "Wpisano złe dane.";
}
    

//WYSWIETLA SIE STRONA
if ($_SESSION['zalogowany']==true) {
	require('menu.php'); 
?>



<form action="index.php" method="post">
    <div class="form-group">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Przedmiot</th>
                    <th scope="col">Minimum</th>
                    <th scope="col">Stan</th>
                    <th scope="col">Akcja</th>
                </tr>
            </thead>
            <?php      
        
			function aktualizujHistorie($typAkcji,$produkt,$iloscPrzedmiotow,$ileBylo,$ileJest,$login) {
				global $mysqli;
				$dodajHistorie = "INSERT INTO `log` (`id`, `produkt`, `ostatniaLiczba`, `aktualnaLiczba`, `user`, `data`, `akcja`, `ile_akcja`) VALUES (NULL, '".$produkt."', '".$ileBylo."', '".$ileJest."', '".$login."', '".date("Y-m-d H:i:s")."', '".$typAkcji."', '".$iloscPrzedmiotow."')";
				$mysqli -> query($dodajHistorie);
			}	
	
        //Aktualizacja bazy po kliknieciu
        if(isset($_POST['ilosc1'])) {            
            //$wynik2 przechowuje całą listę przedmiotów
            $wynik2 = $mysqli -> query('SELECT * FROM magazyn');
            //Sprawdza ile jest przedmiotów
            $count = $wynik2 -> num_rows;            
            for($j=1;$j<=$count;$j++) {                   
                
                $query2 = "SELECT * FROM magazyn WHERE id=".$j;
                $result2 = mysqli_query($mysqli, $query2);
                $data = mysqli_fetch_array($result2);         
                //echo 'ilosc z bazy: '.$data['ilosc'].' ilosc z post: '.$_POST['ilosc'.$j]. "</br>";
						
				if($_POST['ilosc'.$j]<0) {
					$typAkcji = 'pobrał';
					$ileWzial = intval(str_replace('-','',$_POST['ilosc'.$j]));
					$liczIloscPrzedmiotow = $data['ilosc'] - $ileWzial;
                    $update = "UPDATE `magazyn` SET ilosc='".$liczIloscPrzedmiotow."' WHERE id=".$j;
                    $mysqli -> query($update);				
					aktualizujHistorie($typAkcji,$data['produkt'],$ileWzial,$data['ilosc'],$liczIloscPrzedmiotow,$_SESSION['login']);
					
				}				
			
				if($_POST['ilosc'.$j]>0) {
					$typAkcji = 'dodał';
					$ileDodal = intval(str_replace('-','',$_POST['ilosc'.$j]));
					$liczIloscPrzedmiotow = $data['ilosc'] + $ileDodal;
                    $update = "UPDATE `magazyn` SET ilosc='".$liczIloscPrzedmiotow."' WHERE id=".$j;
                    $mysqli -> query($update);						
					aktualizujHistorie($typAkcji,$data['produkt'],$ileDodal,$data['ilosc'],$liczIloscPrzedmiotow,$_SESSION['login']);
					
				}
					
            }
        }       
            
    $wynik = $mysqli -> query('SELECT * FROM magazyn');
	//zmienna sprawdza czy z stanu zoltego przechodzi na stan zielony
	//zmienna sprawdza czy z stanu zielonego przechodzi w stan zolty lub czerwony
	$k = 0;
	//id
   	$i = 0;
	$zamowienie = '';
	
    while ($r = $wynik->fetch_assoc()) {
        $i++;
		
		$ostrzezenie = $r['min'] * 1.5;
		
		//ponizej minimum
		if($r['ilosc'] <= $r['min']) {
			echo "<tr class=\"alert alert-danger\" role=\"alert\">";
			$zamowienie .="<span style=\"color:#721c24\"><b>". $r['produkt'] ."</b> Ilość: <b>". $r['ilosc']."</b> / Minimalna liczba: <b>".$r['min'] ."</b></span><br><br>";
		}
		//ostrzezenie
		if($r['min'] <= $r['ilosc'] &&  $r['ilosc'] <= $ostrzezenie) {
			echo "<tr class=\"alert alert-warning\" role=\"alert\">";
			$zamowienie .="<span style=\"color:#856404\"><b>". $r['produkt'] ."</b> Ilość:<b> ". $r['ilosc']."</b> / Minimalna liczba: <b>".$r['min'] ."</b></span><br><br>";
		}
		//odpowiednia ilosc
		if($ostrzezenie < $r['ilosc']) {
			echo "<tr class=\"alert alert-success\" role=\"alert\">";
		}      
     
           // echo "<td> ".$r["id"]."</td>";  
            echo "<td> ".$r["produkt"]."</td>";  
            echo "<td> ".$r["min"]."</td>";  
            echo "<td> ".$r["ilosc"]."</td>";    
            echo "<td>
				<div>
					<input type='button' value='-' class='minus'>
					<input type=\'text\' size='2' class='value' value='0' name='ilosc".$i."'>
					<input type='button' value='+' class='plus'>
				</div>
				</td>";  
        echo "</tr>";
	} //koniec petli while

	/*
if(isset($_POST['ilosc1'])) {
	global $zamowienie;
    //Naglowki mozna sformatowac tez w ten sposob.
	$naglowki = '';
   $naglowki .= "From: STAS- System traktujacy anomalie stanowe <mosakamil@gmail.com>".PHP_EOL;
   $naglowki .= "MIME-Version: 1.0".PHP_EOL;
   $naglowki .= "Content-type: text/html; charset=utf-8".PHP_EOL; 

   //Wiadomość najczęściej jest generowana przed wywołaniem funkcji
   $wiadomosc = '<html> 
   <head> 
      <title>Ostrzeżenie przed małą ilością stanów w magazynie</title> 
   </head>
   <body>
      <p><b>Treść wiadomości</b>: To jest treść wiadomości z formatowaniem HTML.</p>
	  <p>'.$zamowienie.'</p>
   </body>
   </html>';


	   if(mail('mossakowski.kamil@gmail.com', 'MAGAZYN- Ostrzeżenie przed małą ilością stanów', $wiadomosc, $naglowki))
	   {
	      echo 'Wiadomość została wysłana';
	   } else {
	       echo 'kapa';
	   }  	
}
	*/
?>
        </table>
        <input id="updateList" type="submit" value="Aktualizuj">
    </div>
</form>
<?php			
			
//koniec zalogowanego kodu
}
               
?>






<?php if ($_SESSION['zalogowany']==false): ?>
<div class="container full-height d-flex justify-content-center align-items-center">
    <form method="POST" action="index.php">
        <div class="form-group">
            <label><b>Login:</b></label> <input type="text" name="login"><br>
            <label><b>Hasło:</b></label> <input type="password" name="haslo"><br>
            <input type="submit" value="Zaloguj" name="loguj">
        </div>
    </form>
</div>
<?php endif; ?>



<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</body>

</html>
