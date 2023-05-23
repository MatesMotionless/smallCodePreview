<?php

namespace App\Serializer\Normalizer;

use Algolia\SearchBundle\Searchable;
use App\Api\ProductVariantApiModel;
use App\Entity\Project\Product;
use App\Repository\Project\CategoryRepository;
use App\Repository\Project\ParameterRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductNormalizer implements NormalizerInterface
{
    public function __construct(
        private CategoryRepository  $categoryRepository
    )
    {
    }

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        if ($object->getId() != null and $object->getName() != null) {
            $normalizedProduct = [];
            $normalizedProduct['ProductId'] = $object->getId();
            $normalizedProduct['name'] = strip_tags($object->getName());
            if ($object->getDescription()) {
                $normalizedProduct['description'] = strip_tags($object->getDescription());
            }
            if ($object->getProducer()) {
                $normalizedProduct["producer"]["id"] = $object->getProducer()->getId();
                $normalizedProduct["producer"]["name"] = $object->getProducer()->getName();
            }

            $categories = $this->categoryRepository->getProductCategoriesForIndexing($object);

            if (is_array($categories) and !empty($categories)){
                $normalizedProduct["categories"] = $categories;
            }
            return $normalizedProduct;
        } else {
            return null;
        }

    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof Product && Searchable::NORMALIZATION_FORMAT === $format;
    }
}