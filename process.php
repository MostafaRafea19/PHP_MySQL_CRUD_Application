<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "crud";

$name = '';
$location = '';
$update = false;
$id = 0;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $location = $_POST['location'];

        $_SESSION['message'] = "Record has been saved";
        $_SESSION['msg_type'] = "success";

        $sql = "INSERT INTO data (name, location) VALUES ('$name', '$location')";

        // use exec() because no results are returned
        $conn->exec($sql);

        header("Location: index.php");
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        $conn->query("DELETE FROM data WHERE id=$id");

        $_SESSION['message'] = "Record has been deleted";
        $_SESSION['msg_type'] = "danger";

        header("Location: index.php");
    }

    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $update = true;
        $result = $conn->query("SELECT * FROM data WHERE id=$id");
        $data = $result->fetch();
        if (count($data)) {
            // Old values
            $name = $data['name'];
            $location = $data['location'];
        }
    }

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $location = $_POST['location'];

        $conn->query("UPDATE data SET name='$name', location='$location' WHERE id=$id");

        $_SESSION['message'] = "Record has been updated";
        $_SESSION['msg_type'] = "warning";

        header("Location: index.php");
    }
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;