
<?php
class homepage extends page {

    public function get() {

        $form = '<form action="index.php" method="post">';
        
        $form .= '<input type="file"  name="fileToUpload" id="fileToUpload">';
        
        $form .= '<input type="submit" value="Upload " name="submit">';
       
        $form .= '</form> ';
        $this->html .= 'homepage';
        $this->html .= $form;
    }

    public function post()
    {
        $target_file="upload/".basename($_FILES["fileToUpload"]["name"]);
        
                                   // Checking if the file doesnt already exist, and that it is of the correct file format
                           if (!$this->isFileAlreadyExisting($target_file) && $this->isCorrectFileFormat($fileType)
                       ) {
 
                                         // this command uploads the file to the directory specified, and returns true if successful
                               if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
                               {
                                   echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                               }
                                else
                               {
                                   echo "Sorry, there was an error uploading your file.";
                               }
 
                               header('Location: index.php?fileName=' . basename($_FILES["fileToUpload"]["name"]));
 
                           }
                           // If file aready exists or is of incorrect format
                           else
                            {
                                         echo "Sorry, there was an error uploading your file.";
                            }
 
              
    }

}
?>