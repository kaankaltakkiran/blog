<?php
@session_start();
$activePage = "index";
require 'up.html.php';
?>

         <!--  Navbar için navbar.php dosyasını include ediyoruz. -->
         <?php include 'navbar.php';?>
  <?php
require_once 'db.php';

// Arama kelimesini al
$search_word = isset($_GET['form_search_word']) ? $_GET['form_search_word'] : '';

// Arama kelimesi boşsa hata mesajını göster
if (empty($search_word)) {
    echo "<script>
    alert('The search word cannot be empty. Please enter a word.!!!');
    window.location.href = 'index.php';
  </script>";
    die();
} else {
    $searchWord = $_GET['form_search_word'];
    $searchWord = "%{$searchWord}%";
    $sql = "SELECT blogs.*, users.*, categories.*
    FROM users
    INNER JOIN blogs ON blogs.writerid = users.userid
    INNER JOIN categories ON blogs.categoryid = categories.categoryid
    WHERE blogs.title LIKE :form_search_word and ispublish = 1 AND CURDATE() BETWEEN blogs.startdate AND blogs.lastdate ORDER BY blogid DESC
    LIMIT 5";
    $SORGU = $DB->prepare($sql);

    $SORGU->bindParam(':form_search_word', $searchWord);

    $SORGU->execute();

    $blogs = $SORGU->fetchAll(PDO::FETCH_ASSOC);
    /*    var_dump($blogs);
    echo "Gelen cevap " . count($blogs) . " adet satırdan oluşuyor";
    die(); */
    ?>
 <!--     Bloglar Start -->
 <div class="container">
 <div class="row g-3 mt-4 ">
          <h1 class='alert alert-info mt-3 text-center'>Search Results...</h1>
          <div class="col-12 border ">
<?php
// Sonuçları listele
    if (count($blogs) > 0) {
        foreach ($blogs as $blog) {
            echo "
          <div class='row mt-3 '>
              <div class='col-4'>
              <a href='blog.show.php?blogid={$blog["blogid"]}'>  <img src='images/{$blog['blogimage']}' class='card-img-top' alt='Blog Image'></a>
              </div>
              <div class='col-8'>
              <h3>{$blog['title']} <span class='text-danger  fs-6' style='float: right;'>{$blog['startdate']}</span></h3>
              <p>{$blog['summary']}</p>
              <a href='blog.show.php?blogid={$blog["blogid"]}' class='btn btn-outline-info'>Read More...</a>
              <div style='float: right;'><span style='color: DimGray;'>Writer:</span>
              <a href='blog.writer.php?writerid={$blog["userid"]}' class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover'>{$blog['username']}</a>
              <br>
              <span style='color: DimGray;'>Category:</span>
              <a href='blog.category.show.php?categoryid={$blog["categoryid"]}' class='link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>{$blog['categoryname']}</a>
          </div>

              </div>
              </div>
      ";
            echo "<hr>";
        }

    } else {
        echo "<script>
      alert('No Such Result Found!!! You are redirected to the home page...');
      window.location.href = 'index.php';
    </script>";
        die();
    }
}
?>
   </div>
   </div>
   <?php require 'modal.php';?>
<?php include 'footer.php';?>
  <?php require 'down.html.php';?>
