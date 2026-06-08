<?php

namespace App\Twig;

use ApiPlatform\Metadata\IriConverterInterface;
//use ApiPlatform\Metadata\IriConverterInterface;
//use ApiPlatform\Api\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DataTableTwigExtension extends AbstractExtension
{

    public function __construct(private IriConverterInterface $iriConverter)
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('api_route', [$this, 'apiCollectionRoute']),
            new TwigFunction('api_item_route', [$this, 'apiCollectionRoute']),
        ];
    }

    public function apiCollectionRoute($entityOrClass)
    {
//        $iri = $this->iriConverter->getIriFromResource($context['resource_class'], UrlGeneratorInterface::ABS_PATH, $operation, ['uri_variables' => ['id' => $data['id']]]);

        $x = $this->iriConverter->getIriFromResourceClass($entityOrClass);
//        $x = $this->iriConverter->getIriFromResource($entityOrClass, 0);
        return $x;
    }

    public function apiItemRoute($entityOrClass, $id)
    {

        $x = $this->iriConverter->getIriFromResource($entityOrClass);
        return $x;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

}
