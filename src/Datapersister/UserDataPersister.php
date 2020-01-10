<?php
namespace App\Datapersister;

use App\Entity\User;
use App\DataPersister\UserDataPersister;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface
 {
    private $userPasswordEncoder;
    public function __construct(EntityManagerInterface $entitymanager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entitymanager;
    }
    public function supports($data): bool
    {
        return $data instanceof User;
        // TODO: Implement supports() method.
    }
   public function persist($data)
   {
    
    if ($data->getPassword()) {
        $data->setPassword(
            $this->userPasswordEncoder->encodePassword($data, $data->getPassword())
        );
        $data->eraseCredentials();
    }
      


       $this->entityManager->persist($data);
       $this->entityManager->flush();
   }

   public function remove($data)
   {
    $this->entityManager->remove($data);
    $this->entityManager->flush();
       // TODO: Implement remove() method.
   }
 }