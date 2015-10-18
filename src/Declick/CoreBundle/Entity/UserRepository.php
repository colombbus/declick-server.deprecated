<?php
namespace Declick\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository {
    
    public function getSearchQuery($string) {
        $qb = $this->createQueryBuilder('u')
                    ->where('u.username like :string')
                    ->orderBy('u.id')
                    ->setParameter('string','%'.$string.'%');
        return $qb->getQuery();
    }
    
    public function findUserByExternalId($externalId) {
        $query = $this->createQueryBuilder('u')
                ->where('u.externalId = :id')
                ->setParameter('id', $externalId);
        try {
            $user = $query->getQuery()->getSingleResult();
            return $user;
        } catch (NoResultException $e) {
            return false;
        }
    } 
}