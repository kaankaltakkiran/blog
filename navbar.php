<?php
@session_start();
?>
<nav class="navbar navbar-expand-lg  bg-dark border-bottom border-body" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">My Blog</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
        <li class="nav-item">
          <a class="nav-link  <?=($activePage == 'index') ? 'active' : '';?>" href="index.php">Home</a>
        </li>
        </li>
        <?php
require_once 'db.php';
$sql = "SELECT * FROM categories";
$SORGU = $DB->prepare($sql);
$SORGU->execute();
$categories = $SORGU->fetchAll(PDO::FETCH_ASSOC);

/* var_dump($categories);
die(); */

$Categories = "";
foreach ($categories as $category) {
    $Categories .= "<a class='dropdown-item' href='index.php?kategori={$category["categoryid"]}'>{$category["categoryname"]}</a>";
}

?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Categoryies
          </a>
          <ul class="dropdown-menu">
          <?php echo $Categories; ?>
          </ul>
        </li>
        <?php
require_once 'db.php';
$sql = "SELECT * FROM users";
$SORGU = $DB->prepare($sql);
$SORGU->execute();
$users = $SORGU->fetchAll(PDO::FETCH_ASSOC);

/* var_dump($categories);
die(); */

$Users = "";
foreach ($users as $user) {
    $Users .= "<a class='dropdown-item' href='blog.show.php?writerid={$user["userid"]}'>{$user["username"]}</a>";
}
?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Writers
          </a>
          <ul class="dropdown-menu">
          <?php echo $Users; ?>
          </ul>
        </li>
      </ul>
      <?php if ($_SESSION['isLogin'] == 1) {?>
       <ul class="navbar-nav">
       <li class="nav-item dropdown">
          <a class="nav-link text-danger  dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Hello! <?php echo $_SESSION['userName'] ?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item  <?=($activePage == 'blogAdd') ? 'active' : '';?>" href="blog.add.php">Yazı Ekle</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
      <?php }?>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search Blog" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
