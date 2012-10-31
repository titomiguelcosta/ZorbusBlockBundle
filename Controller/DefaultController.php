<?php

namespace Zorbus\BlockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function renderAction($id)
    {
        $block = $this->getDoctrine()->getRepository('ZorbusBlockBundle:Block')->findOneBy(array('id' => $id));

        $response = new Response('');
        if ($block)
        {
            $service = $this->get($block->getService());
            $response = $service->render($block);
            $response->setTtl($block->getCacheTtl());
        }

        return $response;
    }
}
