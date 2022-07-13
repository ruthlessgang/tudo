<?php
    session_start();
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        header('location: /index.php');
        die();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = hash('sha256',$_POST['password']);

        include('includes/db_connect.php');
        $ret = pg_prepare($db, "login_query", "select * from users where username = $1 and password = $2");
        $ret = pg_execute($db, "login_query", array($_POST['username'], $password));

        if (pg_num_rows($ret) === 1) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $_POST['username'];

            if ($_SESSION['username'] === 'admin')
                $_SESSION['isadmin'] = true;

            header('location: /index.php');
            die();
        }
        else {
            $error = true;
        }
    }
?>

<html>
    <head>
        <title>TUDOs/Log In</title>
        <link rel="stylesheet" href="style/style.css">
    </head>
    <body>
        <?php include('includes/header.php'); ?>
        <div id="content">
            <form class="center_form" action="login.php" method="POST">
                <h1>Log In:</h1>
                <img src="https://storage.cloud.google.com/test_bss/identitas-logo-bank-sampoerna-e1533178439998.png" alt="Google Cloud Platform" width="200">
                <p>Currently we are in the Alpha testing phase, thus you may log in if you recieved credentials from
                the admin. Otherwise you can admin the few pages linked at the bottom :)
                </p>
                <input name="username" placeholder="Username"><br><br>
                <input type="password" name="password" placeholder="Password"><br><br>
                <input type="submit" value="Log In"> 
                <?php if (isset($error)){echo "<span style='color:red'>Login Failed</span>";} ?>
                <br><br>
                <?php include('includes/login_footer.php'); ?>
            </form>
            <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="file" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" name="upload" class="btn btn-primary form-control">Upload</button>
            </div>
        </form>
        <?php
            include "storage.php";
            include "DbConnect.php";
            $storage = new storage();
            /*Create a new bucket*/
            // $storage->createBucket('bss');

            /*List all bucket*/
            // $storage->listBuckets();

            /*Upload a file*/
            if (isset($_POST['upload'])) {
                $db = new DbConnect();
                $conn = $db->connect();
                $sql = "INSERT INTO files values(null, :name, :size, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $_FILES['file']['name']);
                $stmt->bindParam(':size', $_FILES['file']['size']);
                $stmt->execute();
                $storage->uploadObject('test_bss', $_FILES['file']['name'], $_FILES['file']['tmp_name']);
            }

            /*List objects under a bucket*/
            //$storage->listObjects('test_bss');

            /*Delete an object*/
            //$storage->deleteObject('test_bss', 'gcp.png');

            /*Delete a bucket*/
            //$storage->deleteBucket('sahani');

            /*Download file*/
            // $storage->downloadObject('durgesh', 'notes.txt', "D:\\tutorials\\notes_new.txt");

            /*Edit a file*/
           /*$str =  file_get_contents('gs://durgesh/notes.txt');
           echo "<pre>";
           echo $str;
           file_put_contents('gs://durgesh/notes.txt', "LearnWebCoding")*/

        $imageUrl = $storage->getImageUrl('test_bss', 'identitas-logo-bank-sampoerna-e1533178439998.png');
        ?>

        </div>
    </body>
</html>
