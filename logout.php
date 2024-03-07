<?php

//session_start
session_start();

header('location:login.php');
//hilangkan session yg sudah di set
unset($_SESSION['iduser']);
unset($_SESSION['email']);
unset($_SESSION['password']);

session_destroy();
echo "<sript>
('Anda telah keluar dari halaman StokBarang...!')
</sript>";
