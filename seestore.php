<?php
//connect to database
require_once("config.php");

// Check user login or not
if(!isset($_SESSION['uname'])){
   header('Location: login.php');
}

// logout
if(isset($_POST['but_logout'])){
   session_destroy();
   header('Location: login.php');
}

$display_block ="<div class='container'>
                  <div class='row'>
                    <div class='col-md-12'>";

//show categories first
$get_cats_sql = "SELECT id, cat_title, cat_desc FROM store_categories ORDER BY cat_title";
$get_cats_res =  mysqli_query($mysqli, $get_cats_sql) or die(mysqli_error($mysqli));

if (mysqli_num_rows($get_cats_res) < 1) {
   
   $display_block = "<div class='notices red'>Sorry, no categories to browse.</b></div>";
} else {
   $display_block.="<div class='card-columns'>";
   while ($cats = mysqli_fetch_array($get_cats_res)) {
        $cat_id  = $cats['id'];
        $cat_title = strtoupper(stripslashes($cats['cat_title']));
        $cat_desc = stripslashes($cats['cat_desc']);
        $display_block .="<div class='card mb-3' style='max-width: 540px;'>
        <div class='row no-gutters'>
          <div class='col-md-4'>
            <img src='https://dtstore.s3.amazonaws.com/".$cat_title.".jpeg' class='card-img' alt='...'>
          </div>
          <div class='col-md-8'>
            <div class='card-body'>
              <h5 class='card-title'>".$cat_title."</h5>
              <p class='card-text'>".$cat_desc."</p>
              <a href=\"".$_SERVER['PHP_SELF']."?cat_id=".$cat_id."\">Check it</a>
            </div>
          </div>
        </div>
      </div>";
       

      if (isset($_GET['cat_id']) && ($_GET['cat_id'] == $cat_id)) {

            //create safe value for use
            $safe_cat_id = mysqli_real_escape_string($mysqli, $_GET['cat_id']);

            //get items
            $get_items_sql = "SELECT id, item_title, item_price FROM store_items WHERE cat_id = '".$safe_cat_id."' ORDER BY item_title";
            $get_items_res = mysqli_query($mysqli, $get_items_sql) or die(mysqli_error($mysqli));

            if (mysqli_num_rows($get_items_res) < 1) {
           
               $display_block =  "<div class='notices red'>Sorry, no items in this category..</b></div>";
            } else {
               $display_block.="<div class='card text-center'>
               <div class='card-body'><ul>";

               while ($items = mysqli_fetch_array($get_items_res)) {
                  $item_id  = $items['id'];
                  $item_title = stripslashes($items['item_title']);
                  $item_price = $items['item_price'];
                  $display_block.="<li><a href=\"showitem.php?item_id=".$item_id."\">".$item_title."</a> (\$".$item_price.")</li>";
                }
                $display_block .= "</ul></div></div></div>";
            }
            //free results
            mysqli_free_result($get_items_res);
        }
    }
}
//free results
mysqli_free_result($get_cats_res);

//close connection to MySQL
mysqli_close($mysqli);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DTStore</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <style>
      .img-container {
        text-align: center;
        display: block;
      }
    </style>
</head>
<body class="bg-light">
    <header>
    <div class="container">
        <div class="row">
        <div class="col-md-12">
        
          <span class="img-container">
             <img class="img img-responsive" src="https://dtstore.s3.amazonaws.com/itb.png" style="width:auto;height:200px;"/>
             <img class="img img-responsive" src="https://dtstore.s3.amazonaws.com/DTS_0.png" style="width:auto;height:200px;"/>
          </span>
        </div>
        </div>
        <p></p>
        <div class="alert alert-primary" role="alert">
      <h2 class="card-title h2" style="text-align :center">Welcome to DTS Store</h2>
      </div>
    </header>
    <section>
        <form method='post' action="">
          <input type="submit" value="Logout" name="but_logout">
        </form>
        <?php echo $display_block; ?>
    </section>
</body>
</html>
