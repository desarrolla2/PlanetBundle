<?php

namespace Desarrolla2\Bundle\PlanetBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Desarrolla2\Bundle\BlogBundle\Model\PostStatus;
use Desarrolla2\Bundle\BlogBundle\Entity\Post;

/**
 * UnrelatedRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UnrelatedRepository extends EntityRepository
{

    public function getPublished()
    {
        $em    = $this->getEntityManager();
        $query = $em->createQuery(
            ' SELECT COUNT(u) AS n,  ' .
            ' u AS un ' .
            ' FROM PlanetBundle:Unrelated u  ' .
            ' GROUP BY u.post ' .
            ' HAVING  n > 1 ' .
            ' ORDER BY n DESC '
        );

        return $query->getResult();
    }

    /**
     * @param Post $post
     */
    public function clean(Post $post)
    {
        $em    = $this->getEntityManager();
        $query = $em->createQuery(
            ' DELETE PlanetBundle:Unrelated u  ' .
            ' WHERE u.post = :post '
        )->setParameter('post', $post);

        $query->execute();
    }
}
