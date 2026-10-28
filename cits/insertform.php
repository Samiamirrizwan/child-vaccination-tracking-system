<?php
include "include/conn.php";
// Initialize variables
$child_name = $dob = $parent_name = $contact_number = $address = $vaccine_name = $vaccination_date = "";
$errors = [];

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    if (empty($_POST["child_name"])) {
        $errors[] = "Child name is required.";
    } else {
        $child_name = $conn->real_escape_string($_POST["child_name"]);
    }

    if (empty($_POST["dob"])) {
        $errors[] = "Date of birth is required.";
    } else {
        $dob = $conn->real_escape_string($_POST["dob"]);
    }

    if (empty($_POST["parent_name"])) {
        $errors[] = "Parent name is required.";
    } else {
        $parent_name = $conn->real_escape_string($_POST["parent_name"]);
    }

    if (empty($_POST["contact_number"])) {
        $errors[] = "Contact number is required.";
    } else {
        $contact_number = $conn->real_escape_string($_POST["contact_number"]);
    }

    if (empty($_POST["address"])) {
        $errors[] = "Address is required.";
    } else {
        $address = $conn->real_escape_string($_POST["address"]);
    }

    if (empty($_POST["vaccine_name"])) {
        $errors[] = "Vaccine name is required.";
    } else {
        $vaccine_name = $conn->real_escape_string($_POST["vaccine_name"]);
    }

    if (empty($_POST["vaccination_date"])) {
        $errors[] = "Vaccination date is required.";
    } else {
        $vaccination_date = $conn->real_escape_string($_POST["vaccination_date"]);
    }

    // Insert into database if no errors
    if (empty($errors)) {
        $sql = "INSERT INTO child_vaccinations (child_name, dob, parent_name, contact_number, address, vaccine_name, vaccination_date) 
                VALUES ('$child_name', '$dob', '$parent_name', '$contact_number', '$address', '$vaccine_name', '$vaccination_date')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Record added successfully.</p>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
}
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

    <br><br>
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
    <form action="insertcode.php" method="POST">
        <label for="child_name">Child Name:</label>
        <input type="text" id="child_name" name="child_name" value="<?php echo htmlspecialchars($child_name); ?>" required>
        
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" required>
        
        <label for="parent_name">Parent Name:</label>
        <input type="text" id="parent_name" name="parent_name" value="<?php echo htmlspecialchars($parent_name); ?>" required>
        
        <label for="contact_number">Contact Number:</label>
        <input type="tel" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" required>
        
        <label for="address">Address:</label>
        <textarea id="address" name="address" required><?php echo htmlspecialchars($address); ?></textarea>
        
        <label for="vaccine_name">Vaccine Name:</label>
        <input type="text" id="vaccine_name" name="vaccine_name" value="<?php echo htmlspecialchars($vaccine_name); ?>" required>
        
        <label for="vaccination_date">Vaccination Date:</label>
        <input type="date" id="vaccination_date" name="vaccination_date" value="<?php echo htmlspecialchars($vaccination_date); ?>" required>
        
        <button name="insert" type="submit">Register</button>
    </form>
</body>
</html>