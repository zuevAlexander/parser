<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\Entity\User;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


/**
 * User controller.
 *
 * @RouteResource("Login")
*/
class DefaultController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Login",
     *  description="Login user",
     *  input={
     *       "class" = "CoreBundle\Form\UserType",
     *       "name" = ""
     *  },
     *  statusCodes={
     *      200 = "Ok",
     *      400 = "Bad format",
     *      403 = "Access denied"
     *  }
     *)
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function postAction(Request $request)
    {

        $user = new User();
        $form =  $this->createForm('CoreBundle\Form\UserType', $user);
        $form->submit($request->request->all());

        $token = $this->get('core.handler.user')->getUser($user);

        $serializer = $this->get('jms_serializer');
        $jsonContent = $serializer->serialize($token, 'json');

        $view = $this->view($jsonContent, 200);

        return $this->handleView($view);
    }

}
