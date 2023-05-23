<?php

namespace App\Entity\Project;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Project\ParameterGroupTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ParameterGroupTypeRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['parameter_group_type:read']],
    denormalizationContext: ['groups' => ['parameter_group_type:write']],
    paginationEnabled: false
)]
class ParameterGroupType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['parameter_group_type:read', 'parameter_group:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['parameter_group_type:read', 'parameter_group_type:read', 'parameter_group:read'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: ParameterGroup::class)]
    private Collection $parameterGroups;

    public function __construct()
    {
        $this->parameterGroups = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ParameterGroup>
     */
    public function getParameterGroups(): Collection
    {
        return $this->parameterGroups;
    }

    public function addParameterGroup(ParameterGroup $parameterGroup): self
    {
        if (!$this->parameterGroups->contains($parameterGroup)) {
            $this->parameterGroups->add($parameterGroup);
            $parameterGroup->setType($this);
        }

        return $this;
    }

    public function removeParameterGroup(ParameterGroup $parameterGroup): self
    {
        if ($this->parameterGroups->removeElement($parameterGroup)) {
            // set the owning side to null (unless already changed)
            if ($parameterGroup->getType() === $this) {
                $parameterGroup->setType(null);
            }
        }

        return $this;
    }


}
