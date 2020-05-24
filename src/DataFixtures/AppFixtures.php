<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Adresse;
use App\Entity\Animal;
use App\Entity\Espece;
use App\Entity\Personne;

use App\Repository\AdresseRepository;
use App\Repository\AnimalRepository;
use App\Repository\EspeceRepository;
use App\Repository\PersonneRepository;

use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @var EspeceRepository
     */
    private $especeRepository;
    
    /**
     * @var AdresseRepository
     */
    private $adresseRepository;
    
    /**
     * @var AnimalRepository
     */
    private $animalRepository;
    
    /**
     * @var PersonneRepository
     */
    private $personneRepository;

    public function __construct(EspeceRepository $especeRepository, AdresseRepository $adresseRepository, AnimalRepository $animalRepository, PersonneRepository $personneRepository) {
        $this->especeRepository = $especeRepository;
        $this->adresseRepository = $adresseRepository;
        $this->animalRepository = $animalRepository;
        $this->personneRepository = $personneRepository;
    }

    public function load(ObjectManager $manager) {
        
        $faker = Factory::create('fr_FR');

        // Les especes
        for ($i = 0; $i < 20; $i++) {
            $espece = new Espece();
            $espece->setNom(ucfirst($faker->unique()->word));
            $manager->persist($espece);
        }
        $manager->flush();

        // Création des adresses
        for ($i = 0; $i < 250; $i++) {
            $adresse = new Adresse();
            $adresse->setIntitule($faker->buildingNumber . ' ' . ucwords($faker->streetName) . ', ' . $faker->postcode . ' ' . strtoupper($faker->city));
            $manager->persist($adresse);
        }
        $manager->flush();

        // Création des personnes
        $noms = [];
        for ($i = 0; $i < 500; $i++) {
            $personne = new Personne();
            $sexe = $faker->numberBetween(0, 1);


            if ($sexe === 0) {
                $nom = $faker->lastName . ' ' . $faker->firstNameMale;

                $existe = in_array($nom, $noms);

                while ($existe) {
                    $nom = $faker->lastName . ' ' . $faker->firstNameMale;

                    if (!in_array($nom, $noms)) {
                        $existe = false;
                    }
                }
                $noms[] = $nom;

                $personne->setNom($nom);
                $personne->setSexe('M');
            } else {
                $nom = $faker->lastName . ' ' . $faker->firstNameFemale;

                $existe = in_array($nom, $noms);

                while ($existe) {
                    $nom = $faker->lastName . ' ' . $faker->firstNameFemale;

                    if (!in_array($nom, $noms)) {
                        $existe = false;
                    }
                }
                $noms[] = $nom;

                $personne->setNom($nom);
                $personne->setSexe('F');
            }

            $personne->setAge($faker->numberBetween(12, 100));

            $manager->persist($personne);
        }
        $manager->flush();

        $especes = $this->especeRepository->findAll();

        // Création des animaux
        for ($i = 0; $i < 1500; $i++) {
            $animal = new Animal();
            $animal->setNom($faker->firstName());
            $animal->setAge($faker->numberBetween(1, 25));

            // Relation animaux - espèces
            $randEspece = $faker->numberBetween(0, count($especes) - 1);
            $especes[$randEspece]->addAnimal($animal);

            $manager->persist($animal);
        }
        $manager->flush();

        // Relations
        $adresses = $this->adresseRepository->findAll();
        $animals = $this->animalRepository->findAll();
        $personnes = $this->personneRepository->findAll();

        // Relation personnes - adresses
        foreach ($personnes as $personne) {
            $randAdresse = $faker->numberBetween(0, count($adresses) - 1);
            $adresses[$randAdresse]->addPersonne($personne);
        }

        // Relation animaux - personnes
        foreach ($animals as $animal) {
            $randPersonne = $faker->numberBetween(0, count($personnes) - 1);
            $hasPersonne = $faker->numberBetween(0, 8);
            if ($hasPersonne > 1 && $hasPersonne < 5) {
                $animal->addPersonne($personnes[$randPersonne]);
            } else if ($hasPersonne >= 5 && $hasPersonne < 7) {
                $animal->addPersonne($personnes[$randPersonne]);
                $randPersonne2 = $faker->numberBetween(0, count($personnes) - 1);
                while ($randPersonne2 === $randPersonne) {
                    $randPersonne2 = $faker->numberBetween(0, count($personnes) - 1);
                }
                $animal->addPersonne($personnes[$randPersonne2]);
            } else if ($hasPersonne >= 7) {
                $animal->addPersonne($personnes[$randPersonne]);
                $randPersonne2 = $faker->numberBetween(0, count($personnes) - 1);
                while ($randPersonne2 === $randPersonne) {
                    $randPersonne2 = $faker->numberBetween(0, count($personnes) - 1);
                }
                $animal->addPersonne($personnes[$randPersonne2]);
                $randPersonne3 = $faker->numberBetween(0, count($personnes) - 1);
                while ($randPersonne3 === $randPersonne2 || $randPersonne3 === $randPersonne) {
                    $randPersonne3 = $faker->numberBetween(0, count($personnes) - 1);
                }
                $animal->addPersonne($personnes[$randPersonne3]);
            }
        }

        $manager->flush();
    }
}
