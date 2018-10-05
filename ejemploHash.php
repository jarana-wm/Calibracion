<?php
class Password {
    public static function hash($password) {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
    }
    public static function verify($password, $hash) {
        return password_verify($password, $hash);
    }
}
$hash = Password::hash('12345678');
echo "contraseña encriptada: ".$hash."<br>";
$nueva = Password::hash('12345678');
echo "contraseña encriptada: ".$nueva."<br>";
if (Password::verify($nueva, $hash)) {
    echo '\nContraseña correcta!';
} else {
    echo "Contraseña incorrecta!";
}

?>