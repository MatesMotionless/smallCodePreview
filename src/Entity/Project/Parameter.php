<?php

namespace App\Entity\Project;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Project\ParameterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ParameterRepository::class)]
class Parameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['category:read', 'category:write', 'product_variant:read', 'product_variant:write', 'product_info:read', 'product_info:write'])]
    private $id;

    #[ORM\Column(type: 'text')]
    #[Groups(['category:read', 'category:write', 'product_variant:read', 'product_variant:write', 'product_info:read', 'product_info:write', 'searchable'])]
    private $data;

    #[ORM\ManyToOne(targetEntity: ParameterGroup::class, inversedBy: 'parameter')]
    #[Groups(['category:read', 'category:write', 'product_variant:read', 'product_variant:write', 'product_info:read', 'product_info:write', 'searchable'])]
    private $parameterGroup;

    #[ORM\ManyToOne(targetEntity: ProductVariant::class, inversedBy: 'parameters')]
    private $productVariant;

    #[ORM\Column(nullable: true)]
    private ?int $sequence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getParameterGroup(): ?ParameterGroup
    {
        return $this->parameterGroup;
    }

    public function setParameterGroup(?ParameterGroup $parameterGroup): self
    {
        $this->parameterGroup = $parameterGroup;

        return $this;
    }

    public function getProductVariant(): ?ProductVariant
    {
        return $this->productVariant;
    }

    public function setProductVariant(?ProductVariant $productVariant): self
    {
        $this->productVariant = $productVariant;

        return $this;
    }

}
