<?php
namespace controllers;
use League\Plates\Engine;
class AdminController
{
    private $templates;
    public function __construct(Engine $templates) {
        $this->templates = $templates;
    }
    public function adminPanel() {
        echo "Welcome to the Admin Panel.";
    }
}