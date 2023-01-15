<?php

    session_start();//initialize session
    if(session_destroy()){
        header("Location: login.php");
    }

?>
