<?php 

require('mysql.php');
require('menu.php'); 

?>

<h1>Historia</h1>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Wiadomość</th>
        </tr>
    </thead>
    
    <?php
        $wyswietlLogi = $mysqli -> query('SELECT * FROM `log` ORDER BY `log`.`id` DESC');        
        while ($logi = $wyswietlLogi->fetch_assoc()) {
	
			$akcja = $logi['akcja'];
			if($akcja == 'pobrał') {
				$tr =  "<tr class=\"alert alert-warning\" role=\"alert\">";
			} else {
				$tr = "<tr class=\"alert alert-success\" role=\"alert\">";
			}
			
            echo $tr;
            echo "<td>".$logi['id']."</td>";
            echo "<td>".$logi['data']."- pracownik <b>".$logi['user']." </b>".$akcja."<b> ".$logi['ile_akcja']." ".$logi['produkt']."</b> (Było <b>".$logi['ostatniaLiczba']."</b> jest <b>".$logi['aktualnaLiczba']."</b>)</td>";
            echo "</tr>";

        }
    ?>
</table>

</body>


</html>
