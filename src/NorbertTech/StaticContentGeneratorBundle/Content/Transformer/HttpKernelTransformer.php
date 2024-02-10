<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content\Transformer;

use NorbertTech\StaticContentGeneratorBundle\Content\Content;
use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\Transformer;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

final class HttpKernelTransformer implements Transformer
{
    private KernelInterface $kernel;

    private RouterInterface $router;

    public function __construct(KernelInterface $kernel, RouterInterface $router)
    {
        $this->kernel = $kernel;
        $this->router = $router;
    }

    public function transform(Source $source) : Content
    {
        $kernelBrowser = new HttpKernelBrowser($this->kernel);
        $kernelBrowser->request(
            'GET',
            $path = $this->router->generate($source->routerName(), $source->parameters()),
            [],
            [],
            [
                'HTTP_HOST' => $this->kernel->getContainer()->getParameter('router.request_context.host') ?? 'localhost',
                'HTTPS' => $this->kernel->getContainer()->getParameter('router.request_context.scheme')  === 'https',
            ]
        );

        if ($kernelBrowser->getResponse()->getStatusCode() > 299) {
            throw new \RuntimeException('Can\'t generate static content for route ' . $source->routerName());
        }

        $content = $kernelBrowser->getResponse()->getContent();

        if ($content === false) {
            throw new \RuntimeException('Can\'t generate static content for route ' . $source->routerName());
        }

        return new Content($path, $content);
    }
}
