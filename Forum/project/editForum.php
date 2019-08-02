<?php
ob_start();
session_start();
require_once 'db_config.php';
if (!isset($_SESSION['loginUser']) && !isset($_SESSION['loginStatus'])) {
    session_unset();
    session_destroy();
    header("location:index.php");
    exit();
} else {
    $role = $_SESSION['loginStatus'];
    if ($role == 'user') {
        session_unset();
        session_destroy();
        header("location:index.php");
        exit();
    }
}
?>

<html>
    <head>
        <title>ADD FORUM</title>

        <link href="decor.css" rel="stylesheet" type="text/css"/>
        <link href="decor2.css" rel="stylesheet" type="text/css"/>
        <link href="decor3.css" rel="stylesheet" type="text/css"/>
        <link href="boxes.css" rel="stylesheet" type="text/css"/>
        <?php
        $forumName = rawurldecode($_GET['forumName']);
        $conn = mysql_connect($host, $username, $password);
        if (!$conn) {
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db($dbname, $conn);

        $query = "SELECT * FROM `forum` WHERE ForumName= BINARY '$forumName'";

        $result = mysql_query($query, $conn);
        $count = mysql_numrows($result);

        if ($count > 0) {
            $picture = mysql_result($result, 0, "Picture");
            $description = mysql_result($result, 0, "Description");
        }
        mysql_close($conn);
        ?>

        <script src="jquery-3.1.1.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $('#file-input').on('change', function () { //on file input change
                    if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
                    {
                        $('#thumb-output').html(''); //clear html of output element
                        var data = $(this)[0].files; //this file data

                        $.each(data, function (index, file) { //loop though each file
                            if (/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)) { //check supported file type
                                var fRead = new FileReader(); //new filereader
                                fRead.onload = (function (file) { //trigger function on successful read
                                    return function (e) {
                                        var img = $('<img width="100%" style="margin-bottom: 15px;"/>').attr('src', e.target.result); //create image element 
                                        $('#thumb-output').append(img); //append image to output element
                                    };
                                })(file);
                                fRead.readAsDataURL(file); //URL representing the file's data.
                            }
                        });

                    } else {
                        alert("Your browser doesn't support File API!"); //if File API is absent
                    }
                });

            });
        </script>

    </head>

    <body>
        <div style="position: absolute; top: 0; right: 0; width: 400px; text-align:right; font-family:tahoma, verdana, arial, sans-serif; font-weight: bold; font-size: 12px; ">
            login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' ' . $_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
        </div>
        <?php
        if (count($_POST) > 0) {
            /* Form Required Field Validation */

            foreach ($_POST as $key => $value) {
                if (empty($_POST[$key])) {
                    $message = ucwords($key) . " is required";
                    break;
                }
            }

            if (isset($_FILES['image']) && !isset($message)) {

                $conn = mysql_connect($host, $username, $password);

                if (!$conn) {
                    die('Could not connect: ' . mysql_error());
                }

                mysql_select_db($dbname, $conn);



                if (!empty($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])) {

                    //$tmpName = $_FILES['image']['tmp_name'];
                    $imageData = file_get_contents($_FILES['image']['tmp_name']);

                    $data = addslashes($imageData);

                    $forumNewName = mysql_real_escape_string($_POST["forumName"]);
                    $newDescription = mysql_real_escape_string($_POST["description"]);


                    $query = "SELECT * FROM `forum` WHERE ForumName= BINARY '$forumNewName'";

                    $result = mysql_query($query, $conn);
                    $count = mysql_numrows($result);

                    if ($count > 0 && $forumName != $forumNewName) {
                        $message = $_SESSION["loginUserFullName"] . " should select another forum name";
                    } else {
                        $query = "UPDATE forum SET Picture='{$data}', ForumName='$forumNewName', Description='$newDescription' WHERE ForumName='$forumName'";


                        $result = mysql_query($query, $conn);

                        if ($result) {
                            $message = "You have update forum profile successfully!";
                            header("Location: forum.php");
                        } else {
                            $message = "Error. Try Again!";
                            echo "<span class='signupMessage'>" . mysql_errno($conn) . ": " . mysql_error($conn) . "</span>";
                        }
                    }
                } else {
                    //$message = "Please select a valid photo";
                    $forumNewName = mysql_real_escape_string($_POST["forumName"]);
                    $newDescription = mysql_real_escape_string($_POST["description"]);


                    $query = "SELECT * FROM `forum` WHERE ForumName= BINARY '$forumNewName'";

                    $result = mysql_query($query, $conn);
                    $count = mysql_numrows($result);

                    if ($count > 0 && $forumName != $forumNewName) {
                        $message = $_SESSION["loginUserFullName"] . " should select another forum name";
                    } else {
                        $query = "UPDATE forum SET ForumName='$forumNewName', Description='$newDescription' WHERE ForumName='$forumName'";


                        $result = mysql_query($query, $conn);

                        if ($result) {
                            $message = "You have update forum profile successfully!";
                            header("Location: forum.php");
                        } else {
                            $message = "Error. Try Again!";
                            echo "<span class='signupMessage'>" . mysql_errno($conn) . ": " . mysql_error($conn) . "</span>";
                        }
                    }
                }



                mysql_close($conn);
            }
        }
        ?>
        <form class="upload_form" method='post' enctype='multipart/form-data' action=''>
            <?php
            $role = $_SESSION['loginStatus'];
            echo '<span class="formtitle">EDIT-FORUM</span>';
            ?>            
            <a href="process.php?action=signout">SIGN-OUT</a><a href="forum.php">BACK</a><a href="mainpage.php">HOME</a>
            <p class="signupMessage"><?php if (isset($message)) echo $message; ?></p>
            <input name="image" accept="image/*" type="file" id="file-input"/>
            <div id="thumb-output"><img style="max-height: 200px; padding-bottom: 20px;" onload="this.style.opacity = '1';" src="data:image/png;base64, <?php echo base64_encode($picture); ?> "/></div>
            <input type="text" placeholder="Forum Name" name="forumName" value="<?php echo $forumName; ?>"/>
            <input type="text" placeholder="Description" name="description" value="<?php echo $description; ?>" />
            <button>Submit</button>
        </form>


        <footer><div class="upload_footer">Liam Le's Term Project</div></footer>
    </body>
</html>