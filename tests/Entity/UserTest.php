<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{

    public function testCreateUser()
    {
        // Création d'un utilisateur
        $user = new User();
        $user->setEmail('test@example.com');    // Ajout de l'email
        $user->setPassword('password123');      // Ajout du mot de passe
        $user->setNom('John');                  // Ajout du nom
        $user->setPrenom('Doe');                // Ajout du prénom

        // Vérification des valeurs données
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('password123', $user->getPassword());
        $this->assertEquals('John', $user->getNom());
        $this->assertEquals('Doe', $user->getPrenom());
    }

    public function testCommentaire()
    {
        // Création d'un utilisateur
        $user = new User();
        $user->setEmail('test@example.com');    // ajout email
        // Vérifie si l'email de l'utilisateur est le même qu'on lui ai donné
        $this->assertEquals('test@example.com', $user->getEmail()); 

        $user->setRoles(['ROLE_USER']);         // ajout role
        // Vérifie si le role de l'utilisateur est le même qu'on lui ai donné
        $this->assertEquals(['ROLE_USER'], $user->getRoles());

        // Création d'un commentaire
        $commentaire = new Commentaire();
        $commentaire->setTexte("Commentaire de test");  // ajout texte
        $commentaire->setUsers($user);                  // ajout d'utilisateur au commentaire

        // Ajout du commentaire à l'utilisateur
        $user->addCom($commentaire);

        // Récupère le commentaire de l'utilisateur
        $commentaires = $user->getComs();

        // Vérifie si l'utilisateur a bien un commentaire
        $this->assertCount(1, $commentaires);
        // Vérifie si le seul commentaire de l'utilisateur est bien le même qu'on lui ai donné 
        $this->assertSame($commentaire, $commentaires[0]);
    }
}
