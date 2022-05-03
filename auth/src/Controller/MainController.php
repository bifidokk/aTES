<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController
{
    #[Route('/main')]
    public function index(): Response
    {
        return new Response(
            '<html><body>Hey!</body></html>'
        );
    }
}