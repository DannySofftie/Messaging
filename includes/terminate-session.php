<?php

session_start();
unset($_SESSION['usermail']);

header("Location: ../index.php");