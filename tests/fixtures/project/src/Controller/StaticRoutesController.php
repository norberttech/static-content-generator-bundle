<?php declare(strict_types=1);

namespace FixtureProject\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticRoutesController extends AbstractController
{
    /**
     * @Route("index.html", name="index_html")
     */
    public function index() : Response
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("api.json", name="api")
     */
    public function apiJson() : Response
    {
        return $this->json([
            'content' => 'json',
        ]);
    }

    /**
     * @Route("/named/route", name="named_route")
     */
    public function namedRoute() : Response
    {
        return $this->render('named/route.html.twig');
    }

    /**
     * @Route("/parametrized/{param1}/{param2}", name="parametrized_route")
     */
    public function withParameters(string $param1, string $param2) : Response
    {
        return $this->render('parametrized.html.twig', ['param1' => $param1, 'param2' => $param2]);
    }
}
