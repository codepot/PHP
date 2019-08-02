<?php
ob_start();
session_start();
require_once 'db_config.php';
?>

<html>
    <head>
        <title>ADD FORUM</title>

        <link href="decor.css" rel="stylesheet" type="text/css"/>
        <link href="decor2.css" rel="stylesheet" type="text/css"/>
        <link href="decor3.css" rel="stylesheet" type="text/css"/>
        <link href="boxes.css" rel="stylesheet" type="text/css"/>

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
                    login as <span style="color:brown;"><?php echo $_SESSION["loginStatus"]; ?></span>:<span style="color:green;"><?php echo ' '.$_SESSION['loginUser'] ?> (<?php echo $_SESSION['loginUserFullName'] ?>)</span>
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

                    $forumName = mysql_real_escape_string($_POST["forumName"]);
                    $description = mysql_real_escape_string($_POST["description"]);
                    $loginUserName = $_SESSION['loginUser'];

                    $query = "SELECT * FROM `forum` WHERE ForumName= BINARY '$forumName'";

                    $result = mysql_query($query, $conn);
                    $count = mysql_numrows($result);

                    if ($count > 0) {
                        $message = $_SESSION["loginUserFullName"] . " should select another forum name";
                    } else {

                        $query = "INSERT INTO forum (ForumName, Picture, Description, Status, Moderator) VALUES "
                                . "('$forumName','{$data}','$description' ,'pending', '{$loginUserName}');";

                        $result = mysql_query($query, $conn);

                        if ($result) {
                            $message = "You have requested to create a forum successfully!";
                            header("Location: forum.php");
                        } else {
                            $message = "Error. Try Again!";
                            echo "<span class='signupMessage'>" . mysql_errno($conn) . ": " . mysql_error($conn) . "</span>";
                        }
                    }
                } else {
                    $message = "Please select a valid photo";
                }



                mysql_close($conn);
            }
        }
        ?>
        <form class="upload_form" method='post' enctype='multipart/form-data' action=''>
            <?php
            $role = $_SESSION['loginStatus'];
            if($role=='administrator'){
                echo '<span class="formtitle">CREATE A FORUM</span>';
            }
            else if ($role=='moderator'){
                echo '<span class="formtitle">REQUEST TO CREATE A FORUM</span>';
            }
            ?>
            
            <a href="process.php?action=signout">SIGN OUT</a><a href="mainpage.php">HOME</a><a href="forum.php">BACK</a>
            <p class="signupMessage"><?php if (isset($message)) echo $message; ?></p>
            <input name="image" accept="image/*" type="file" id="file-input"/>
            <div id="thumb-output"></div>
            <input type="text" placeholder="Forum Name" name="forumName" value="<?php if (isset($_POST['forumName'])) echo $_POST['forumName']; ?>"/>
            <input type="text" placeholder="Description" name="description" value="<?php if (isset($_POST['description'])) echo $_POST['description']; ?>" />
            <button>submit</button>
        </form>


        <footer><div class="upload_footer">Liam Le's Term Project</div></footer>
    </body>
</html>