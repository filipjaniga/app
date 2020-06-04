<?php
/**
 * Tag fixture.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Tags that were already added.
     *
     * @var array
     */
    private $alreadyAddedTags = [];

    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(30, 'tags', function ($i) {
            $tag = new Tag();
            $newWord = $this->faker->word;

            while (in_array($newWord, $this->alreadyAddedTags)) {
                $newWord = $this->faker->word;
            }

            $this->alreadyAddedTags[] = $newWord;
            $tag->setTitle($newWord);

            return $tag;
        });

        $manager->flush();
    }
}
