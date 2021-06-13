<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Word;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Word|null find($id, $lockMode = null, $lockVersion = null)
 * @method Word|null findOneBy(array $criteria, array $orderBy = null)
 * @method Word[]    findAll()
 * @method Word[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Word::class);
    }

    public function prepareResults(User $user, string $text): array
    {
        $text = explode(' ', trim(preg_replace('!\s+!', ' ', preg_replace('/[[:punct:]]/', ' ', $text))));

        $this->removeByUser($user);

        $words = [];
        for ($i = 0; $i < count($text); $i++) {
            $word = mb_convert_case(mb_strtolower($text[$i]), MB_CASE_TITLE);
            $words[$word] = (array_key_exists($word, $words) ? $words[$word] : 0) + 1;
        }

        $em = $this->getEntityManager();

        $i = 0;
        $batchSize = 20;
        foreach ($words as $w => $cnt) {
            $word = new Word();
            $word
                ->setUser($user)
                ->setWord($w)
                ->setCount($cnt)
            ;
            $this->getEntityManager()->persist($word);
            if (($i % $batchSize) === 0) {
                $em->flush();
            }
            $i++;
        }
        $em->flush();

        return $this->getResults($user);
    }

    public function removeByUser(User $user): int
    {
        return $this->getEntityManager()
            ->createQuery('DELETE FROM App\Entity\Word w WHERE w.user = :user')
            ->execute([ 'user' => $user ]);
    }

    public function getResults(User $user): array
    {
        return $this->findBy([ 'user' => $user ], [ 'count' => 'DESC', 'word' => 'ASC' ]);
    }
}
