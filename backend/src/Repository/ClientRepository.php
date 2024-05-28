<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Client>
 * @implements PasswordUpgraderInterface<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Client) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findLoginClient($email): array
    {
        return $this->createQueryBuilder('a')
            ->setParameter('emailKey', $email)
            ->select('client.id, client.client_first_name, client.client_last_name, client.client_email, client.client_password')
            ->from('App\Entity\Client', 'client')
            ->where('client.client_email= :emailKey')
            ->getQuery()
            ->getResult();
    }

    public function findAddresses($client_id):array
    {
        return $this->createQueryBuilder('a')
            ->setParameter('clientId', $client_id)
            ->select('address.id')
            ->from('App\Entity\Address', 'address')
            ->join('address.client', 'client')
            ->where('client.id= :clientId')
            ->getQuery()
            ->getResult();

    }

    public function updateClientFields(int $id,string $first_name, string $last_name, string $email, string $password)
    {
        return $this->createQueryBuilder('a')
            ->setParameters(["id"=>$id,"firstName"=>$first_name, "lastName"=>$last_name, "email"=>$email, 'password'=>$password])
            ->update('App\Entity\Client', 'client_table')
            ->set('client_table.first_name', ':firstName')
            ->set('client_table.last_name', ':lastName')
            ->set('client_table.email', ':email')
            ->set('client_table.password', ':password')
            ->where('client_table.id= :id')
            ->getQuery()
            ->execute();
    }
//    /**
//     * @return Client[] Returns an array of Client objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Client
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
