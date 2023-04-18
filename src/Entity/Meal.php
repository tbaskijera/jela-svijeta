<?php

namespace App\Entity;

use App\Repository\MealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MealRepository::class)]
class Meal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'meals')]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'meals')]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'meals')]
    private Collection $ingredients;

    #[ORM\OneToMany(mappedBy: 'meal', targetEntity: MealTranslation::class)]
    private Collection $mealTranslations;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
        $this->mealTranslations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * @return Collection<int, MealTranslation>
     */
    public function getMealTranslations(): Collection
    {
        return $this->mealTranslations;
    }

    public function addMealTranslation(MealTranslation $mealTranslation): self
    {
        if (!$this->mealTranslations->contains($mealTranslation)) {
            $this->mealTranslations->add($mealTranslation);
            $mealTranslation->setMeal($this);
        }

        return $this;
    }

    public function removeMealTranslation(MealTranslation $mealTranslation): self
    {
        if ($this->mealTranslations->removeElement($mealTranslation)) {
            // set the owning side to null (unless already changed)
            if ($mealTranslation->getMeal() === $this) {
                $mealTranslation->setMeal(null);
            }
        }

        return $this;
    }
}
