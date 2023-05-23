<?php

namespace App\Serializer\Normalizer;

use Algolia\SearchBundle\Searchable;
use App\Api\ProductVariantApiModel;
use App\Entity\Project\Product;
use App\Entity\Project\ProductVariant;
use App\Repository\Project\CategoryRepository;
use App\Repository\Project\ParameterRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductVariantNormalizer implements NormalizerInterface
{
    public function __construct(
        private ParameterRepository $parameterRepository
    )
    {
    }

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $apiVariant["VariantId"] = $object->getId();
        $apiVariant["ProductId"] = $object->getProduct()->getId();
        $apiVariant["externalId"] = $object->getExternalId();
        $apiVariant["parameters"] = $this->parameterRepository->getParametersForProductIndex($object);
        return $apiVariant;

    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof ProductVariant && Searchable::NORMALIZATION_FORMAT === $format;
    }
}