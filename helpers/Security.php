<?php
namespace helpers;
class Security
{
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
    public static function generatePassword($length = 12)
    {
         // Listas de caracteres por tipo
        $minusculas = 'abcdefghijklmnopqrstuvwxyz';
        $mayusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeros = '0123456789';
        $especiales = '!@#$%^&*()-_+=';

        // Aseguramos que haya al menos un carácter de cada tipo
        $password = '';
        $password .= $minusculas[rand(0, strlen($minusculas) - 1)];
        $password .= $mayusculas[rand(0, strlen($mayusculas) - 1)];
        $password .= $numeros[rand(0, strlen($numeros) - 1)];
        $password .= $especiales[rand(0, strlen($especiales) - 1)];

        // Rellenamos el resto de la contraseña hasta 10 caracteres
        $todos = $minusculas . $mayusculas . $numeros . $especiales;
        for ($i = 4; $i < 10; $i++) {
            $password .= $todos[rand(0, strlen($todos) - 1)];
        }

        // Mezclar los caracteres para no dejar los primeros fijos
        $password = str_shuffle($password);

        $password = password_hash( $password, PASSWORD_DEFAULT );
        // Guardar la contraseña
        return $password;
    }

    
}