<?php

namespace Zorbus\BlockBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zorbus\BlockBundle\Entity\Block
 */
abstract class Block
{
    public function __toString()
    {
        return $this->getName();
    }

    public function toArray()
    {
        $array = array();
        $array['id'] = $this->getId();
        $array['name'] = $this->getName();
        $array['type'] = $this->getType();
        $array['enabled'] = $this->isEnabled();
        $array['theme'] = $this->getTheme();
        $array['lang'] = $this->getLang();

        foreach(json_decode($this->getParameters()) as $identifier => $value)
        {
            $array[$identifier] = $value;
        }

        return $array;
    }
}