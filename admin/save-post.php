<?php

include "config.php";

if(isset($_FILES['fileToUpload'])){

	$errors = array();
	$file_name = $_FILES['fileToUpload']['name'];
	$file_size = $_FILES['fileToUpload']['size'];
	$file_tmp = $_FILES['fileToUpload']['tmp_name'];
	$file_type = $_FILES['fileToUpload']['type'];
	$file_ext = end(explode('.', $file_name));

	$extensions = array("jpeg","jpg","png");
	if(in_array($file_ext, $extensions) === false){
		$errors[] = "Only jpeg ,jpg or png Files Allowed .";
	}

	if($file_size > 2097152){
		$errors[] = "File Size Must Be 2mb Or Lower ";
	}
	$new_name = time()."-".basename($file_name);
	$target = "upload/".$new_name; 
	$image_name = $new_name; 

	if(empty($errors) == true){
		move_uploaded_file($file_tmp,$image_name);
	}else{

		print_r($errors);
		die();
	}
}
session_start();

$title = mysqli_real_escape_string($conn,$_POST['post_title']);

$description = mysqli_real_escape_string($conn,$_POST['postdesc']);
$category = mysqli_real_escape_string($conn,$_POST['category']);

$date = date("d M,Y");
$auther = $_SESSION['user_id'];

$sql = "INSERT INTO post(title,description,category,post_date,author,post_img)

 VALUES('{$title}','{$description}',{$category},'{$date}',{$auther},'{$image_name}');";

 $sql .  "UPDATE category SET post = post+1 WHERE category_id = {$category}";
	
	if(mysqli_multi_query($conn,$sql)){
		header("Location: {$hostname}/admin/post.php");

	}else{
		echo "<div class='alter alter-danger'>Query Failed.</div>";
	}



?>
