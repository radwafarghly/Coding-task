<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> -->
    <style type="text/css">
        .row {
            margin-bottom: 15px;
            margin-top: 15px;
        }

        .col-md-4 {
            float: left;
            width: 33.33%;
            padding: 0 15px;
        }

        @media (max-width: 600px) {
            .row {
                width: 100%;
            }

            .col-md-4 {
                width: 100%;
                float: none;
            }
        }

        #image-preview {
            text-align: left;
            float: left;
        }

        #image-preview img {
            width: 100px;
            height: 100px;
            float: left;

        }

        body {
            /* background: #555; */
           margin: 100px 0
        }

        .container {
            
            background: white;
            padding: 10px;

        }
    </style>

</head>

<body>
    
    <div class="container">
        <h2>Save User</h2>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? '' ?>">
            <div class="row">
                <div class="col-md-4 ">
                    <label for="firstname">First Name</label>
                    <input type="text" class="form-control" id="exampleInputfirstname" name="firstname" required>
                </div>
                <div class="col-md-4 ">
                    <label for="lastname">Last Name</label>
                    <input type="text" class="form-control" id="exampleInputlastname" name="lastname" required>
                </div>
                <div class="col-md-4 ">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ">

                    <div id="image-preview"></div>
                </div>
            </div>

            <input type="submit" name="submit" id="submit" class="btn btn-primary">
        </form>
    </div>

    <div class="container">
        <?php

        require_once "conn.php";
        if (isset($_POST['submit'])) {

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];


            // Check the CSRF token.
            if (!isset($_POST["csrf_token"]) || !hash_equals($_POST["csrf_token"], $_SESSION["csrf_token"])) {
                die("Invalid CSRF token.");
            }



            // Get the uploaded file.
            $image = $_FILES["image"];

            // Check if the file is larger than 2MB.
            if ($image["size"] > 2097152) {
                echo "The file is too large. The maximum file size is 2MB.";
                exit;
            }
            // Move the file to the upload directory.
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($image["name"]);
            move_uploaded_file($image["tmp_name"], $target_file);

            // insert in data base
            if ($firstname != "" && $lastname != "" && $target_file != "") {
                $sql = "INSERT INTO users (`first_name`, `last_name`, `image_path`) VALUES ('$firstname', '$lastname', '$target_file')";

                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                    // header("location: index.php");
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "First Name, Last Name and Image cannot be empty!";
            }
        }
        ?>
    </div>

    <script>
        function validateImage() {
            // Get the image file.
            const image = document.getElementById("image").files[0];

            // Check if the file is an image.
            if (!image.type.match("image.*")) {
                alert("Please select a valid image file.");
                return false;
            }

            // Get the image preview.
            const imagePreview = document.getElementById("image-preview");

            // Create a thumbnail of the image.
            const thumbnail = new Image();
            thumbnail.src = URL.createObjectURL(image);
            thumbnail.onload = function() {
                imagePreview.appendChild(thumbnail);
            };

            return true;
        }

        // On change of the image file, validate it.
        document.getElementById("image").addEventListener("change", validateImage);
    </script>
</body>

</html>