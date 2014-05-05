<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" >
    <link rel="Stylesheet" type="text/css" href="style.css" />
	<title>Wyswietl</title>
</head>
<body>
    
<?php
session_start();
if(!isset($_SESSION["zalogowany"])){$_SESSION["zalogowany"]=0;}
 
mysql_connect("127.0.0.1", "root", "")or die("Nie mozna nawiazac polaczenia");
mysql_select_db("admin")or die("Wystapil blad podczas wybierania bazy danych");



$gt= filter_input(INPUT_POST, "nr");
$data= filter_input(INPUT_POST, "add_data");
if(isset($data))
{
  mysql_query("INSERT INTO `data`(`id_data`, `datatext`) VALUES ('".$gt."','".$data."')") or die("blad podczas dodawania opisu"); 
 echo '<script> alert("Dodano opis do kategorii");  </script>';
  header("Refresh: '0' ; url=display.php?nr=$gt");
}
$pom= filter_input(INPUT_POST, "edit_data");
$pom2= filter_input(INPUT_POST, "edit_send");
if(isset($pom)){
$re=mysql_query("UPDATE data SET datatext='$pom' WHERE id_data='$pom2'") or die ("QUERY ERROR EDIT");
 echo '<script> alert("Zedytowano opis kategorii");  </script>';
  header("Refresh: '0' ; url=display.php?nr=$pom2");
}


$g1= filter_input(INPUT_POST, "delete");
$g2= filter_input(INPUT_POST, "del_nr");

if(isset($g1)){
$res=mysql_query("DELETE FROM data WHERE id_data='$g2'") or die ("DELETE ERROR ");
 echo '<script> alert("Usunieto opis");  </script>';
  header("Refresh: '0' ; url=index.php");
}


$got= filter_input(INPUT_POST, "edit");
$git= filter_input(INPUT_POST, "edit_nr");

if(isset($got)){
$res=mysql_query("SELECT datatext FROM data WHERE id_data = '$git'") or die ("QUERY ERROR GIT");


if(mysql_num_rows($res)){

    foreach (mysql_fetch_row($res) as $row){
    }
if(($_SESSION["zalogowany"]==1)&& ($got=="edit")){
    echo '<form action="display.php" method="post"><input type="text" value="'.$row.'" name="edit_data"> </br> <button type="submit" name="edit_send" value="'.$git.'">Edytuj opis</button></form>';
    
}
}
}


$get= filter_input(INPUT_GET, "nr");
if(isset($get)){
$result=mysql_query("SELECT datatext FROM data WHERE id_data = '$get'") or die ("QUERY ERROR");


if(mysql_num_rows($result)){

    foreach (mysql_fetch_row($result) as $row){
        echo '<div name="zawartosc">'.$row.'</div>';
    }
    if ($_SESSION["zalogowany"]==1){
    echo '<form action="display.php" method="post"><input type="text" value="'.$get.'" name="edit_nr" hidden><button name="edit" value="edit"  type="submit">Edytuj</button></form>';
    echo '<form action="display.php" method="post"><input type="text" value="'.$get.'" name="del_nr" hidden><button name="delete" value="del"  type="submit">Usun</button></form></br>';
    }
    echo '<form action="index.php" method="post"><button  type="submit">Wroc</button></form></br>';
}
else if ($_SESSION["zalogowany"]==1){
   echo '<script> alert("Podana kategoria nie zawiera opisu. Dodaj nowy.");  </script>';
echo '<form action="display.php" method="post"><input type="text" value="Uzupelnij opis kategorii" name="add_data"><input type="text" value="'.$get.'" name="nr" hidden> </br> <button type="submit">Dodaj opis</button></form>';
echo '<form action="index.php" method="post"><button  type="submit">Anuluj</button></form></br>';
}
 else {
 echo '<script> alert("Podana kategoria nie zawiera opisu");  </script>';
 header("Refresh: '0' ; url=index.php"); 
}
 
}


mysql_close(); 
?>
    
    
    
</body>
</html>