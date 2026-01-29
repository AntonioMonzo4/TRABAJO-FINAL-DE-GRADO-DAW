<?php
// debug/hash.php 
// Generar hash de contraseña para "Admin1234" 

echo password_hash("Admin1234", PASSWORD_DEFAULT);
