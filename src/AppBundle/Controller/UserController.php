<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;

/**
 * User controller.
 *
 * @RouteResource("User")
*/
class UserController extends FOSRestController
{
    /**
     * Lists all User entities.
     *
     *
     * @ApiDoc(
     *     description="Retrieve list of users.",
     *     section = "USERS",
     *     statusCodes={
     *         400 = "Validation failed."
     *     },
     *     responseMap={
     *          200 = "CoreBundle\Entity\User",
     *         400 = {
     *             "class"="CommonBundle\Model\ValidationErrors",
     *             "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *         }
     *     },
     *  input={
     *       "class" = "CoreBundle\Form\UserType",
     *       "name" = ""
     *  }
     *  )
     *
     *
     */
    public function getAction(Request $request)
    {

        $users = $this->get('core.handler.user')->getUsers();

        $serializer = $this->get('jms_serializer');
        $jsonContent = $serializer->serialize($users, 'json');
        $view = $this->view($jsonContent, 200);

        return $this->handleView($view);
    }

    /**
     * Lists all Channel entities.
     *
     *
     * @ApiDoc(
     *     description="Input new user.",
     *     section = "USERS",
     *     statusCodes={
     *         400 = "Validation failed."
     *     },
     *
     *    {"name"="Id", "url"="sting", "format"="sting", "user"="integer"},
     *
     *     responseMap={
     *          200 = "CoreBundle\Entity\User",
     *         400 = {
     *             "class"="CommonBundle\Model\ValidationErrors",
     *             "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *         }
     *     },
     *     requirements = {
     *                {"name"="name", "url"="url", "format"="string", "user" = "string"}
     *            },
     *  input={
     *       "class" = "CoreBundle\Form\UserType",
     *       "name" = ""
     *  }
     *
     *  )
     *
     */
    public function postAction(Request $request)
    {

        $user = new User();
        $form =  $this->createForm('CoreBundle\Form\UserType', $user);
        $form->submit($request->request->all());
        
        $this->get('core.handler.user')->createUser($user);

        $serializer = $this->get('jms_serializer');
        $jsonContent = $serializer->serialize($user, 'json');

        $view = $this->view($jsonContent, 200);

        return $this->handleView($view);
    }
    
}
