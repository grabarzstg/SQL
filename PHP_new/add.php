<!DOCTYPE html>
<html>
    <head>
        <title>Dodaj Kategorię</title>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" >
        <link rel="Stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
<?php
  //wczytanie pliku konfiguracyjnego
	require("config.php"); 
  
  //nawiazanie polaczenia z baza danych
  try
  {
    $pdo = new PDO("$szbd:host=$host;dbname=$dbname;port=$port",$login,$haslo);
    echo 'Polaczenie nawiazane!';  //w razie potrzeby usun ten komunikat
 
    //chcemy, aby ewentualne bledy w zapytaniach raportowane byly jako wyjatki
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 				
  }
  catch(PDOException $e)
  {
    echo 'Polaczenie nie moglo zostac utworzone: ' . $e->getMessage();
    exit(1);
  }	
 // END OF CONNECT -------------------------

try {
  $po1= filter_input(INPUT_POST, "add_kat");
  $po2= filter_input(INPUT_POST, "rem_kat");
  $po3= filter_input(INPUT_POST, "move_kat");
  $po4= filter_input(INPUT_POST, "send_move");
  $pom= filter_input(INPUT_POST, "kategoriakrok1");
    if($po1=="ADD"){
echo '<form action="index.php" method="post">'
.'Nazwa kategorii: </br>'
. '<input type="text" name="nazwaAdd"> </br>' 
.'Wybierz kategorię nadrzędną: </br>'   
.'	<select name="kategoriaAdd"> </br>';
$stmt = $pdo -> query('SELECT * FROM kategoria;');
echo '<option value="0">(0) KATEGORIA G�?ÓWNA </option>';
            foreach($stmt as $row) 
             { 
		echo' <option value ="'.$row[id_kategoria].'">('.$row[id_kategoria].') '.$row[nazwa].'</option>';
             }
echo'	</select></br>'
.' <button name="send_add" value="add"  type="submit">Add</button></form></br>';
    }
    
    else if($po2=="REMOVE"){
echo '<form action="index.php" method="post">'
.'Wybierz kategorię do usuniecia: </br>'   
.'	<select name="kategoriaRem"> </br>';
$stmt = $pdo -> query('SELECT * FROM kategoria;');
            foreach($stmt as $row) 
             { 
		echo' <option value ="'.$row[id_kategoria].'">('.$row[id_kategoria].') '.$row[nazwa].'</option>';
             }
echo'	</select></br>'
.'<input type="radio" name="podepnijRem" value="TRUE" checked="checked">Podepnij poziom wyzej<br> <input type="radio" name="podepnijRem" value="FALSE">Usun podkategorie'
.' <button name="send_rem" value="rem"  type="submit">Usun</button></form></br>';
    }
else if($po3=="MOVE"){
echo '<form action="add.php" method="post">'
.'Nazwa kategorii: </br>' 
.'Wybierz kategorię do przeniesienia: </br>'   
.'	<select name="kategoriakrok1"> </br>';
$stmt = $pdo -> query('SELECT * FROM kategoria;');
            foreach($stmt as $row) 
             { 
		echo' <option value ="'.$row[id_kategoria].'">('.$row[id_kategoria].') '.$row[nazwa].'</option>';
             }
echo'	</select></br>'
.'<input type="text" value="krok2" name="send_move" hidden>'
.' <button name="butt_move" type="submit">Przenies</button></form></br>';
    }
    else if($po4=="krok2"){
echo '<form action="index.php" method="post">'
.'Nazwa kategorii: </br>' 
.'Wybierz kategorię nadrzędną: </br>'   
.'	<select name="move_gdzie"> </br>';
$stm = $pdo -> query("SELECT id_nadkategoria FROM kategoria WHERE id_kategoria=$pom;");
            foreach($stm as $row){
                if($row['id_nadkategoria']!=NULL)
                {echo '<option value="0">(0) KATEGORIA GLOWNA </option>';}
                
            }
$stmt = $pdo -> query('SELECT * FROM kategoria;');
            foreach($stmt as $row) 
             { if($row[id_kategoria]!=$pom)
		echo' <option value ="'.$row[id_kategoria].'">('.$row[id_kategoria].') '.$row[nazwa].'</option>';
             }
echo'	</select></br>'
.'<input type="text" value="'
        . "$pom"
        . '" name="move_co" hidden>'
.' <button name="send_move" value="move"  type="submit">Przenies</button></form></br>';
    }
    else { header("Refresh: '0' ; url=index.php"); }
    
    
  } catch(PDOException $e){
    echo 'Mamy problem: '.$e->getMessage();
  }

?>
        
    </body>
</html>