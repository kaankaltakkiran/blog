<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>

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
    WHERE blogs.title LIKE :form_search_word and ispublish = 1
    LIMIT 5";
    $SORGU = $DB->prepare($sql);

    $SORGU->bindParam(':form_search_word', $searchWord);

    $SORGU->execute();

    $results = $SORGU->fetchAll(PDO::FETCH_ASSOC);
    /*    var_dump($results);
    echo "Gelen cevap " . count($results) . " adet satırdan oluşuyor";
    die(); */
    ?>
 <!--     Bloglar Start -->
 <div class="container">
 <div class="row g-3 mt-4 ">
          <h1 class='alert alert-info mt-3 text-center'>Search Results...</h1>
          <div class="col-12 border ">
<?php
// Sonuçları listele
    if (count($results) > 0) {
        foreach ($results as $result) {
            echo "
          <div class='row mt-3 '>
              <div class='col-4'>
              <a href='blog.show.php?blogid={$result["blogid"]}'>  <img src='images/{$result['blogimage']}' class='card-img-top' alt='Blog Image'></a>
              </div>
              <div class='col-8'>
              <h3>{$result['title']} <span class='text-danger  fs-6' style='float: right;'>{$result['blogdate']}</span></h3>
              <p>{$result['summary']}</p>
              <a href='blog.show.php?blogid={$result["blogid"]}' class='btn btn-outline-info'>Read More...</a>
              <div style='float: right;'><span style='color: DimGray;'>Writer:</span>
              <a href='blog.writer.php?writerid={$result["userid"]}' class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover'>{$result['username']}</a>
              <br>
              <span style='color: DimGray;'>Category:</span> {$result['categoryname']}
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
