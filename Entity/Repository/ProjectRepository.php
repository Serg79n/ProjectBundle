<?php

namespace Wifinder\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function retriveProject($request){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("ProjectBundle:Project", "c");

        return $query;
    }
    
    public function GetLastProjects($limit){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("ProjectBundle:Project", "c")
            ->where("c.is_active = true")
            ->orderBy("c.sort", "asc")
            ->addOrderBy("c.id", "asc")
            ->setMaxResults($limit)
            ->getQuery()->execute();

        return $query;
    }
    
    public function GetProjects(){
        $query = $this->_em->createQueryBuilder()
            ->select("c")
            ->from("ProjectBundle:Project", "c")
            ->where("c.is_active = true")
            ->orderBy("c.sort", "desc")
            ->getQuery()->execute();

        return $query;
    }
}
