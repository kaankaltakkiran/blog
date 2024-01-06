<?php
@session_start();
$activePage = "index";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
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
<!--     Carousel Start -->
<h2 class="text-center  text-danger ">Popüler Bloglar</h2>
      <div class="row justify-content-center ">
        <div class="col-6">
        <div id="carouselExampleAutoplaying" class="carousel slide carousel-fade" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="2000">
      <img src="./blog.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>First slide label</h5>
        <p>Some representative placeholder content for the first slide.</p>
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="2000">
    <img src="./blog2 .jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Second slide label</h5>
        <p>Some representative placeholder content for the second slide.</p>
      </div>
    </div>
    <div class="carousel-item"data-bs-interval="2000">
    <img src="./blog3.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Third slide label</h5>
        <p>Some representative placeholder content for the third slide.</p>
      </div>
    </div>
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
         <div class="row mt-3 ">
         <div class="col-4">
          <img src="./blog2 .jpg" class="card-img-top">
         </div>
         <div class="col-8">
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque asperiores amet voluptatum sed provident dignissimos placeat vitae fuga possimus molestias alias tempora, ipsum velit nulla saepe a exercitationem illo cum!</p>
         <a href="#" class="btn btn-primary">Devamını Oku</a>
         </div>
         </div>
         <div class="row mt-3 mb-2  ">
         <div class="col-4">
          <img src="./blog3.jpg" class="card-img-top">
         </div>
         <div class="col-8">
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque asperiores amet voluptatum sed provident dignissimos placeat vitae fuga possimus molestias alias tempora, ipsum velit nulla saepe a exercitationem illo cum!</p>
         <a href="#" class="btn btn-primary">Devamını Oku</a>
         </div>
         </div>
        </div>
        <div class="col-5 border">
         <h2 class="text-center text-danger mb-3  ">Katogeriler</h2>
          <div class="row">
            <div class="col-6">
            <ul class="list-group">
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>


</ul>
            </div>
            <div class="col-6">
            <ul class="list-group">
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
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
