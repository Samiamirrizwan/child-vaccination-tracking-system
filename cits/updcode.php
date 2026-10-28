<?php
include 'include/conn.php';

if (isset($_POST['update'])) {
    $stdid=$_POST['id'];
    $chname = $_POST['child_name'];
    $chidob = $_POST['dob'];
    $paname = $_POST['parent_name'];
    $pacontact = $_POST['contact_number'];
    $paddress = $_POST['address'];
    $chivacc = $_POST['vaccine_name'];
    $chivaccdate = $_POST['vaccination_date'];

    $sql = "UPDATE child_vaccinations set
  child_name ='$chname',
  dob='$chidob',
  parent_name='$paname',
  contact_number='$pacontact',
  address='$paddress' ,
  vaccine_name='$chivacc',
  vaccination_date='$chivaccdate'
    WHERE id='$stdid'";

    $query = mysqli_query($conn,$sql);

    header("Location:http://localhost/cvts/cits/selectdata.php");
}
