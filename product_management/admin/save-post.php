<?php
    include "config.php";
    if(isset($_FILES['fileToUpload'])){
        $errors =array();
         
        $file_name = $_FILES['fileToUpload']['name'];
        $file_size = $_FILES['fileToUpload']['size'];
        $file_tmp  = $_FILES['fileToUpload']['tmp_name'];
        $file_type = $_FILES['fileToUpload']['type'];
        $file_ext = strtolower(end(explode('.' , $file_name)));
        $extensions  = array("jpeg" , "jpg" , "png");

        if(in_array($file_ext  , $extensions) === false){
            $errors[] = "This extension file is not allowed , Please choose a jpg or png file"; 
        }
        if($file_size > 2097152){
            $errors[] = "File size must be 2mb or lower";
        }

        if(empty($errors) == true){
            move_uploaded_file($file_tmp , "upload/" . $file_name);
        }else {
            print_r($errors);
            die();
        }

    }
    session_start();
    $product_name = mysqli_real_escape_string($conn , $_POST['product_name']);
    $description= mysqli_real_escape_string($conn , $_POST['postdesc']);
    $category = mysqli_real_escape_string($conn , $_POST['category']);
    $date = date("d M, Y");
    $author = $_SESSION['user_id'];
    $price = mysqli_real_escape_string($conn , $_POST['post_price']);

    $sql = "INSERT into post(product_name , description , category , post_date , author , price , post_img) values('{$product_name}' , '{$description}' , '{$category}' , '{$date}' , '{$author}' , '{$price}' , '{$file_name}');";
    $sql .= "UPDATE category set post = post + 1 where  category_id  = {$category}";

    if(mysqli_multi_query($conn , $sql)){
        header("Location: {$hostname}/admin/post.php");
    }else{
        echo "<div class='alert alert-danger'>Query Failed</div>";
    }

    ?>  