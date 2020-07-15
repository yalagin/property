<?php


namespace CelebrityAgent\Entity\Interfaeces;


use CelebrityAgent\Entity\User;

// get the  user that owned that entity
interface HasOwnerInterface
{
    public function getOwner(): ?User;
}