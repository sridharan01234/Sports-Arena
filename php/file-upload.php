<html>
<head>
    <title>file upload</title>
</head>
    <body>
        <form action="file-upload.php" method="post" enctype="multipart/form-data" id="form">
            <h1>Upload file</h1>
            <label for="fileselect">Select file to upload:</label>
            <input type="file" name="file[]" id="fileselect">
            <input type="submit" name="submit" value="Submit">
        </form>
        <?php
        //print_r($_SERVER);
        //print_r($_REQUEST);
        //exit;
        if(isset($_POST['submit'])) {
            $allow = ["pdf" => "application/pdf", "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "jpg" => "image/jpeg"];
            print_r($_POST);
            exit;
            if(isset($_FILES['file'])) {
                $fileCount = count($_FILES['file']['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    $name = $_FILES["file"]["name"][$i];
                    $type = $_FILES["file"]["type"][$i];
                    $extn = pathinfo($name, PATHINFO_EXTENSION);
                    $size = $_FILES['file']['size'][$i];
                    
                    echo $name."<br>";
                    echo $type."<br>";
                    echo $extn."<br>";
        
                    if(!array_key_exists($extn, $allow)) { 
                        echo "Error: Please select a valid file format";
                    } elseif ($extn === 'jpg' && $size < 5 * 1024 * 1024) { 
                        echo "Error: File size must be less than 5 MB";
                            echo '<img src = "" alt = "Uploaded Image">';
                    }
                        else{
                                $targetDir = "files/"; 
                                $targetFile = $targetDir . basename($_FILES["file"]["name"]);  
        
                                if (file_exists($targetFile)) {
                                    echo "This file already exists";
                                } else {
                                    if (move_uploaded_file($_FILES["file"]["tmp_name"][$i], $targetFile)) {
                                        echo "Your image was uploaded successfully";
                                    } else {
                                        echo "There was an error uploading your image";
                                    }
                                }
                            }
                        }
                    }
                         else {
                            echo "File upload failed";
                         }
                        }

        ?>
        