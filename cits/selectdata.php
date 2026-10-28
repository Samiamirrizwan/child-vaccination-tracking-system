
<?php
session_start();
error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
include('include/conn.php');
check_login();

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | View Child immunization details</title>
		
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
		<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
		<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
		<link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/plugins.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	</head>
	<body style="background-image:url('parent.jpg'); background-repeat: no-repeat; background-size: cover; background-filter: blur(8px); background-position: center;
  " class="hold-transition login-page">
			
<?php include('include/sidenav.html');?>

<?php include('include/head.php');?>

<section id="page-title">
<div class="row">
<div class="col-sm-8">
<h1 style="padding-left: 350px; color: black;" class="mainTitle"><b> Parent  | View Child Immunization details</b></h1>
</div>
</div>
</section>

  <div class="container-fluid container-fullw bg-green">
  <table class="table table-hover" >
  <thead>
    <tr>
      <th> #</th>
      <th> child_name</th>
      <th>   dob</th>
      <th> parent_name</th>
      <th> contact_number</th>
      <th> address</th>
      <th> vaccine_name</th>
      <th> vaccination_date</th>
      <th> Edit</th>
      <th> Delete</th>
      
    </tr>
  </thead>
  <tbody>
<?php
$sql = "SELECT * FROM child_vaccinations";
$query = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($query)) {

    ?>
    <tr>
    <td><?php echo $row['id'] ?></td>
    <td ><?php echo $row['child_name'] ?></td>
    <td><?php echo $row['dob'] ?></td>
    <td><?php echo $row['parent_name'] ?></td>
    <td><?php echo $row['contact_number'] ?></td>
    <td><?php echo $row['address'] ?></td>
    <td><?php echo $row['vaccine_name'] ?></td>
    <td><?php echo $row['vaccination_date'] ?></td>

    <td><a href="editform.php?editid=<?=$row['id']?>" class="btn btn-warning">Edit</a></td>
    <td><a href="deletecode.php?delid=<?=$row['id']?>" class="btn btn-danger">Delete</a></td>

    </tr>

    <?php
}

?>
  </tbody>
</table>
<center><a href="insertform.php" class="btn btn-warning ">Add New</a></center> 

</div>

			<!-- start: FOOTER -->
      <?php include('include/footer.php');?>
			<!-- end: FOOTER -->
		
			
		</div>
		<!-- start: MAIN JAVASCRIPTS -->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<!-- end: MAIN JAVASCRIPTS -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
		<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
		<script src="vendor/autosize/autosize.min.js"></script>
		<script src="vendor/selectFx/classie.js"></script>
		<script src="vendor/selectFx/selectFx.js"></script>
		<script src="vendor/select2/select2.min.js"></script>
		<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
		<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<!-- start: CLIP-TWO JAVASCRIPTS -->
		<script src="assets/js/main.js"></script>
		<!-- start: JavaScript Event Handlers for this page -->
		<script src="assets/js/form-elements.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});
		</script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>
