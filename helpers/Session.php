<?php
namespace helpers;

class Session
{
    /**
     * Inicia la sesión si no está iniciada
     */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Guarda un valor en la sesión
     */
    public static function set(string $clave, mixed $valor): void
    {
        self::init();
        $_SESSION[$clave] = $valor;
    }

    /**
     * Devuelve el valor de una clave o null si no existe
     */
    public static function get(string $clave): mixed
    {
        self::init();
        return $_SESSION[$clave] ?? null;
    }

    /**
     * Comprueba si existe una clave en la sesión
     */
    public static function has(string $clave): bool
    {
        self::init();
        return isset($_SESSION[$clave]);
    }

    /**
     * Elimina una clave de la sesión
     */
    public static function delete(string $clave): void
    {
        self::init();
        if (isset($_SESSION[$clave])) {
            unset($_SESSION[$clave]);
        }
    }

    /**
     * Destruye completamente la sesión
     */
    public static function destroy(): void
    {
        self::init();
        session_unset();
        session_destroy();
    }

    /**
     * Inicia sesión para un usuario
     */
    public static function login(mixed $usuario): void
    {
        self::init();
        $_SESSION['user'] = $usuario;
    }

    /**
     * Cierra sesión del usuario actual
     */
    public static function logout(): void
    {
        self::init();
        unset($_SESSION['user']);
        self::destroy();
    }

    /**
     * Comprueba si hay un usuario logueado
     */
    public static function isLogged(): bool
    {
        self::init();
        return isset($_SESSION['user']);
    }
}