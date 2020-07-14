<?php


namespace CelebrityAgent\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="talking_points")
 */
class TalkingPoints extends AbstractEntity
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    private $id;

    // todo add wysiwyg
}