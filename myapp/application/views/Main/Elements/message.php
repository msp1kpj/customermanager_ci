<?php
if(isset($message) && is_array($message) && array_key_exists("message", $message) && array_key_exists("class", $message) && strlen($message["message"])){
    echo '<div class="alert alert-'. $message["class"].'">'.$message["message"].'</div>';
}