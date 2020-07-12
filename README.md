# Symfony Static Content Generator Bundle

Generate static html pages from all Symfony routes available in your system. 

### Installation

```bash
composer require norberttech/static-content-generator-bundle
```

### Configuration

```php
<?php
// bundles.php

return [
    NorbertTech\StaticContentGeneratorBundle\StaticContentGeneratorBundle::class => ['all' => true],
];
```

```yaml
# static_content_generator.yaml

static_content_generator:
  output_directory: "%kernel.project_dir%/output"
```

### Usage

Transform all routes into static html pages. 

```bash
bin/console static-content-generator:generate:routes
```

Copy all assets from `public` directory into output directory

```bash
bin/console static-content-generator:copy:assets
```

#### Parametrized Routes

In order to dump parametrized routes you need to register service that implements `SourceProvider` interface
which will return all possible combinations of parameters for given route you would like to dump.

Don't forget that this services must have `static_content_generator.source_provider` tag. 

```yaml
# service.yaml 

services:

    FixtureProject\Source\ParametrizedSourceProvider:
        tags: ['static_content_generator.source_provider']

``` 

Controller: 
```php
<?php declare(strict_types=1);

namespace FixtureProject\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticRoutesController extends AbstractController
{
    /**
     * @Route("/parametrized/{param1}/{param2}", name="parametrized_route")
     */
    public function withParameters(string $param1, string $param2) : Response
    {
        return $this->render('parametrized.html.twig', ['param1' => $param1, 'param2' => $param2]);
    }
}
```

Provider: 

```php
<?php

declare(strict_types=1);

namespace FixtureProject\Source;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;

final class ParametrizedSourceProvider implements SourceProvider
{
    public function all() : array
    {
        return [
            new Source('parametrized_route', ['param1' => 'first-param', 'param2' => 'second-param']),
        ];
    }
}
```

This will generate `/output/parametrized/first-param/second-param/index.hmtml`

### Testing

```php
php -S localhost:8000 -t output
```
