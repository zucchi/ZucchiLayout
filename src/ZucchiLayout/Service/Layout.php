<?php
namespace ZucchiLayout\Service;

use ZucchiDoctrine\Service\AbstractService;
use Zend\EventManager\EventInterface;
use Doctrine\ORM\Query\Expr;
use Zucchi\Debug\Debug;
use Zucchi\DateTime\DateTime;

class Layout extends AbstractService
{
    protected $entityName = '\ZucchiLayout\Entity\Layout';

    public function getCurrentLayout()
    {
        $now = new DateTime();
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select($this->alias)
           ->distinct()
           ->from($this->entityName, $this->alias)
           ->join(
               $this->alias . '.Schedule',
               's',
               Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->lte('s.displayFrom', "'".$now->format(DateTime::MYSQL) ."'" ),
                    $qb->expr()->gte('s.displayTill', "'".$now->format(DateTime::MYSQL) ."'" )
                )
           )
           ->where($this->alias .'.active = 1')
           ->orderBy('s.displayFrom', 'DESC')
        ;

        $result = $qb->getQuery()->getSingleResult();

        return $result;

    }
}