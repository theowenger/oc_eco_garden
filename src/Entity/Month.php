<?php

namespace App\Entity;

use App\Repository\MonthRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonthRepository::class)]
class Month
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $monthNumber = null;

    /**
     * @var Collection<int, Advice>
     */
    #[ORM\ManyToMany(targetEntity: Advice::class, inversedBy: 'months')]
    private Collection $advices;

    #[ORM\Column(length: 255)]
    private ?string $monthName = null;

    public function __construct()
    {
        $this->advices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonthNumber(): ?int
    {
        return $this->monthNumber;
    }

    public function setMonthNumber(int $monthNumber): static
    {
        $this->monthNumber = $monthNumber;

        return $this;
    }

    /**
     * @return Collection<int, Advice>
     */
    public function getAdvices(): Collection
    {
        return $this->advices;
    }

    public function addAdvice(Advice $advice): static
    {
        if (!$this->advices->contains($advice)) {
            $this->advices->add($advice);
        }

        return $this;
    }

    public function removeAdvice(Advice $advice): static
    {
        $this->advices->removeElement($advice);

        return $this;
    }

    public function getMonthName(): ?string
    {
        return $this->monthName;
    }

    public function setMonthName(string $monthName): static
    {
        $this->monthName = $monthName;

        return $this;
    }
}
