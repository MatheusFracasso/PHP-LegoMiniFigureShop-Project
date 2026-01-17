<?php

namespace App\Controllers;

class HomeController
{
    public function home(array $parameters = []): void
    {
        $pageTitle = "Home Page";
        $message = "Welcome to the Lego Mini Figures Store!";

        $contentView = __DIR__ . '/../Views/home.php';

        require __DIR__ . '/../Views/layout/main.php';
    }
}
