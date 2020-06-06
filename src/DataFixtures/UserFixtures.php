<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        /** User de base.
         * Peut afficher et lister.
         * Username : 'foo' / Mot de passe : 'bar'
         */
        $user = new User();
        $user->setUsername('foo');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'bar'
        ));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        /** Secrétariat.
         * Peut ajouter et modifier.
         * Username : 'sec' / Mot de passe : 'sec'
         */
        $user = new User();
        $user->setUsername('sec');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'sec'
        ));
        $user->setRoles(['ROLE_SECRETAIRE']);
        $manager->persist($user);

         /** Administrateur.
          * Peut supprimer.
          * Username : 'adm' / Mot de passe 'adm'
          */
        $user = new User();
        $user->setUsername('adm');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'adm'
        ));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $manager->flush();
    }
}
