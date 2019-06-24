<?php


namespace App\DataFixtures;


use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++) {
            $article = new Article();

            $article->setSnippet('Je suis le snippet de l\'article n°'.$i);
            $article->setDescription("Je suis la description de l'article n°".$i);
            $article->setCreationDate(new \DateTime());
            $article->setUser($this->getReference('user'.mt_rand(0, 9)));


            $manager->persist($article);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            UsersFixtures::class
        ];
    }

}