<?php
namespace helpers;

class Session
{
    /**
     * Inicia la sesión si no está iniciada
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Guarda un valor en la sesión
     */
    public static function set($clave, $valor): void
    {
        self::start();
        $_SESSION[$clave] = $valor;
    }

    /**
     * Devuelve el valor de una clave o null si no existe
     */
    public static function get($clave): mixed
    {
        self::start();
        return $_SESSION[$clave] ?? null;
    }

    /**
     * Comprueba si existe una clave en la sesión
     */
    public static function has($clave): bool
    {
        self::start();
        return isset($_SESSION[$clave]);
    }

    /**
     * Elimina una clave de la sesión
     */
    public static function delete($clave): void
    {
        self::start();
        if (isset($_SESSION[$clave])) {
            unset($_SESSION[$clave]);
        }
    }

    /**
     * Destruye completamente la sesión
     */
    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

    /**
     * Inicia sesión para un usuario
     */
    public static function login($usuario): void
    {
        self::start();
        $_SESSION['user'] = $usuario;
    }

    /**
     * Cierra sesión del usuario actual
     */
    public static function logout(): void
    {
        self::start();
        unset($_SESSION['user']);
        self::destroy();
    }

    /**
     * Comprueba si hay un usuario logueado
     */
    public static function isLogged(): bool
    {
        self::start();
        return isset($_SESSION['user']);
    }
}