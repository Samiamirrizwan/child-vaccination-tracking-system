<?php

include 'include/conn.php';

if (isset($_POST['insert'])) {
    $chname = $_POST['child_name'];
    $chidob = $_POST['dob'];
    $paname = $_POST['parent_name'];
    $pacontact = $_POST['contact_number'];
    $paddress = $_POST['address'];
    $chivacc = $_POST['vaccine_name'];
    $chivaccdate = $_POST['vaccination_date'];

    $sql = "INSERT INTO child_vaccinations (child_name,dob,parent_name,contact_number,address,vaccine_name,vaccination_date)
     VALUES ('$chname','$chidob','$paname ','$pacontact','$paddress','$chivacc','$chivaccdate')";
    $query = mysqli_query($conn, $sql);

    header("Location: http://localhost/cvts/cits/selectdata.php");
}
