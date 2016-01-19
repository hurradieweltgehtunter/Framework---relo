<?php

    class uploadHandler {

        function __construct()
        {

            switch($_POST['action'])
            {
                case 'beprofilepic':
                    $target_dir = "../data/img/_users/";
                    break;

                case 'userimage':
                case 'profilepic':
                default:
                    $target_dir = "data/img/_users/";
                    break;
            }
            
            $imageFileType = pathinfo($_FILES['file']['name'][0],PATHINFO_EXTENSION);

            switch($_POST['action'])
            {
                case 'userimage':
                    $uniqueFilename = $this->getRandomUniqueFilename($imageFileType, $_SESSION['user_id']);
                    break;

                case 'profilepic':
                    $uniqueFilename = $this->getRandomUniqueFilename($imageFileType, $_SESSION['user_id']);
                    break;

                case 'beprofilepic':
                    $uniqueFilename = $this->getRandomUniqueFilename($imageFileType, $_SESSION['beuser_id']);
                    break;
            }

            $filename = $uniqueFilename . '.' . $imageFileType;
            $target_file = $target_dir . $filename;          
            $uploadOk = 1;
            
            // Check if image file is a actual image or fake image

            $check = getimagesize($_FILES["file"]["tmp_name"][0]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $errormsg = "Falscher Dateityp. Nur jpg, png und gif erlaubt. Datei wurde nicht hochgeladen.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $errormsg = "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Check file size
            if ($_FILES["file"]["size"][0] > 1000000) {
                $errormsg = "Deine Datei ist zu groß. Max. 1MB. Datei wurde nicht hochgeladen.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $errormsg = "Falscher Dateityp. Nur jpg, png und gif erlaubt. Datei wurde nicht hochgeladen.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo json_encode(array('status'=>0, 'error'=>$errormsg));
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["file"]["tmp_name"][0], $target_file)) {
                    $uploadtime = date('U');
                    $imageid = null;
                    switch($_POST['action'])
                    {
                        case 'userimage':
                            database::Query('INSERT INTO files SET filename=:var1, user_id=:var2, comment=:var3, `date`=:var4', array("var1"=>$filename, "var2"=>$_SESSION['user_id'], "var3"=>"", "var4"=>$uploadtime), $imageid);
                            break;

                        case 'profilepic':
                            //Delete old profilepic
                            $RS = database::Query('SELECT profilepic FROM users WHERE id =' . $_SESSION['user_id'] . ';', array());
                            if($RS[0]['profilepic'] != '' && file_exists($target_dir . $RS[0]['profilepic']) && strpos($RS[0]['profilepic'], '_default') === false)
                                unlink($target_dir . $RS[0]['profilepic']);

                            database::Query('UPDATE users SET profilepic=:var1 WHERE id=' . $_SESSION['user_id'] . ';', array("var1"=>$filename));
                            break;

                        case 'beprofilepic':
                            //Delete old profilepic
                            $RS = database::Query('SELECT profilepic FROM users WHERE id =' . $_SESSION['beuser_id'] . ';', array());
                            if(file_exists($target_dir . $RS[0]['profilepic']) && strpos($RS[0]['profilepic'], '_default') === false)
                                unlink($target_dir . $RS[0]['profilepic']);

                            database::Query('UPDATE users SET profilepic=:var1 WHERE id=' . $_SESSION['beuser_id'] . ';', array("var1"=>$filename));
                            break;
                    }

                    echo json_encode(array('status'=>1, 'file'=>array('id'=>$imageid, 'filename'=>$target_file, 'date'=>date('d.m.Y H:i', $uploadtime))));
                    
                } else {
                    echo json_encode(array('status'=>0, 'error'=>"Fehler beim Upload"));
                }
            }
        }

        function getRandomUniqueFilename($ext, $userid)
        {
            $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789&()-_=+';
            $filename = "";
            $randStringLen = 8;

            while(strlen($filename) < $randStringLen) {
                $randChar = substr(str_shuffle($charset), mt_rand(0, strlen($charset)), 1);
                $filename .= $randChar;
            }

            $RS = database::Query('SELECT * FROM files WHERE user_id = ' . $userid . ' AND filename=:var1;', array("var1"=>$filename));
            if(count($RS) > 0)
                $this->getRandomUniqueFilename($ext);
            else
                return $filename;
        }

    }

?>