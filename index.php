  <?php
@session_start();
$activePage = "index";
?>
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>My Blog</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body>
    <!--  Navbar için navbar.php dosyasını include ediyoruz. -->
      <?php include 'navbar.php';?>
  <!--     Header Start -->
      <div class="container">
      <?php if ($_SESSION['isLogin'] == 0) {?>
      <h3 class="text-center text-danger mt-3">Normalde sayfada görünmeyecek kısımlar!!!</h3>
      <p class="text-center text-info fw-bold ">Url'e admin yazılarak admin login sayfasına gidilir.</p>
      <p class="text-center"> <a href="admin.login.php" class="btn btn-info">Admin Login</a></p>
      <p class="text-center text-success fw-bold ">Url'e writer yazılarak writer login sayfasına gidilir.</p>
      <p class="text-center"> <a href="writer.login.php" class="btn btn-success">Writer Login</a></p>

      <?php }?>

      <!--  Yazı ekleme sayfasına giriş yapan kullanıcının idsini yollama -->
      <?php
require_once 'db.php';
$sql = "SELECT * FROM users WHERE userid = :idUser";
$id = $_SESSION['id'];
$SORGU = $DB->prepare($sql);
$SORGU->bindParam(':idUser', $id);
$SORGU->execute();
$users = $SORGU->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Eğer adamin giriş yaparsa aşaığıdaki kısımlar görünür(role=2) -->
    <?php if ($_SESSION['role'] == 2) {?>
      <div class="container my-3 ">
      <div class="row justify-content-center">
          <div class="col-6">
          <h1 class="text-center text-danger mt-3">Welcome</h1>
          <h3 class="text-center text-muted">Admin: <?php echo $_SESSION['userName']; ?></h3>
          <h5 class="text-center text-danger fw-bold"><?php
date_default_timezone_set('Europe/Istanbul'); // Türkiye saat dilimine göre tarih ve saat ayarla
    $date_and_time = date("d-m-Y H:i:s"); // Yıl-Ay-Gün Saat:Dakika:Saniye formatında tarih ve saat
    echo "Date And Time: " . $date_and_time;
    ?>
</h5>
       </div>
        <div class="row justify-content-center g-4">
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 " style="width: 18rem;">
              <img src="./public/img/addAdmin.jpg" class="card-img-top" alt="Admin img">
              <div class="card-body">
                <h5 class="card-title">Add Admin User</h5>
                <p class="card-text">Admin Adds Admin User</p>
                <a href="add.admin.php?idUser=<?php echo $users[0]['userid'] ?>" class="btn btn-danger mt-3">Add Admin
                <i class="bi bi-send-fill"></i>
                </a>
              </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card h-100 " style="width: 18rem;">
            <img src="./public/img/blogwriter.jpg" class="card-img-top" alt="Writer img">
            <div class="card-body">
              <h5 class="card-title">Add Writer User</h5>
              <p class="card-text">Admin Adds Writer User</p>
              <a href="add.writer.php?idUser=<?php echo $users[0]['userid'] ?>" class="btn btn-danger mt-3">Add Writer User
              <i class="bi bi-send-fill"></i>
              </a>
            </div>
          </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100 " style="width: 18rem;">
          <img src="./public/img/category.jpg" class="card-img-top" alt="Category img">
          <div class="card-body">
            <h5 class="card-title">Add Blog Category</h5>
            <p class="card-text">Admin Adds Blog Category</p>
            <a href="add.category.php?idUser=<?php echo $users[0]['userid'] ?>" class="btn btn-danger mt-4" style="">Add Blog Category
            <i class="bi bi-send-fill"></i></a>
          </div>
        </div>
    </div>
          </div>
      </div>
      <?php }?>
        <div class="row justify-content-center ">
          <div class="col-6">
        <h1 class='alert alert-primary mt-3 text-center'>Welcome My Blog V2!</h1>
        </div>
        </div>
  <!--     Header End -->
  <?php
require_once 'db.php';
/* SELECT * FROM blogs ORDER BY blogid DESC LIMIT 3 */
//?Birinci Çözüm
//!Eğer bloglar yayınlasın seçildiğiyse sql den ispublish = 1 olanları getir
$SORGU = $DB->prepare("SELECT * FROM blogs WHERE ispublish = 1 AND CURDATE() BETWEEN blogs.startdate AND blogs.lastdate ORDER BY blogid DESC LIMIT 5");
$SORGU->execute();
$carouselblogs = $SORGU->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Chatgpt çözümü ile yapılan carousel -->
  <!--     Carousel Start -->
  <h2 class="text-center  text-danger ">Popular Blogs</h2>
        <div class="row justify-content-center ">
          <div class="col-6">
          <div id="carouselExampleAutoplaying" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
    <?php
foreach ($carouselblogs as $key => $carouselblog) {
    $activeClass = ($key === 0) ? 'active' : ''; // İlk öğe için active sınıfını ayarla
    ?>
                    <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="<?php echo $key; ?>" class="<?php echo $activeClass; ?>" aria-label="Slide <?php echo $key + 1; ?>"></button>
                <?php }?>
    </div>
    <div class="carousel-inner">
    <?php
foreach ($carouselblogs as $key => $carouselblog) {
    $activeClass = ($key === 0) ? 'active' : ''; // İlk öğe için active sınıfını ayarla
    ?>
                    <div class="carousel-item <?php echo $activeClass; ?>" data-bs-interval="2000">
                    <a href="blog.show.php?blogid=<?php echo $carouselblog['blogid']; ?>"> <img src="images/<?php echo $carouselblog['blogimage']; ?>" class="d-block w-100" alt="Blog Image">
                    </a>
                        <div class="carousel-caption d-none d-md-block">
                            <h5><?php echo $carouselblog['title']; ?></h5>
                            <p><?php echo $carouselblog['summary']; ?></p>
                        </div>
                    </div>
                <?php }?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
          </div>
        </div>
  <!--     Carousel end -->
  <!--     Bloglar Start -->
          <div class="row g-3 mt-4 ">
          <h1 class='alert alert-info mt-3 text-center'>Published Blogs</h1>
          <div class="col-7 border ">
          <?php

require_once 'db.php';

/*   SELECT * FROM blogs */
/* SELECT blogs.*,users.*
FROM users
INNER JOIN blogs
ON blogs.writerid=users.userid */
/* Yeni eklenen blogları en üstte göstermek için ORDER BY blogid DESC kullandım? */
$SORGU = $DB->prepare("SELECT blogs.*, users.*, categories.*
FROM users
INNER JOIN blogs ON blogs.writerid = users.userid
INNER JOIN categories ON blogs.categoryid = categories.categoryid WHERE ispublish = 1 AND CURDATE() BETWEEN blogs.startdate AND blogs.lastdate ORDER BY blogid DESC");
$SORGU->execute();
$blogs = $SORGU->fetchAll(PDO::FETCH_ASSOC);
//echo '<pre>'; print_r($users);
foreach ($blogs as $blog) {
    //!Format date
    $startDate = new DateTime($blog['startdate']);
    $formattedStartDate = $startDate->format('d-m-Y');
    echo "
      <div class='row mt-3 '>
          <div class='col-4'>
          <a href='blog.show.php?blogid={$blog["blogid"]}'>  <img src='images/{$blog['blogimage']}' class='card-img-top' alt='Blog Image'></a>
          </div>
          <div class='col-8'>
          <h3>{$blog['title']} <span class='text-danger  fs-6' style='float: right;'>{$formattedStartDate}</span></h3>
          <p>{$blog['summary']}</p>
          <a href='blog.show.php?blogid={$blog["blogid"]}' class='btn btn-outline-info'>Read More...</a>
          <div style='float: right;'><span style='color: DimGray;'>Writer:</span>
          <a href='blog.writer.php?writerid={$blog["userid"]}' class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover'>{$blog['username']}</a>
          <br>
          <span style='color: DimGray;'>Category:</span> {$blog['categoryname']}
      </div>

          </div>
          </div>
  ";
    echo "<hr>";
}

?>
          </div>
          <div class="col-5 border">
          <h2 class='alert alert-danger mb-3 text-center text-danger'>Categorories</h2>
          <?php
require_once 'db.php';
$sql = "SELECT * FROM categories";
$SORGU = $DB->prepare($sql);
$SORGU->execute();
$categories = $SORGU->fetchAll(PDO::FETCH_ASSOC);

/* var_dump($categories);
die(); */
//! Kategorileri listelemek için iki liste oluşturuyoruz(chatgptan alınmıştır)
$firstListItems = "";
$secondListItems = "";
//! veri sayısını buluyoruz
$totalCategories = count($categories);

for ($i = 0; $i < $totalCategories; $i++) {
    $category = $categories[$i];

    //! Veri sayısı tek ise firstListItems'a, çift ise secondListItems'a ekle
    if ($i % 2 == 0) {
        $firstListItems .= "<a class='list-group-item list-group-item-action list-group-item-info' href='blog.category.show.php?categoryid={$category["categoryid"]}'>{$category["categoryname"]}</a>";
    } else {
        $secondListItems .= "<a href='blog.category.show.php?categoryid={$category["categoryid"]}' class='list-group-item list-group-item-action list-group-item-info'>{$category["categoryname"]}</a>";
    }
}
?>
            <div class="row">
              <div class="col-6">
              <ul class="list-group">
              <?php echo $firstListItems; ?>

  </ul>
              </div>
              <div class="col-6">
              <ul class="list-group">
              <?php echo $secondListItems; ?>
  </ul>
              </div>
            </div>
        </div>
            </div>
            <!--     Bloglar end -->
  </div>
  </div>
      <?php include 'footer.php';?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
  </html>
