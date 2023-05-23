<?php

namespace App\Entity\Project;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Project\ProductVariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ProductVariantRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['product_variant:read']],
    denormalizationContext: ['groups' => ['product_variant:write']],
)]
class ProductVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['product_variant:read', 'product_variant:write', 'product_info:read', 'product_info:write', 'searchable'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['product_variant:read', 'product_variant:write'])]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['product_variant:read', 'product_variant:write', 'searchable'])]
    private $externalId;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productVariants')]
    private $product;


    #[ORM\OneToMany(targetEntity: Parameter::class, mappedBy: 'productVariant')]
    #[Groups(['searchable'])]
    private $parameters;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): self
    {
        $this->externalId= $externalId;

        return $this;
    }


    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }


    /**
     * @return Collection<int, Parameter>
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    public function addParameter(Parameter $parameter): self
    {
        if (!$this->parameters->contains($parameter)) {
            $this->parameters[] = $parameter;
            $parameter->setProductVariant($this);
        }

        return $this;
    }

    public function removeParameter(Parameter $parameter): self
    {
        if ($this->parameters->removeElement($parameter)) {
            // set the owning side to null (unless already changed)
            if ($parameter->getProductVariant() === $this) {
                $parameter->setProductVariant(null);
            }
        }

        return $this;
    }



}
