<?php
include 'include/conn.php';

$getid = $_GET['editid'];
//   echo $getid;

$sql = "SELECT * FROM child_vaccinations WHERE id = '$getid'";
$query = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($query)) {

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Child Vaccination Registration</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional: Include a CSS file for styling -->
<link rel="stylesheet" href="./vaccination.css">
</head>

<body>

<center><h1>Child Vaccination Registration</h1></center>
<?php
    if (!empty($errors)) {
        echo '<div class="errors">';
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo '</div>';
    }

    ?>
    <form action="updcode.php" method="POST">
        <label for="child_name">Child Name:</label>
        <input type="hidden" class="form-control" name="id" value="<?=$row['id']?>">
        <input type="text" id="child_name" name="child_name"  value="<?=$row['child_name']?>" required>
        
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?=$row['dob']?>" required>
        
        <label for="parent_name">Parent Name:</label>
        <input type="text" id="parent_name" name="parent_name" value="<?=$row['parent_name']?>">
        
        <label for="contact_number">Contact Number:</label>
        <input type="tel" id="contact_number" name="contact_number" value="<?=$row['contact_number']?>"  required>
        
        <label for="address">Address:</label>
        <input type="text" id="address"  name="address" value="<?=$row['address']?>" required>
        
        <label for="vaccine_name">Vaccine Name:</label>
        <input type="text" id="vaccine_name" name="vaccine_name" value="<?=$row['vaccine_name']?>" required>
        
        <label for="vaccination_date">Vaccination Date:</label>
        <input type="date" id="vaccination_date" name="vaccination_date" value="<?=$row['vaccination_date']?>"  required>
        
        <button name="update" type="submit">Update</button>
    </form>
    <?php
}
?>
</body>
</html>