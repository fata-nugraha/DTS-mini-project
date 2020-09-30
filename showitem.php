<?php
//connect to database
require_once("config.php");
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

//create safe values for use
$safe_item_id = mysqli_real_escape_string($mysqli, $_GET['item_id']);
if(isset($_POST['add_comment'])){
    $comment = mysqli_real_escape_string($mysqli, $_POST['comment']);
    $insertSQL = "INSERT INTO store_item_comment(item_id,username,comment) values(?,?,?)";
    $stmt = $mysqli->prepare($insertSQL);
    $stmt->bind_param("sss",$safe_item_id,$_SESSION['uname'],$comment);
    $stmt->execute();
    $stmt->close();
}
    
//validate item
$get_item_sql = "SELECT c.id as cat_id, c.cat_title, si.item_title, si.item_price, si.item_desc, si.item_image FROM store_items AS si LEFT JOIN store_categories AS c on c.id = si.cat_id WHERE si.id = '".$safe_item_id."'";
$get_item_res = mysqli_query($mysqli, $get_item_sql) or die(mysqli_error($mysqli));

if (mysqli_num_rows($get_item_res) < 1) {
    //invalid item
    $display_block .= "<p><em>Invalid item selection.</em></p>";
} else {
    //valid item, get info
    while ($item_info = mysqli_fetch_array($get_item_res)) {
       $cat_id = $item_info['cat_id'];
       $cat_title = strtoupper(stripslashes($item_info['cat_title']));
       $item_title = stripslashes($item_info['item_title']);
       $item_price = $item_info['item_price'];
       $item_desc = stripslashes($item_info['item_desc']);
       $item_image = $item_info['item_image'];
    }
    //make breadcrumb trail & display of item
    $display_block .= <<<END_OF_TEXT
    <nav aria-label='breadcrumb'>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="seestore.php?cat_id=$cat_id">$cat_title</a> &gt; $item_title</strong></p></li>
    </ol>
    </nav>
    <div class='card mb-3' style='max-width: 540px;text-align=center'>
    <div class='row no-gutters'>
     <div class='col-md-4'>
     <img src="$s3path/$item_image" alt="$item_title" style="width:195px;height:200px;"/>
     </div>
     <div class='col-md-8'>
       <div class='card-body'>
         <h5 class='card-title'>Description : </h5>
         <p class='card-text'>$item_desc</p>
         <p class='card-text'><Strong>Price:</strong> \$$item_price</p>
    END_OF_TEXT;

//free result
mysqli_free_result($get_item_res);

//get colors
$get_colors_sql = "SELECT item_color FROM store_item_color WHERE item_id = '".$safe_item_id."' ORDER BY item_color";
$get_colors_res = mysqli_query($mysqli, $get_colors_sql) or die(mysqli_error($mysqli));

if (mysqli_num_rows($get_colors_res) > 0) {
    $display_block .= "<p><strong>Available Colors:</strong><br/>";
    while ($colors = mysqli_fetch_array($get_colors_res)) {
       $item_color = $colors['item_color'];
       $display_block .= $item_color."<br/>";
   }
}

//free result
mysqli_free_result($get_colors_res);

//get sizes
$get_sizes_sql = "SELECT item_size FROM store_item_size WHERE item_id = '".$safe_item_id."' ORDER BY item_size";
$get_sizes_res = mysqli_query($mysqli, $get_sizes_sql) or die(mysqli_error($mysqli));

if (mysqli_num_rows($get_sizes_res) > 0) {
   $display_block .= "<p><strong>Available Sizes:</strong><br/>";

   while ($sizes = mysqli_fetch_array($get_sizes_res)) {
      $item_size = $sizes['item_size'];
      $display_block .= $item_size."<br/>";
   }
}
    //free result
    mysqli_free_result($get_sizes_res);
    //get sizes
    $get_comments_sql = "SELECT username, comment FROM store_item_comment WHERE item_id = '".$safe_item_id."'";
    $get_comments_res = mysqli_query($mysqli, $get_comments_sql) or die(mysqli_error($mysqli));

    if (mysqli_num_rows($get_comments_res) > 0) {
      $display_block .= "<p><strong>Comments:</strong><br/>";

      while ($comments = mysqli_fetch_array($get_comments_res)) {
         $user = $comments['username'];
         $item_comment = $comments['comment'];
         $display_block .= $user.": ".$item_comment."<br/>";
      }
    }
    //free result
    mysqli_free_result($get_comments_res);
    //close up the div
    $display_block .= 
        '<form action="" method="POST">
            <div class="form-group">
                <label for="comment">Comment</label>
                <input class="form-control" type="text" name="comment"> </input>
            </div>
            <input type="submit" class="btn btn-info btn-block mt-4" name="add_comment" value="Add" />
        </form>';
    $display_block .= "</div></div></div></div></div>";
}
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
      <h2 class="card-title h2" style="text-align :center">Item Detail</h2>
      </div>
    </header>
<body>
    <form method='post' action="">
      <input type="submit" value="Logout" name="but_logout">
    </form>
    <?php echo $display_block; ?>
</body>
</html>
