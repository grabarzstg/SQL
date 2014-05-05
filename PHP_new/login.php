<?php
session_start();
//session_register("zalogowany");

if(!isset($_SESSION["zalogowany"])){$_SESSION["zalogowany"]=0;}

mysql_connect("127.0.0.1", "root", "")or die("Nie można nawiązać połączenia z bazą");
mysql_select_db("admin")or die("Wystąpił błąd podczas wybierania bazy danych");


function ShowLogin($komunikat=""){
    echo '<div id="loguj">';
	echo "$komunikat<br>";
	echo "<form action='index.php' method=post>";
	echo "Login: <input type=text name=login><br>";
	echo "Haslo: <input type=text name=haslo><br>";
	echo "<input type=submit value='Zaloguj!'>";
	echo "</form>";
	echo "</div>";
}

?>

<?php
$gett= filter_input(INPUT_GET, "wyloguj");
if($gett=="tak"){$_SESSION["zalogowany"]=0;   echo '    <script> alert("Zostales wylogowany!");  </script>';}
if($_SESSION["zalogowany"]!=1){
                $pom1 = filter_input(INPUT_POST, "login");
            $pom2 = filter_input(INPUT_POST, "haslo");
	if(!empty($_POST["login"]) && !empty($_POST["haslo"])){
            $result=mysql_query("select * from users where user_login = '$pom1' AND user_haslo = '$pom2'");
		if(mysql_num_rows($result)){
			$_SESSION["zalogowany"]=1;
                        header("Refresh: '0' ; url=index.php");
			}
                else {echo ShowLogin("Podano złe dane!!!");}
		}
        else {ShowLogin();}
}
else{
?>
<br><a href='index.php?wyloguj=tak'>wyloguj się</a>
<?php
}
?>


<?php mysql_close(); 