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
//!Hangi kategorinin seçildiğini anlamak için(chatgpt)
$activeCategoryID = isset($_GET['categoryid']) ? $_GET['categoryid'] : null;

$sql = "SELECT * FROM categories";
$SORGU = $DB->prepare($sql);
$SORGU->execute();
$categories = $SORGU->fetchAll(PDO::FETCH_ASSOC);

/* var_dump($categories);
die(); */

$Categories = "";
foreach ($categories as $category) {
    $isActive = ($activeCategoryID == $category["categoryid"]) ? 'active' : '';
    $Categories .= "<a class='dropdown-item {$isActive}' href='blog.category.show.php?categoryid={$category["categoryid"]}'>{$category["categoryname"]}</a>";
}

?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle  <?=($activePage == 'categoryies') ? 'active' : '';?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Categoryies
          </a>
          <ul class="dropdown-menu">
          <?php echo $Categories; ?>
          </ul>
        </li>
        <?php
require_once 'db.php';
//!Hangi kategorinin seçildiğini anlamak için(chatgpt)
$activeWriterID = isset($_GET['writerid']) ? $_GET['writerid'] : null;

$sql = "SELECT * FROM users WHERE roleuser=1";
$SORGU = $DB->prepare($sql);
$SORGU->execute();
$users = $SORGU->fetchAll(PDO::FETCH_ASSOC);

/* var_dump($users);
die(); */

$Users = "";
foreach ($users as $user) {
    $isActive = ($activeWriterID == $user["userid"]) ? 'active' : '';
    $Users .= "<a class='dropdown-item {$isActive}' href='blog.writer.php?writerid={$user["userid"]}'>{$user["username"]}</a>";

}
?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle  <?=($activePage == 'writers') ? 'active' : '';?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
          <a class="nav-link text-danger  dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Hello! <?php echo $_SESSION['userName'] ?>
          </a>
          <ul class="dropdown-menu">
            <?php if ($_SESSION['role'] == 1) {?>
            <li><a class="dropdown-item  <?=($activePage == 'blogAdd') ? 'active' : '';?>" href="blog.add.php?idUser=<?php echo $_SESSION['id'] ?>">Add Blog</a></li>
            <?php }?>
            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal">Change Password <i class="bi bi-arrow-repeat"></i></a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
      <?php }?>
      <form action="search.blog.php" class="d-flex" role="search" method="get">
        <input class="form-control me-2" type="search" name="form_search_word" placeholder="Search Blog" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<?php
//!form_email post edilmişse
if (isset($_POST['form_submit'])) {
    $errors = array();
    require 'db.php';
    //! Post edilen verileri değişkenlere atama
    $oldPassword = $_POST['form_oldpassword'];
    $olRePassword = $_POST['form_repassword'];
    $newPassword = $_POST['form_newpassword'];

    // Form gönderildi
    // 1.DB'na bağlan
    // 2.SQL hazırla ve çalıştır
    // 3.Gelen sonuç 1 satırsa GİRİŞ BAŞARILI değilse, BAŞARISIZ
    //! Eğer boş alan varsa uyarı mesajı
    if (empty($_POST["form_oldpassword"]) || empty($_POST["form_newpassword"])) {
        $errors[] = "Both Fields are required !";
    } else if ($_POST['form_oldpassword'] != $_POST['form_repassword']) {
        $errors[] = "Passwords Do Not Match!";
    }
    //! Boş alan yoksa
    else {
        //! SQL hazırlama ve çalıştırma
        //! formdan gelen email ile db de varsa
        $id = $_SESSION['id'];
        $sql = "SELECT * FROM users WHERE userid = :id";
        $SORGU = $DB->prepare($sql);
        $SORGU->bindParam(':id', $id);
        $SORGU->execute();
        $CEVAP = $SORGU->fetchAll(PDO::FETCH_ASSOC);

        /*    var_dump($CEVAP);
        echo "Gelen cevap " . count($CEVAP) . " adet satırdan oluşuyor";
        die(); */
        //! Gelen sonuç 1 satırsa db de kullanıcı var olduğunu anlarız
        if (count($CEVAP) == 1) {
            //! Kullanıcının şifresini doğrulama
            //? posttan gelen ile db den gelen karşılaştırma
            //? password_verify() fonksiyonu ile
            $hashedOldPassword = $CEVAP[0]['userpassword'];
            if (password_verify($oldPassword, $hashedOldPassword)) {
                //return true;
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $id = $_SESSION['id'];
                $sql = "UPDATE users SET userpassword	 = '$hashedNewPassword' WHERE userid = :id";
                $SORGU = $DB->prepare($sql);
                $SORGU->bindParam(':id', $id);
                $SORGU->execute();
                $approves[] = "Password Changed Successfully...";
            } else {
                //return false;
                //!Şifreler Eşleşmiyorsa
                $errors[] = "INCORRECT Email OR PASSWORD MATCH!...";

            }
        } else {
            //! Kullanıcı yoksa
            $errors[] = "There Is No Such User !.";
        }
    }

}
?>
<?php
//! Hata mesajlarını göster
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<div class='position-fixed top-0 end-0 p-3' style='z-index: 5'>
      <div class='toast align-items-center text-white bg-danger border-0' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='5000'>
          <div class='d-flex'>
              <div class='toast-body'>
              $error
              </div>
              <button type='button' class='btn-close btn-close-white me-2 m-auto' data-bs-dismiss='toast' aria-label='Close'></button>
          </div>
      </div>
  </div>";
    }
}
?>
<?php
//! Başarılı mesajlarını göster
if (!empty($approves)) {
    foreach ($approves as $approve) {
        echo "<div class='position-fixed top-0 end-0 p-3' style='z-index: 5'>
      <div class='toast align-items-center text-white bg-success border-0' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='5000'>
          <div class='d-flex'>
              <div class='toast-body'>
              $approve
              </div>
              <button type='button' class='btn-close btn-close-white me-2 m-auto' data-bs-dismiss='toast' aria-label='Close'></button>
          </div>
      </div>
  </div>";
    }
}
?>