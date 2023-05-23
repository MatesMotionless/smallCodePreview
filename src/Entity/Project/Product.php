<?php

namespace App\Entity\Project;


use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\Project\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @Gedmo\Loggable()
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['product_info:read']],
    denormalizationContext: ['groups' => ['product_info:write']],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['categoryProducts.category' => "exact"])]
#[ApiFilter(RangeFilter::class, properties: ['productVariants.stock'])]
#[ApiFilter(OrderFilter::class, properties: ['productVariants.price.price', 'sequence', 'id', 'externalId'])]
class Product implements Translatable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['product_info:read', 'product_info:write'])]
    private $id;

    #[Gedmo\Translatable]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['product_info:read', 'product_info:write', 'searchable'])]
    private $name;

    #[Gedmo\Translatable]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['product_info:read', 'product_info:write'])]
    private $menu_name;

    #[Gedmo\Translatable]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'text')]
    #[Groups(['product_info:read', 'product_info:write', 'searchable'])]
    private $description;

    #[Gedmo\Translatable]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['product_info:read', 'product_info:write'])]
    private $title;


    #[ORM\Column(type: 'boolean')]
    #[Groups(['product_info:read', 'product_info:write'])]
    private $isActive;

    #[ORM\Column(type: 'integer')]
    #[Groups(['product_info:read', 'product_info:write'])]
    private $sequence = 999;


    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductVariant::class, orphanRemoval: true)]
    #[Groups(['product_info:read', 'searchable'])]
    private $productVariants;

    #[ORM\ManyToOne(targetEntity: Producer::class, inversedBy: 'Product')]
    #[Groups(['product_info:read', 'product_info:write'])]
    private $producer;


    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['product_info:read', 'product_info:write'])]
    private $state = 'draft';

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: CategoryProduct::class, cascade: ['persist'])]
    private Collection $categoryProducts;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['product_info:read', 'product_info:write', 'searchable'])]
    private ?string $externalId = null;


    public function __construct()
    {
        $this->productVariants = new ArrayCollection();
        $this->categoryProducts = new ArrayCollection();
    }

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

    public function getMenuName(): ?string
    {
        return $this->menu_name;
    }

    public function setMenuName(?string $menu_name): self
    {
        $this->menu_name = $menu_name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }


    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    public function setSequence(int $sequence): self
    {
        $this->sequence = $sequence;

        return $this;
    }


    public function getTextGeneral(): ?string
    {
        return $this->textGeneral;
    }

    public function setTextGeneral(?string $textGeneral): self
    {
        $this->textGeneral = $textGeneral;

        return $this;
    }

    /**
     * @return Collection|ProductVariant[]
     */
    public function getProductVariants(): Collection
    {
        return $this->productVariants;
    }

    public function addProductVariant(ProductVariant $productVariant): self
    {
        if (!$this->productVariants->contains($productVariant)) {
            $this->productVariants[] = $productVariant;
            $productVariant->setProduct($this);
        }

        return $this;
    }

    public function removeProductVariant(ProductVariant $productVariant): self
    {
        if ($this->productVariants->removeElement($productVariant)) {
            // set the owning side to null (unless already changed)
            if ($productVariant->getProduct() === $this) {
                $productVariant->setProduct(null);
            }
        }

        return $this;
    }

    public function getProducer(): ?Producer
    {
        return $this->producer;
    }

    public function setProducer(?Producer $producer): self
    {
        $this->producer = $producer;

        return $this;
    }



    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, CategoryProduct>
     */
    public function getCategoryProducts(): Collection
    {
        return $this->categoryProducts;
    }

    public function addCategoryProduct(CategoryProduct $categoryProduct): self
    {
        if (!$this->categoryProducts->contains($categoryProduct)) {
            $this->categoryProducts->add($categoryProduct);
            $categoryProduct->setProduct($this);
        }

        return $this;
    }

    public function removeCategoryProduct(CategoryProduct $categoryProduct): self
    {
        if ($this->categoryProducts->removeElement($categoryProduct)) {
            // set the owning side to null (unless already changed)
            if ($categoryProduct->getProduct() === $this) {
                $categoryProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }


}
