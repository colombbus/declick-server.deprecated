<?php

namespace Declick\CoreBundle\Manager;

use Declick\CoreBundle\Manager\BaseManager;
use Doctrine\ORM\EntityManager;

class LogManager extends BaseManager {
    
    protected $em;    
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    
    public function getRepository() {
        return $this->em->getRepository('DeclickCoreBundle:Log');
    }
    
    public function removeProjectEntries($project) {
        $entries = $this->getRepository()->getProjectLogEntries($project);
        foreach ($entries as $entry) {
            $this->em->remove($entry);
        }
        $this->em->flush();
    }
}
