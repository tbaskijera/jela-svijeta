<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[Groups('ingredient')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('ingredient')]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Groups('ingredient')]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: Meal::class, mappedBy: 'ingredients')]
    private Collection $meals;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: IngredientTranslation::class)]
    private Collection $ingredientTranslations;

    public function __construct()
    {
        $this->meals = new ArrayCollection();
        $this->ingredientTranslations = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Meal>
     */
    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(Meal $meal): self
    {
        if (!$this->meals->contains($meal)) {
            $this->meals->add($meal);
            $meal->addIngredient($this);
        }

        return $this;
    }

    public function removeMeal(Meal $meal): self
    {
        if ($this->meals->removeElement($meal)) {
            $meal->removeIngredient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, IngredientTranslation>
     */
    public function getIngredientTranslations(): Collection
    {
        return $this->ingredientTranslations;
    }

    public function addIngredientTranslation(IngredientTranslation $ingredientTranslation): self
    {
        if (!$this->ingredientTranslations->contains($ingredientTranslation)) {
            $this->ingredientTranslations->add($ingredientTranslation);
            $ingredientTranslation->setIngredient($this);
        }

        return $this;
    }

    public function removeIngredientTranslation(IngredientTranslation $ingredientTranslation): self
    {
        if ($this->ingredientTranslations->removeElement($ingredientTranslation)) {
            // set the owning side to null (unless already changed)
            if ($ingredientTranslation->getIngredient() === $this) {
                $ingredientTranslation->setIngredient(null);
            }
        }

        return $this;
    }
}
