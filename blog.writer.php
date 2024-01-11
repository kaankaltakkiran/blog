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
print_r($blogs); */

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

        } elseif ($zamanFarki->i < 60) {
            echo "Last Login Time: " . $zamanFarki->format('%i minute');
        } else {
            // Aksi durumda sadece tam formatı ekrana yazdır
            echo "Last Login Time: " . $zamanFarki->format('%h hour,%i minute');
        }
    }
    echo "</p>";
}
?>
        </div>

        </div>
  <!--     Header End -->
    <div class="row">
    <?php
foreach ($blogs as $blog) {
    echo "
   <div class='col-6'>
   <div class='card mb-4 shadow-sm'>
   <a href='blog.show.php?blogid={$blog["blogid"]}'> <img class='card-img-top' src='images/{$blog['blogimage']}' height='400' width='100%'  />
   </a>
     <div class='card-body'>
       <p class='card-text'>
         <h3>{$blog['title']}</h3>
         <p>{$blog['summary']}</p>
       </p>
       <div class='d-flex justify-content-between align-items-center'>
         <div class='btn-group'>
         <a class='btn btn-outline-success' href='blog.show.php?blogid={$blog["blogid"]}'>Read More...</a>
         </div>
         ";
    //! Chatgpt çözümü
    if ($_SESSION['id'] == $blog['userid']) {
        // Display the "Update Blog" button if they match
        echo "<a class='btn btn-danger' href='blog.update.php?blogid={$blog["blogid"]}'>Update Blog</a>";
    }
    echo "
         <div style='float: right;'><span style='color: DimGray;'>Yazar:</span>
          <a href='blog.writer.php?writerid={$blog["userid"]}' class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover'>{$blog['username']}</a>
          <br>
          <span style='color: DimGray;'>Category:</span> {$blog['categoryname']}
      </div>
       </div>
     </div>
   </div>
 </div>
 ";

}
?>

    </div>
  </div>
      <?php include 'footer.php';?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
  </html>
