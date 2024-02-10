<?php declare(strict_types=1);

namespace FixtureProject\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StaticRoutesController extends AbstractController
{
    public function index() : Response
    {
        return $this->render('index.html.twig');
    }

    public function indexHtml() : Response
    {
        return $this->render('index.html.twig');
    }

    public function versionHtml() : Response
    {
        return $this->render('index.html.twig');
    }

    public function apiJson() : Response
    {
        return $this->json([
            'content' => 'json',
        ]);
    }

    public function apiXML() : Response
    {
        return $this->render('api.xml.twig');
    }

    public function namedRoute() : Response
    {
        return $this->render('named/route.html.twig');
    }

    public function withParameters(string $param1, string $param2) : Response
    {
        return $this->render('parametrized.html.twig', ['param1' => $param1, 'param2' => $param2]);
    }
}
