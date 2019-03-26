<?php

namespace AppBundle\Repository;

/**
 * AddressRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AddressRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUserAddress($id){
        return $this->createQueryBuilder('address')
                    ->andWhere('address.id = :userId')
                    ->setParameter('userId', $id)
                    ->getQuery()
                    ->execute();
    }   

    public function deleteUserAddress($user_id, $address_id){
        return $this->createQueryBuilder('address')
                        ->delete()
                        ->where('address.id = :addressId')
                        ->andWhere('address.user_id = :userId')
                        ->setParameter('addressId', $address_id)
                        ->setParameter('userId', $user_id)
                        ->getQuery()
                        ->execute();

    }
}
