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

### Testing

```php
php -S localhost:8000 -t output
```