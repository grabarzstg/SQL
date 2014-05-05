<!DOCTYPE html>
<html> 
    <head> 
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" >
        <link rel="Stylesheet" type="text/css" href="style.css" />
        <title>Strona Glowna</title> 
    </head> 
    <body>
        <div id="main">
        <?php
        require("login.php");
        
        //wczytanie pliku konfiguracyjnego
        require("config.php");

        //nawiazanie polaczenia z baza danych
        try {
            $pdo = new PDO("$szbd:host=$host;dbname=$dbname;port=$port", $login, $haslo);
           // echo 'Polaczenie nawiazane!';  //w razie potrzeby usun ten komunikat
            //chcemy, aby ewentualne bledy w zapytaniach raportowane byly jako wyjatki
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Polaczenie nie moglo zostac utworzone: ' . $e->getMessage();
            exit(1);
        }
        // END OF CONNECT -------------------------




        try {


            function wyswietl($int, $pdo) {
                $stm = $pdo->query("SELECT * FROM kategoria WHERE id_nadkategoria=$int;");
                foreach ($stm as $rw) {
                    echo '<ul>';
                    echo "<li><a href='display.php?nr=".$rw['id_kategoria']."'>". $rw['nazwa'] . ' ' . $rw['id_nadkategoria'] ."</a> </li>";
                   

                    wyswietl($rw['id_kategoria'], $pdo);
                }
                echo '</ul>';
            }
            
            
                function wyswietl2($int, $pdo) {
                $stm = $pdo->query("SELECT * FROM kategoria WHERE id_nadkategoria=$int;");
                foreach ($stm as $rw) {
                    
                    echo "<a href='display.php?nr=".$rw['id_kategoria']."'>". $rw['nazwa'] . ' ' . $rw['id_nadkategoria'] ."</a>";
                    echo '&nbsp';
                   

                    wyswietl2($rw['id_kategoria'], $pdo);
                }
       
            }

            echo "<h2><strong>Zarzadzanie kategoriami :</strong></h2>";
            $stmt = $pdo->query('SELECT * FROM kategoria;');
            $a = 1;

            foreach ($stmt as $row) {

                $view_roz[$a] = "";
                $view_zwin[$a] = "hidden";
                
                $view_rozP[$a] = "";
                $view_zwinP[$a] = "hidden";
                
                if ($row['id_nadkategoria'] == 0) {
                    $id_kat = $row['id_kategoria'];

echo "<a href='display.php?nr=".$row['id_kategoria']."'>". $row['nazwa']."</a> ";
echo '&nbsp'.'&nbsp'.'&nbsp'.'&nbsp';
                    //echo '<li>' . $row['nazwa'] . '</li>';
                    $post_kat = filter_input(INPUT_POST, "rozwin_kat$a");
                    $post_zwin = filter_input(INPUT_POST, "zwin_kat$a");
                    if ($post_kat === "rozwin$a") { //$row['id_nadkategoria']==0
                        wyswietl($id_kat, $pdo);

                        $view_roz[$a] = "hidden";
                        $view_zwin[$a] = "";
                    } else if ($post_zwin === "rozwin$a") {
                        $view_roz[$a] = "";
                        $view_zwin[$a] = "hidden";
                    }

                    if ($post_kat === "rozwinP$a") { //$row['id_nadkategoria']==0
                        wyswietl2($id_kat, $pdo);

                        $view_rozP[$a] = "hidden";
                        $view_zwinP[$a] = "";
                    } else if ($post_zwin === "rozwinP$a") {
                        $view_rozP[$a] = "";
                        $view_zwinP[$a] = "hidden";
                    }





                    // } 
                    echo '</ul>';





                    echo'  <form action="index.php" method="post"><input type="text" value="';
                    echo "rozwin$a";
                    echo '" name="';
                    echo "rozwin_kat$a";
                    echo '" hidden> <button  type="submit" ';
                    echo "$view_roz[$a]>Pionowo</button></form> ";

                                        echo'  <form action="index.php" method="post"><input type="text" value="';
                    echo "rozwinP$a";
                    echo '" name="';
                    echo "rozwin_kat$a";
                    echo '" hidden> <button  type="submit" ';
                    echo "$view_rozP[$a]>Poziomo</button></form> </br>";
                    
                    
                   // ----------

                    echo'  <form action="index.php" method="post"><input type="text" value="';
                    echo "zwin$a";
                    echo '" name="zwin_kat" hidden> <button  type="submit" ';
                    echo "$view_zwin[$a]>Zwin</button></form> ";

                    echo'  <form action="index.php" method="post"><input type="text" value="';
                    echo "zwinP$a";
                    echo '" name="zwin_kat" hidden> <button  type="submit" ';
                    echo "$view_zwinP[$a]>Zwin</button></form> </br>";
                }
                $a++;
            }
            if($_SESSION["zalogowany"]==1){
            echo'  <form action="add.php" method="post"><input type="text" value="ADD" name="add_kat" hidden> <button  type="submit">Add</button></form> </br>';
            }
            $pom1 = filter_input(INPUT_POST, "nazwaAdd");
            $pom2 = filter_input(INPUT_POST, "kategoriaAdd");

            if ($pom1 != NULL && $pom2 != NULL) {
                    if($pom2==0){
                   $pdo -> exec("INSERT INTO kategoria (nazwa, id_nadkategoria) VALUES ('$pom1',NULL);");
                    }
                    else
                    {$pdo -> exec("INSERT INTO kategoria (nazwa, id_nadkategoria) VALUES ('$pom1','$pom2');"); 
                    }
                    
                echo '    <script> alert("Dodano");  </script>';
                header("Refresh: '0' ; url=index.php");
            } else if (isset($_POST["send_add"]) &&  $_POST["nazwaAdd"] == NULL) {
                echo '    <script> alert("Niepowodzenie. Sprawdz czy wypelniles wszystkie pola formularza."); </script>';
            }
            if($_SESSION["zalogowany"]==1){
               echo'  <form action="add.php" method="post"><input type="text" value="REMOVE" name="rem_kat" hidden> <button  type="submit">Remove</button></form> </br>';
            }

                $pom3 = filter_input(INPUT_POST, "kategoriaRem");
                $pom4 = filter_input(INPUT_POST, "podepnijRem");
                echo $pom3, $pom4;
            if (isset($_POST['send_rem'])){
            $stmt = $pdo->query("SELECT id_nadkategoria FROM kategoria WHERE id_kategoria=$pom3;"); 
            foreach ($stmt as $row) {
            $nad_kat=$row['id_nadkategoria'];

            }
                        if($nad_kat==NULL)
            {$stm = $pdo -> query("UPDATE kategoria SET id_nadkategoria=NULL WHERE id_nadkategoria='$pom3';");}
            else
            {  $stm = $pdo -> query("UPDATE kategoria SET id_nadkategoria=$nad_kat WHERE id_nadkategoria='$pom3';");}

            $stmt = $pdo -> query("DELETE FROM kategoria WHERE id_kategoria=$pom3;");  
            
            echo '    <script> alert("Usunieto!");  </script>';
                header("Refresh: '0' ; url=index.php");
            }
            
            if($_SESSION["zalogowany"]==1){
            echo'  <form action="add.php" method="post"><input type="text" value="MOVE" name="move_kat" hidden> <button  type="submit">MOVE</button></form> </br>';
            }
            
            $pom_co= filter_input(INPUT_POST, "move_co");
            $pom_gdzie = filter_input(INPUT_POST, "move_gdzie");
            if (isset($_POST['send_move'])){
             echo "$pom_co    $pom_gdzie";   
            $stmt = $pdo->query("SELECT id_nadkategoria FROM kategoria WHERE id_kategoria=$pom_co;"); 
            foreach ($stmt as $row) {
            $nad_kat=$row['id_nadkategoria'];

            }
                        if($nad_kat==NULL)
            {$stm = $pdo -> query("UPDATE kategoria SET id_nadkategoria=NULL WHERE id_nadkategoria='$pom_co';");}
            else
            {  $stm = $pdo -> query("UPDATE kategoria SET id_nadkategoria=$nad_kat WHERE id_nadkategoria='$pom_co';");}
            
            if($pom_gdzie==0)
            {$st = $pdo -> query("UPDATE kategoria SET id_nadkategoria=NULL WHERE id_kategoria='$pom_co';");}
            else
            {$st = $pdo -> query("UPDATE kategoria SET id_nadkategoria=$pom_gdzie WHERE id_kategoria='$pom_co';");}  
            
            echo '    <script> alert("Przeniesiono!");  </script>';
               header("Refresh: '0' ; url=index.php"); 
            }
            
            $stmt->closeCursor(); // zamkniecie zbioru wynikow 
            //inaczej nie bedziemy w stanie wyslac nastepnego zapytania
            echo '</ul>';
        } catch (PDOException $e) {
            echo 'Mamy problem: ' . $e->getMessage();
        }
        ?>
        </div>
    </body>
</html>