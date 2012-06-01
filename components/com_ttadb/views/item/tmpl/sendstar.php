<?php 
require_once("../../../../../configuration.php");
$jconfig = new JConfig();
mysql_connect($jconfig->host, $jconfig->user, $jconfig->password) or die(mysql_error());
mysql_select_db($jconfig->db) or die(mysql_error());


$rating = (int)$_POST['rating'];
$id = (int)$_POST['id'];



$query = mysql_query("SELECT * FROM jos_ttadb_item WHERE id = '".$id."'") or die(mysql_error());

while($row = mysql_fetch_array($query)) {

	if($rating > 5 || $rating < 1) {
		echo"Rating can't be below 1 or more than 5";
	}
	
	elseif(isset($_COOKIE['rated'.$id])) {
		echo"<div class='highlight'>Already Voted!</div>";
	}
	else {

		setcookie("rated".$id, $id, time()+60*60*24*730);

		$total_ratings = $row['total_ratings'];
		$total_rating = $row['total_rating'];
		$current_rating = $row['rating'];

		$new_total_rating = $total_rating + $rating;
		$new_total_ratings = $total_ratings + 1;
		$new_rating = $new_total_rating / $new_total_ratings;
		

		// Lets run the queries. 

		mysql_query("UPDATE jos_ttadb_item SET total_rating = '".$new_total_rating."' WHERE id = '".$id."'") or die(mysql_error());
		mysql_query("UPDATE jos_ttadb_item SET rating = '".$new_rating."' WHERE id = '".$id."'") or die(mysql_error());
		mysql_query("UPDATE jos_ttadb_item SET total_ratings = '".$new_total_ratings."' WHERE id = '".$id."'") or die(mysql_error());

		echo"<div class='highlight'>Vote Recorded!</div>";

	}
} ?>