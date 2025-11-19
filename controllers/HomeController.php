<?php
namespace controllers;

use League\Plates\Engine;
class HomeController
{
    private $templates;
    public function __construct(Engine $templates) {
        $this->templates = $templates;
    }
    public function landingPage() {
        echo $this->templates->render('home/landing');
    }
}