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
    </head>
    <body>
    <!--  Navbar için navbar.php dosyasını include ediyoruz. -->
      <?php include 'navbar.php';?>
  <!--     Header Start -->
      <div class="container">
      <?php if ($_SESSION['isLogin'] == 0) {?>
      <h3 class="text-center text-danger mt-3">Normalde sayfada olmayacak kısımlar</h3>
      <p class="text-center"> <a href="admin.login.php" class="btn btn-primary">Admin Login Ekranı</a></p>

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
      <?php if ($_SESSION['isLogin'] == 1) {?>
        <p class="text-center mt-3"> <a href="blog.add.php?idUser=<?php echo $users[0]['userid'] ?>" class="btn btn-primary">Yazı Ekleme Sayfası</a></p>
        <?php }?>
        <div class="row justify-content-center ">
          <div class="col-6">
        <h1 class='alert alert-primary mt-3 text-center'>Bloguma Hoşgeldiniz!</h1>
        </div>
        </div>
  <!--     Header End -->
  <?php

require_once 'db.php';

$SORGU = $DB->prepare("SELECT * FROM blogs ORDER BY blogid DESC LIMIT 3");
$SORGU->execute();
$carouselblogs = $SORGU->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Chatgpt çözümü ile yapılan carousel -->
  <!--     Carousel Start -->
  <h2 class="text-center  text-danger ">Popüler Bloglar</h2>
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
          <h1 class='text-center text-danger '>Yayınlanan Bloglar</h1>
          <div class="col-7 border ">
          <?php

require_once 'db.php';

/*   SELECT * FROM blogs */
/* SELECT blogs.*,users.*
FROM users
INNER JOIN blogs
ON blogs.writerid=users.userid */
$SORGU = $DB->prepare("SELECT blogs.*, users.*, categories.*
FROM users
INNER JOIN blogs ON blogs.writerid = users.userid
INNER JOIN categories ON blogs.categoryid = categories.categoryid");
$SORGU->execute();
$blogs = $SORGU->fetchAll(PDO::FETCH_ASSOC);
//echo '<pre>'; print_r($users);
foreach ($blogs as $blog) {
    echo "
      <div class='row mt-3 '>
          <div class='col-4'>
          <a href='blog.show.php?blogid={$blog["blogid"]}'>  <img src='images/{$blog['blogimage']}' class='card-img-top' alt='Blog Image'></a>
          </div>
          <div class='col-8'>
          <h3>{$blog['title']} <span class='text-danger  fs-6' style='float: right;'>{$blog['blogdate']}</span></h3>
          <p>{$blog['summary']}</p>
          <a href='blog.show.php?blogid={$blog["blogid"]}' class='btn btn-outline-primary'>Read More...</a>
          <div style='float: right;'><span style='color: DimGray;'>Yazar:</span>
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
          <h2 class="text-center text-danger mb-3  ">Katogeriler</h2>
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
        $firstListItems .= "<a class='list-group-item list-group-item-action' href='blog.category.show.php?categoryid={$category["categoryid"]}'>{$category["categoryname"]}</a>";
    } else {
        $secondListItems .= "<a href='blog.category.show.php?categoryid={$category["categoryid"]}' class='list-group-item list-group-item-action'>{$category["categoryname"]}</a>";
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
      <?php include 'footer.php';?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
  </html>
