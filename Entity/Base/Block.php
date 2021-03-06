<?php

namespace Zorbus\BlockBundle\Entity\Base;

use Zorbus\BlockBundle\Model\BlockInterface;

/**
 * Zorbus\BlockBundle\Entity\Block
 */
abstract class Block implements BlockInterface
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
        $array['service'] = $this->getService();
        $array['enabled'] = $this->getEnabled();
        $array['theme'] = $this->getTheme();
        $array['lang'] = $this->getLang();
        $array['cache_ttl'] = $this->getCacheTtl();

        foreach (json_decode($this->getParameters()) as $identifier => $value) {
            $array[$identifier] = $value;
        }

        return $array;
    }
}
