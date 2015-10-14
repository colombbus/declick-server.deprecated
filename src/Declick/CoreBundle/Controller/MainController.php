<?php

namespace Declick\CoreBundle\Controller;

use Declick\CoreBundle\Controller\DeclickController;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends DeclickController {

    public function indexAction() {
        return $this->createAction();
    }
    
    public function infoAction() {
        return $this->renderContent('DeclickCoreBundle:Main:info.html.twig', 'info');
    }

    public function createAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            // should never occur
            $jsonResponse = new JsonResponse();
            return $jsonResponse->setData(array('error' => 'no-access'));
        } else {
            // direct access
            return $this->renderContent(false, 'create');
        }
    }
    
    
}
