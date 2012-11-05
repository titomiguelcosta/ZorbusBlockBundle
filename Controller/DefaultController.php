<?php

namespace Zorbus\BlockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function renderAction($block, $page)
    {
        $response = new Response('');
        if ($block)
        {
            $service = $this->get($block->getService());
            $response = $service->render($block, $page);
            $response->setTtl($block->getCacheTtl());
        }

        return $response;
    }
}
