<?php
namespace App\Entity;


use Algolia\SearchBundle\Entity\Aggregator;
use App\Entity\Project\Product;
use App\Entity\Project\ProductVariant;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AlgoliaProduct extends Aggregator
{
    public static function getEntities(): array
    {
        return [
            Product::class,
            ProductVariant::class
        ];
    }
}