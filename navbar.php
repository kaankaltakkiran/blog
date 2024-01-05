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
          <a class="nav-link active" aria-current="page" href="#">Ana Sayfa</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Türler
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Tür1</a></li>
            <li><a class="dropdown-item" href="#">Tür2</a></li>
            <li><a class="dropdown-item" href="#">Tür3</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Genel</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Yazarlar
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Yazar1</a></li>
            <li><a class="dropdown-item" href="#">Yazar2</a></li>
            <li><a class="dropdown-item" href="#">Yazar3</a></li>
          </ul>
        </li>
        <?php if ($_SESSION['isLogin'] == 1) {?>
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Yazarlar
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Yazı Ekle</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
        <?php }?>
      </ul>
      <?php if ($_SESSION['isLogin'] == 1) {?>
       <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link disabled text-danger "  href="#">Hello! <?php echo $_SESSION['adsoyad'] ?></a>
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
