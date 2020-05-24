<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonneRepository::class)
 */
class Personne
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     * @Assert\Length(min=1, max=30, exactMessage="Votre nom doit faire {{ limit }} caractères")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\Regex(pattern="/^(H|F)$/", message="Le sexe doit être H ou F.")
     */
    private $sexe;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\GreaterThan(0)
     * @Assert\LessThan(120)
     */
    private $age;

    /**
     * @ORM\ManyToOne(targetEntity=Adresse::class, inversedBy="personnes")
     * @ORM\JoinTable(name="adresse")
     */
    private $adresse;

    /**
     * @ORM\ManyToMany(targetEntity=Animal::class, inversedBy="personnes", cascade="persist")
     * @ORM\JoinTable(name="personne_animal")
     */
     private $animal;

    public function __construct()
    {
        $this->animal = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection|Animal[]
     */
    public function getAnimal(): Collection
    {
        return $this->animal;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animal->contains($animal)) {
            $this->animal[] = $animal;
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animal->contains($animal)) {
            $this->animal->removeElement($animal);
        }

        return $this;
    }

    public function __toString() {
        return $this->getNom().' - '.$this->getSexe().' - '.$this->getAge();
    }
}
