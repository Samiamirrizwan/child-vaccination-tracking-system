<?php

include 'include/conn.php';

$getid = $_GET['delid'];

$sql = "DELETE FROM child_vaccinations WHERE id = '$getid'";
$query = mysqli_query($conn,$sql);

header("Location: http://localhost/cvts/cits/selectdata.php");

?>