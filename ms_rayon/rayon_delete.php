<?php 

if(isset($_POST['delete'])){
    $ret_val = $obj->deleteuser();
    if($ret_val!=""){
        echo '<script type="text/javascript">'; 
        echo 'alert("Record Deleted Successfully");'; 
        echo 'window.location.href = "index.php?page=sttb_upload";';
        echo '</script>';
   }
}

?>
