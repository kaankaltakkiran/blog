<?php
require_once 'db.php';
$id = $_GET["blogid"];

/* SELECT blogs.*,users.*
FROM users
INNER JOIN blogs
ON blogs.writerid=users.userid WHERE users.userid=:writerid */
$SORGU = $DB->prepare("SELECT blogs.*, users.*, categories.*
FROM users
INNER JOIN blogs ON blogs.writerid = users.userid
INNER JOIN categories ON blogs.categoryid = categories.categoryid WHERE blogs.blogid=:blogid");
$SORGU->bindParam(':blogid', $id);
$SORGU->execute();
$blog = $SORGU->fetchAll(PDO::FETCH_ASSOC);
/* echo '<pre>';
print_r($blog);
die(); */
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <head>
</head>

  </head>
  <body>
    <!--  Navbar için navbar.php dosyasını include ediyoruz. -->
      <?php include 'navbar.php';?>
  <div class="container mt-4">
      <div class="row justify-content-center ">
       <div class="col-6 border">
           <img class='img-thumbnail m-1 rounded float-left' src='images/<?php echo $blog[0]['blogimage']; ?>'/>
       <h1 class="text-danger text-center"><?php echo $blog[0]['title']; ?></h1>
            <div style='float: left;'><span style='color: DimGray;'>Yazar:</span>
          <a href='blog.writer.php?writerid=<?php echo $blog[0]['userid']; ?>' class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover'><?php echo $blog[0]['username']; ?></a>
          <span style='color: DimGray;'><?php echo $blog[0]['blogdate']; ?></span>
      </div>
<br>
<!-- nl2br boşlukları gösteriyor -->
<p><?php echo nl2br($blog[0]['content']); ?></p>
       </div> <!-- MakaleSonu -->
     </div> <!-- col -->
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
