<?php
  header("Content-type: text/html; charset=utf-8");
  include 'category.php';
  //Link test: /postcontroller?action=create
  $action = $_GET["action"]; //create/update/detele/search
  //echo $action;
  //die();
  $post = new Post();
  //Create for Ajax call
  if ($action =="createAjax"){
    //Link test: 
    $post->Title = $_GET["Title"];
    $post->Img = $_GET["Img"]; 
    $post->Summary = $_GET["Summary"];
    $post->Content = $_GET["Content"];
    $post->CategoryName = $_GET["CategoryName"];
    //var_dump($post);
    //die();
    //Insert input to database
    createAjax($post);
  }
  else if ($action=="deleteAjax"){
    //Link test: 
    $post->PostId = $_GET["PostId"];
    //Delete post from database
    deleteAjax($post->PostId);
  }
  else if ($action=="searchAjax"){
    //Search request
    //Link test:
    $keyword = $_GET["keyword"];
    searchAjax($keyword);
  }
  else if ($action=="editAjax"){
    //Link test: 
    $post->PostId = $_GET["PostId"];
    $post->Title = $_GET["Title"];
    $post->Img = $_GET["Img"]; 
    $post->Summary = $_GET["Summary"];
    $post->Content = $_GET["Content"];
    $post->CategoryId = $_GET["CategoryId"];
    //Edit data
    editAjax($post);
  }
  else if ($action=="manageAjax"){
    //Link test:
    manageAjax();
  }

  function createAjax($post){
    //Connect to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bepphuot";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, 'UTF8');
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    // PostId thì nên sửa lại file sql cho nó nhập tự động
    // CategoryId nghiên cứu làm sao nhập tên danh mục mà insert vào bảng chuyển thành id danh mục hoặc là đổi sql từ id thành name cho dễ
    // UserId chắc lấy session password, tên đăng nhập để lấy id từ bảng users
    $sql = "INSERT INTO posts(Title,ViewNumber,Img,Summary,Content,CategoryId,UserId,DatePost)
    VALUES('$post->Title', 0, '$post->Img','$post->Summary','$post->Content', $post->CategoryId, $post->UserId, Now();";
    // echo $sql;
    // die();

    if ($conn->query($sql) === TRUE) {
      echo '<script language="javascript">';
      echo 'alert("Thêm bài viết thành công!")';
      echo '</script>';
    } else {
      echo '<script language="javascript">';
      echo 'alert( "Lỗi. Thêm bài viết không thành công.")';
      echo '</script>';
    }

    // ngắt kết nối data
    $conn->close();
  }
  function deleteAjax($PostId){
    //2.Insert input data to database
    //Connect to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bepphuot";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM posts WHERE PostId=" . $postId;
    //echo $sql;
    //die();
    if ($conn->query($sql) === TRUE) {
      echo '<script language="javascript">';
      echo 'alert("Xóa bài viết thành công!")';
      echo '</script>';
    } else {
      echo '<script language="javascript">';
      echo 'alert( "Lỗi. Xóa bài viết không thành công.")';
      echo '</script>';
    }
    // ngắt kết nối data
    $conn->close();
  }
  function searchAjax($keyword){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bepphuot";
    
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, 'UTF8');
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
    
    $sql = "SELECT * FROM posts WHERE Title LIKE '%$keyword%' LIMIT 100;";
    //echo $sql;
    //die();
    $result = mysqli_query($conn, $sql);
    
    //Return result as JSON
    if (mysqli_num_rows($result) > 0) {
      // Convert $result to JSON format
      $data= $result->fetch_all(MYSQLI_ASSOC);
      //var_dump($data);
      //die();
      
      echo json_encode($data);
    } else {
      echo "{ message: \"No result found\"}";
    }
    // ngắt kết nối data
    mysqli_close($conn);
  }
  function editAjax($post){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bepphuot";
    
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, 'UTF8');
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
    
    $sql = "UPDATE posts SET Title='$post->Title', Img='$post->Img', Summary='$post->Summary', Content='$post->Content', CategoryId=$post->CategoryId; 
    WHERE PostId=$post->PostId ";
    //echo $sql;
    //die();
    if ($conn->query($sql) === TRUE) {
      echo '<script language="javascript">';
      echo 'alert("Sửa bài viết thành công!")';
      echo '</script>';
    } else {
      echo '<script language="javascript">';
      echo 'alert( "Lỗi. Sửa bài viết không thành công.")';
      echo '</script>';
    }
    // ngắt kết nối data
    mysqli_close($conn);
  }
  function manageAjax(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bepphuot";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, 'UTF8');
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    // Chức năng đếm số lần xem mỗi khi nhấn f5 bài viết (truyền vào Id bài viết) 
    function dem_lan_xem($PostId)
    {
        $PostId = intval($PostId);
        $sql = "UPDATE  posts  SET ViewNumber = ViewNumber+1 WHERE PostId = $PostId";
        mysql_query($sql);
        return;
    }

    $sql = "SELECT * FROM posts";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
      // Convert $result to JSON format
      $data= $result->fetch_all(MYSQLI_ASSOC);
      //var_dump($data);
      //die();
      echo json_encode($data);
    } else {
      echo "0 results";
    }
    $conn->close();
  }
?>