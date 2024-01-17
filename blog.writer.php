<?php
@session_start();
$activePage = "writers";
?>
<?php
require_once 'db.php';
$id = $_GET["writerid"];

/* SELECT blogs.*,users.*
FROM users
INNER JOIN blogs
ON blogs.writerid=users.userid WHERE users.userid=:writerid */
$SORGU = $DB->prepare("SELECT blogs.*, users.*, categories.*
FROM users
INNER JOIN blogs ON blogs.writerid = users.userid
INNER JOIN categories ON blogs.categoryid = categories.categoryid WHERE users.userid=:writerid");
$SORGU->bindParam(':writerid', $id);
$SORGU->execute();
$blogs = $SORGU->fetchAll(PDO::FETCH_ASSOC);
/* echo '<pre>';
print_r($blogs);
die();
 */
//!Eğer blog silme isteği gelirse silme işlemini yap ve tekrardan yazar sayfasına yönlendir.
if (isset($_GET['remove'])) {
    require 'db.php';
    $remove_id = $_GET['remove'];
    $id = $_SESSION['id'];

    $sql = "DELETE FROM blogs WHERE blogid = :remove";
    $SORGU = $DB->prepare($sql);

    $SORGU->bindParam(':remove', $remove_id);

    $SORGU->execute();
    echo "<script>
    alert('The blog has been deleted. You are redirected to the Writer page...!');
    window.location.href = 'blog.writer.php?writerid={$id}';
    </script>";
}
//!Toplu silme işlemi
if (isset($_GET['delete_all'])) {
    require 'db.php';
    $id = $_SESSION['id'];
    $sql = "DELETE FROM blogs WHERE writerid =:id ";
    $SORGU = $DB->prepare($sql);
    $SORGU->bindParam(':id', $id);
    $SORGU->execute();
    echo "<script>
    alert('The blog has been deleted. You are redirected to the Index page...!');
    window.location.href = 'index.php';
    </script>";
}
//! Kontrol: Eğer kayıt yoksa hata sayfasına yönlendir
if (empty($blogs)) {
    echo "<script>
alert('Theree is no record in the database. You are redirected to the home page...!');
window.location.href = 'index.php';
</script>";
}
?>

  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo $blogs[0]['username'] ?>'s Blog</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body>
    <!--  Navbar için navbar.php dosyasını include ediyoruz. -->
      <?php include 'navbar.php';?>
  <!--     Header Start -->
      <div class="container">
        <div class="row justify-content-center ">
          <div class="col-6">
        <h1 class='alert alert-primary mt-3 text-center'><?php echo $blogs[0]['username'] ?>'s Blog</h1>
                <?php
//!Chatgpt çözümü
//! Veritabanından alınan datetime ddeğerini güncelle
$id = $_SESSION['id'];
$DB->prepare("UPDATE users SET lastlogintime = NOW() WHERE userid = :id")
    ->execute(['id' => $id]);
// Sistem saat dilimini belirle
date_default_timezone_set('Europe/Istanbul');
// Veritabanından alınan datetime değeri
$veritabaniDatetime = $blogs[0]['lastlogintime'];

// Şu anki datetime
$suAnkiDatetime = new DateTime();

// Veritabanından alınan datetime'ı DateTime nesnesine dönüştür
$veritabaniDatetimeObj = new DateTime($veritabaniDatetime);

// İki datetime arasındaki farkı hesapla
$zamanFarki = $suAnkiDatetime->diff($veritabaniDatetimeObj);
echo "<h5 class='text-danger text-center '>";
if ($_SESSION['id'] == $blogs[0]['userid']) {
// Eğer zaman farkı 365 günü aşıyorsa yıl, gün ve saat olarak ekrana yazdır
    if ($zamanFarki->i < 1) {
        echo " Last Login Time: " . "was active just now";
    } else {
        // Eğer zaman farkı 365 günü aşıyorsa yıl, gün ve saat olarak ekrana yazdır
        if ($zamanFarki->days > 365) {
            echo "Last Login Time: " . $zamanFarki->format('%y year, %d day, %h hour');
        } elseif ($zamanFarki->days > 0 || $zamanFarki->h > 24) {
            echo "Last Login Time: " . $zamanFarki->format('%d day, %h hour');
        } else {
            // Zaman farkı 60 dakikayı geçerse saat ve dakika, geçmezse sadece dakika
            if ($zamanFarki->h >= 1) {
                echo "Last Login Time: " . $zamanFarki->format('%h hour, %i minute');
            } else {
                echo "Last Login Time: " . $zamanFarki->format('%i minute');
            }
        }
    }
    echo "</h5>";

}
?>
        </div>
        </div>
        <?php
if ($_SESSION['id'] == $blogs[0]['userid']) {
    //!Silmek için oluşturlan bootstrap modal yapısı
    echo "
     <!-- Modal -->
     <div class='modal fade' id='staticBackdrop' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
       <div class='modal-dialog'>
         <div class='modal-content'>
           <div class='modal-header'>
             <h1 class='modal-title fs-5' id='exampleModalLabel'>Delete All Blogs? </h1>
             <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
           </div>
           <div class='modal-body'>
           {$_SESSION['userName']}, Are You Sure You Want to Delete All Blogs?
           </div>
           <div class='modal-footer'>
             <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
             <a href='blog.writer.php?delete_all={$blogs[0]['userid']}' class='btn btn-danger'>Delete All Blogs </a>
           </div>
         </div>
       </div>
     </div>
     ";
    echo "
   <p class='text-end '><a href='blog.writer.php?delete_all={$blogs[0]['userid']}' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#staticBackdrop'>Delete All My Blogs
   <i class='bi bi-trash'></i>
   </a></p>
";
}
?>

    <?php
//!publish olsun denen ve tarihleri uygun olan blogları getir
foreach ($blogs as $blog) {
    //!Chatgpt çözümü ile modala farklı id vermek
    $modalId = "exampleModal{$blog['blogid']}";
    //!Silmek için oluşturlan bootstrap modal yapısı
    echo "
     <!-- Modal -->
     <div class='modal fade' id='{$modalId}' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
       <div class='modal-dialog'>
         <div class='modal-content'>
           <div class='modal-header'>
             <h1 class='modal-title fs-5' id='exampleModalLabel'>Delete {$blog['title']}? </h1>
             <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
           </div>
           <div class='modal-body'>
           {$blog['username']}, Are you sure you want to delete the blog?
           </div>
           <div class='modal-footer'>
             <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
             <a href='blog.writer.php?remove={$blog['blogid']}' class='btn btn-danger'>Delete </a>
           </div>
         </div>
       </div>
     </div>
     ";
    if ($blog['ispublish'] == 1 && strtotime($blog['startdate']) <= time() && strtotime($blog['lastdate']) >= time()) {
        echo "
        <div class='row'>
      <div class='col-6'>
          <div class='card mb-4 shadow-sm'>
              <a href='blog.show.php?blogid={$blog["blogid"]}'>
                  <img class='card-img-top' src='images/{$blog['blogimage']}' height='400' width='100%' />
              </a>
              <div class='card-body'>
                  <p class='card-text'>
                      <h3>{$blog['title']}</h3>
                      <p>{$blog['summary']}</p>
                  </p>
                  <div class='d-flex justify-content-between align-items-center'>
                      <div class='btn-group'>
                          <a class='btn btn-outline-info' href='blog.show.php?blogid={$blog["blogid"]}'>Read More...</a>
                      </div>";

        if ($_SESSION['id'] == $blog['userid']) {
            echo "<a class='btn btn-outline-success' href='blog.update.php?blogid={$blog["blogid"]}'>Update Blog</a>";
            echo "<a href='blog.writer.php?remove={$blog['blogid']}' class='btn btn-outline-danger' data-bs-toggle='modal' data-bs-target='#{$modalId}'>
           Delete Blog
           <i class='bi bi-trash'></i>
          </a>
          ";
        }

        echo "<div style='float: right;'><span style='color: DimGray;'>Writer:</span>
                  <a href='blog.writer.php?writerid={$blog["userid"]}' class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover'>{$blog['username']}</a>
                  <br>
                  <span style='color: DimGray;'>Category:</span>
                          <a href='blog.category.show.php?categoryid={$blog["categoryid"]}' class='link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>{$blog['categoryname']}</a>
              </div>
          </div>
      </div>
  </div>
</div>";
    }
    //!Blog sahibi koşulsuz tüm içeriği görsün
    else if ($_SESSION['id'] == $blog['userid']) {
        echo "
      <div class='col-6'>
          <div class='card mb-4 shadow-sm'>
              <a href='blog.show.php?blogid={$blog["blogid"]}'>
                  <img class='card-img-top' src='images/{$blog['blogimage']}' height='400' width='100%' />
              </a>
              <div class='card-body'>
                  <p class='card-text'>
                      <h3>{$blog['title']}</h3>
                      <p>{$blog['summary']}</p>
                  </p>
                  <div class='d-flex justify-content-between align-items-center'>
                      <div class='btn-group'>
                          <a class='btn btn-outline-info' href='blog.show.php?blogid={$blog["blogid"]}'>Read More...</a>
                      </div>
                      <a class='btn btn-outline-success' href='blog.update.php?blogid={$blog["blogid"]}'>Update Blog</a>
                      <a href='blog.writer.php?remove={$blog["blogid"]}' class='btn btn-outline-danger' data-bs-toggle='modal' data-bs-target='#{$modalId}'>
                     Delete Blog
                     <i class='bi bi-trash'></i>
                    </a>
                      <div style='float: right;'><span style='color: DimGray;'>Writer:</span>
                          <a href='blog.writer.php?writerid={$blog["userid"]}' class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover'>{$blog['username']}</a>
                          <br>
                          <span style='color: DimGray;'>Category:</span>
                          <a href='blog.category.show.php?categoryid={$blog["categoryid"]}' class='link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>{$blog['categoryname']}</a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>";
    }
}

;
?>

    </div>
  </div>
      <?php include 'footer.php';?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
  </html>
