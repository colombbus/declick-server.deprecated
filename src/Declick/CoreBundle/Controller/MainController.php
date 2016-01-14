<?php

namespace Declick\CoreBundle\Controller;

use Declick\CoreBundle\Controller\DeclickController;
use Declick\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Exception;

class MainController extends DeclickController {

    public function indexAction(Request $request) {
        return $this->createAction($request);
    }
    
    public function infoAction() {
        return $this->renderContent('DeclickCoreBundle:Main:info.html.twig', 'info');
    }

    public function createAction(Request $request) {
        if ($this->getRequest()->isXmlHttpRequest()) {
            // should never occur
            $jsonResponse = new JsonResponse();
            return $jsonResponse->setData(array('error' => 'no-access'));
        } else {
            // Check access
            $logged = false;
            $token = $request->query->get("sToken", false);
            if ($token !== false) {
                $parser = $this->get("declick_core.token_parser");
                try {
                    $params = $parser->decodeToken($token);
                    $login = $params['sLogin'];
                    //TODO: find a better way to detect unauthentified users
                    if (substr($login, 0, 4) !== "tmp-") {
                        $dispatcher = $this->get("event_dispatcher");
                        // user is identified
                        $id = $params['idUser'];
                        // get manager
                        $repository = $this->getDoctrine()->getRepository('DeclickCoreBundle:User');
                        $user = $repository->findUserByExternalId($id);
                        if ($user === false) {
                            // user does not already have an account: create it
                            $user = new User();
                            $user->setUsername($login);
                            $user->setPassword("auto");
                            $user->setExternalId($id);
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($user);
                            $em->flush();
                            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, new Response()));

                        } 
                        // user has an account: log in
                        
                        $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
                        $this->get('security.token_storage')->setToken($token);
                        $event = new InteractiveLoginEvent($request, $token);
                        $dispatcher->dispatch("security.interactive_login", $event);
                        //$this->get('fos_user.security.login_manager')->logInUser('main', $user);
                        $logged = true;
                        
                        // check if there is any request to copy projects
                        $session = $this->get('request')->getSession();
                        if ($session->has('copy-project')) {
                            // projects have to be copied
                            $projectId = $session->get('copy-project');
                            $projectManager = $this->get('declick_core.project_manager');
                            $userProjectId = $session->get('projectid');
                            if (!$userProjectId) {
                                throw (new Exception("no home id"));
                            }
                            $userProject = $projectManager->loadProject($userProjectId);
                            // Check if project exists and is public
                            $project = $projectManager->loadProject($projectId);
                            if (!$project || !$project->getPublished()) {
                                throw (new Exception("wrong id"));
                            }
                            // Import project files
                            $projectManager->importProjectFiles($project, $userProject);
                        }
                    }
                } catch (Exception $ex) {
                    $jsonResponse = new JsonResponse();
                    $jsonResponse->setData(array('error' => $ex->getMessage()));
                    return $jsonResponse;
                }
            }
            if (!$logged) {
                //loggout user
                $this->get('security.token_storage')->setToken(null);
                $this->get('request')->getSession()->invalidate();
            }
            // direct access
            return $this->renderContent(false, 'create');
        }
    }
    
    //TODO: remove this quick and dirty thing!
    public function importAction(Request $request, $token, $projectId) {
        $parser = $this->get("declick_core.token_parser");
        try {
            $params = $parser->decodeToken($token);
            $login = $params['sLogin'];
            //TODO: find a better way to detect unauthentified users
            if (substr($login, 0, 4) !== "tmp-") {
                $dispatcher = $this->get("event_dispatcher");
                // user is identified
                $id = $params['idUser'];
                // get manager
                $repository = $this->getDoctrine()->getRepository('DeclickCoreBundle:User');
                $user = $repository->findUserByExternalId($id);
                if ($user === false) {
                    // user does not already have an account: create it
                    $user = new User();
                    $user->setUsername($login);
                    $user->setPassword("auto");
                    $user->setExternalId($id);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, new Response()));
                } 
                // user has an account: log in
                        
                $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $event = new InteractiveLoginEvent($request, $token);
                $dispatcher->dispatch("security.interactive_login", $event);
                //$this->get('fos_user.security.login_manager')->logInUser('main', $user);
                $logged = true;
                        
                // check if there is any request to copy projects
                $session = $this->get('request')->getSession();
                $session->set('copy-project', $projectId);
            }
            $response = new Response();
            $response->setContent("<html><body>imported: ".$projectId."</body></html>");
            $response->setStatusCode(Response::HTTP_OK);
            $response->headers->set('Content-Type', 'text/html');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        }
        catch (Exception $ex) {
        }
    }
    
    
}
