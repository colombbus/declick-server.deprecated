<?php

/**
 * Description of AdminController
 */

namespace Declick\CoreBundle\Controller;

use Declick\CoreBundle\Controller\DeclickController;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends DeclickController {

    public function indexAction() {
        return $this->renderContent('DeclickCoreBundle:Admin:index.html.twig', 'profile', array());
    }

    public function usersAction(Request $request) {
        //TODO: check that user is admin
        $session = $request->getSession();
        $page = $session->get("admin_users_page", 1);
        $search = $session->get("admin_users_search", false);
        return $this->renderContent('DeclickCoreBundle:Admin:users.html.twig', 'profile', array('page' => $page, 'search' => $search, 'paginationRoute' => 'declick_admin_users_list'));
    }
    
    public function getUsersAction(Request $request) {
        //TODO: check that user is admin
        if (!$request->isXmlHttpRequest()) {
            $this->redirect($this->generateUrl('declick_admin_users'));
        }
        $pagination = $this->get('declick_core.pagination')->paginate($request, "admin_users", 'DeclickCoreBundle:User');
        return $this->render('DeclickCoreBundle:Admin:users_list.html.twig', array('users' => $pagination));
    }

    public function projectsAction(Request $request) {
        //TODO: check that user is admin
        $session = $request->getSession();
        $page = $session->get("admin_projects_page", 1);
        $search = $session->get("admin_projects_search", false);
        return $this->renderContent('DeclickCoreBundle:Admin:projects.html.twig', 'profile', array('page' => $page, 'search' => $search, 'paginationRoute' => 'declick_admin_projects_list'));
    }

    public function getProjectsAction(Request $request) {
        //TODO: check that user is admin
        if (!$request->isXmlHttpRequest()) {
            $this->redirect($this->generateUrl('declick_admin_projects'));
        }
        $pagination = $this->get('declick_core.pagination')->paginate($request, "admin_projects", 'DeclickCoreBundle:Project');
        return $this->render('DeclickCoreBundle:Admin:projects_list.html.twig', array('projects' => $pagination));
    }
    
    public function exercisesAction(Request $request) {
        //TODO: check that user is admin
        $session = $request->getSession();
        $page = $session->get("admin_exercises_page", 1);
        $search = $session->get("admin_exercises_search", false);
        $update = $session->get("update_required", false);
        if ($update) {
            $session->remove("update_required");
        }
        return $this->renderContent('DeclickCoreBundle:Admin:exercises.html.twig', 'profile', array('page' => $page, 'search' => $search, 'paginationRoute' => 'declick_admin_exercises_list', 'updateUserMenu'=>$update));
    }

    public function getExercisesAction(Request $request) {
        //TODO: check that user is admin
        if (!$request->isXmlHttpRequest()) {
            $this->redirect($this->generateUrl('declick_admin_exercises'));
        }
        $pagination = $this->get('declick_core.pagination')->paginate($request, "admin_exercises", 'DeclickCoreBundle:Project', array('exercise'=>true));
        return $this->render('DeclickCoreBundle:Admin:exercises_list.html.twig', array('exercises' => $pagination));
    }
    

}
