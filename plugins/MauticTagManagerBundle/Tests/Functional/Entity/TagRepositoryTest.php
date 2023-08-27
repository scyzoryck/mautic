<?php

namespace MauticPlugin\MauticTagManagerBundle\Tests\Functional\Entity;

use Mautic\CoreBundle\Test\MauticMysqlTestCase;
use Mautic\LeadBundle\Entity\Tag;
use MauticPlugin\MauticTagManagerBundle\Entity\TagRepository;
use MauticPlugin\MauticTagManagerBundle\Model\TagModel;
use PHPUnit\Framework\Assert;

class TagRepositoryTest extends MauticMysqlTestCase
{
    private TagRepository $tagRepository;
    private int $lastId;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->tagRepository = self::getContainer()->get('mautic.tagmanager.repository.tag');

        $tags = [
            'tag1',
            'tag2',
            'tag3',
            'tag4',
        ];

        foreach ($tags as $tagName) {
            $tag = new Tag();
            $tag->setTag($tagName);
            $this->tagRepository->saveEntity($tag);
            $this->lastId = $tag->getId();
        }
    }

    public function testCountOccurencesReturnsCorrectQuantityOfTags(): void
    {
        $count = $this->tagRepository->countOccurrences('tag2');
        Assert::assertSame(1, $count);
    }

    public function testCountOccurencesReturnsCorrectQuantityOfMultipleTags(): void
    {
        $count = $this->tagRepository->countByLeads([1, $this->lastId]);
        Assert::assertSame([1 => 0, $this->lastId => 1], $count);
    }

}
