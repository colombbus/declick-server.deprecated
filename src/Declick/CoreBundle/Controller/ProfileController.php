<?php

/*
 * Copyright (C) 2014 Régis
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Description of ProfileController
 *
 * @author Régis
 */

namespace Declick\CoreBundle\Controller;


use Declick\CoreBundle\Controller\DeclickController;
use Symfony\Component\HttpFoundation\Response;
use Declick\CoreBundle\Entity\Group;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileController extends DeclickController
{

    /**
     * To get the user main page
     * @return type
     */
    public function profileAction($user_id) {
        $user = null;
        $edition = false;
        if ($user_id === false) {
            // get user id from logged user
            if (!$this->isUserLogged()) {
                return $this->redirect($this->generateUrl( 'declick_core_homepage'));
            } else {
                $user = $this->getUser();
                $edition = true;
            }
        } else {
                $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('DeclickCoreBundle:User')
                ->findOneById($user_id);
            if (!$user) {
                return $this->redirect($this->generateUrl( 'declick_core_homepage'));
            }
            if ($user === $this->getUser() || $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ) {
                $edition = true;
            }
        }
        
        //$response = parent::profileAction();
        //$user = parent::showAction();
        return $this->renderContent('DeclickCoreBundle:Profile:show.html.twig', 'profile', array('user'=>$user, 'edition'=>$edition));
    }

    public function logoutAction() {
        $request = $this->getRequest();
        // if AJAX login
        if ( $request->isXmlHttpRequest() ) {
            $jsonResponse = new JsonResponse();
            $content = $this->renderView('DeclickCoreBundle:User:anonymous.html.twig');            
            return $jsonResponse->setData(array('success' => true, 'content'=>$content));
        } else {
            // redirect the user to where they were before the login process begun.
            return $this->redirect($request->headers->get('referer'));
        }
    }
    
    public function menuAction() {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            // should never occur
            return $this->redirect($this->generateUrl( 'declick_core_homepage' ));
        } else {
            // direct access
            return $this->render('DeclickCoreBundle:User:menu.html.twig', array('current_project'=>$this->getProject()));
        }
    }
    
    public function registerAction() {
        if ($this->isUserLogged()) {
            // If user is logged: redirect to main page
            return $this->redirect($this->generateUrl( 'declick_core_homepage' ));
        }
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            if ($request->isMethod('POST')) {
                $response = $this->forward("FOSUserBundle:Registration:register");
                if ($response instanceof RedirectResponse) {
                    // redirect
                    return $this->redirect($response->getTargetUrl());
                } else {
                    $jsonResponse = new JsonResponse();
                    return $jsonResponse->setData(array('content'=>$response->getContent()));
                }
            } else {
                return $this->forward("FOSUserBundle:Registration:register");
            }
        } else {
            return $this->renderContent("DeclickCoreBundle:Profile:init.html.twig", 'profile', array('route'=>'declick_user_register'));
        }
    }
    
    public function registrationConfirmedAction() {
        if (!$this->isUserLogged()) {
            // If user is logged: redirect to main page
            return $this->redirect($this->generateUrl( 'declick_core_homepage' ));
        }
        return $this->renderContent("DeclickCoreBundle:Profile:registration.confirmed.html.twig", 'profile', array('user'=>$this->getUser()));
    }

    public function changePasswordAction() {
        if (!$this->isUserLogged()) {
            // If user is not logged: redirect to main page
            return $this->redirect($this->generateUrl( 'declick_core_homepage' ));
        }
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            if ($request->isMethod('POST')) {
                $response = $this->forward("FOSUserBundle:ChangePassword:changePassword");
                if ($response instanceof RedirectResponse) {
                    // redirect
                    return $this->redirect($response->getTargetUrl());
                } else {
                    $jsonResponse = new JsonResponse();
                    return $jsonResponse->setData(array('content'=>$response->getContent()));
                }
            } else {
                return $this->forward("FOSUserBundle:ChangePassword:changePassword");
            }
        } else {
            return $this->renderContent("DeclickCoreBundle:Profile:init.html.twig", 'profile', array('route'=>'declick_user_change_password'));
        }
    }
    
    public function forgotPasswordAction(){
        if ($this->isUserLogged()) {
            // if user is logged: redirect to main page
            return $this->redirect($this->generateUrl( 'declick_core_homepage' ));
        }
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            if ($request->isMethod('POST')) {
                $response = $this->forward("FOSUserBundle:Resetting:sendEmail");
                if ($response instanceof RedirectResponse) {
                    // redirect
                    return $this->redirect($response->getTargetUrl());
                } else {
                    $jsonResponse = new JsonResponse();
                    return $jsonResponse->setData(array('content'=>$response->getContent()));
                }
            } else {
                return $this->forward("FOSUserBundle:Resetting:request");
            }
        } else {
            return $this->renderContent("DeclickCoreBundle:Profile:init.html.twig", 'profile', array('route'=>'declick_user_forgotten_password'));
        }
    }

    public function resetPasswordAction($token){
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            if ($request->isMethod('POST')) {
                $response = $this->forward("FOSUserBundle:Resetting:reset", array('token'=>$token));
                if ($response instanceof RedirectResponse) {
                    // redirect
                    return $this->redirect($response->getTargetUrl());
                } else {
                    $jsonResponse = new JsonResponse();
                    return $jsonResponse->setData(array('content'=>$response->getContent()));
                }
            } else {
                return $this->forward("FOSUserBundle:Resetting:reset", array('token'=>$token));
            }
        } else {
            return $this->renderContent("DeclickCoreBundle:Profile:init.html.twig", 'profile', array('route'=>'fos_user_resetting_reset', 'parameters'=>array('token'=>$token)));
        }
    }
    
    public function resetPasswordConfirmedAction() {
        if (!$this->isUserLogged()) {
            // If user is logged: redirect to main page
            return $this->redirect($this->generateUrl( 'declick_core_homepage' ));
        }
        return $this->renderContent("DeclickCoreBundle:Profile:reset.confirmed.html.twig", 'profile', array('user'=>$this->getUser(), 'edition'=>true));
    }
    
}
