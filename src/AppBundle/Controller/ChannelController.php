<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\Entity\Channel;
use CoreBundle\Entity\Feed;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Channel controller.
 *
 * @RouteResource("Channel")
*/
class ChannelController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="CHANNELS",
     *  description="Get channel",
     *  input={
     *       "class" = "CoreBundle\Form\ChannelType",
     *       "name" = ""
     *  },
     *  statusCodes={
     *      200 = "Ok",
     *      400 = "Bad format",
     *      403 = "Forbidden",
     *      404 = "Channel not found",
     *  }
     *)
     *
     * @param Request  $request
     *
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $channels = $this->get('core.handler.channel')->getChannelByToken();

//        $serializer = $this->get('jms_serializer');
//        $jsonContent = $serializer->serialize($channels, 'json');
//        $view = $this->view($jsonContent, 200);

        return $channels;
    }

    /**
     * Lists all Channel entities.
     *
     *
     * @ApiDoc(
     *     description="Input new channel.",
     *     section = "CHANNELS",
     *     statusCodes={
     *         400 = "Validation failed."
     *     },
     *
     *    {"name"="Id", "url"="sting", "format"="sting", "user"="integer"},
     *
     *     responseMap={
     *          200 = "CoreBundle\Entity\Channel",
     *         400 = {
     *             "class"="CommonBundle\Model\ValidationErrors",
     *             "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *         }
     *     },
     *     requirements = {
     *                {"name"="name", "url"="url", "format"="string", "user" = "string"}
     *            },
     *  input={
     *       "class" = "CoreBundle\Form\ChannelType",
     *       "name" = ""
     *  }
     *
     *  )
     *
     */
    public function postAction(Request $request)
    {

        $channel = new Channel();
        $form =  $this->createForm('CoreBundle\Form\ChannelType', $channel);
        $form->submit($request->request->all());

        try {
            $this->get('core.handler.channel')->createChannel($channel);
            $this->get('core.service.parser')->parseFeeds();
        }
        catch(\Exception $e) {

            $serializer = $this->get('jms_serializer');
            $jsonContent = $serializer->serialize($e->getMessage(), 'json');

            $view = $this->view($jsonContent, 401);

            return $this->handleView($view);
        }

        $serializer = $this->get('jms_serializer');
        $jsonContent = $serializer->serialize($channel, 'json');

        $view = $this->view($jsonContent, 200);

        return $this->handleView($view);
    }

    /**
     * Lists all Channel entities.
     *
     *
     * @ApiDoc(
     *     description="Delete channel.",
     *     section = "CHANNELS",
     *     statusCodes={
     *         400 = "Validation failed."
     *     },
     *
     *    {"name"="Id", "url"="sting", "format"="sting", "user"="integer"},
     *
     *     responseMap={
     *          200 = "CoreBundle\Entity\Channel",
     *         400 = {
     *             "class"="CommonBundle\Model\ValidationErrors",
     *             "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *         }
     *     },
     *     requirements = {
     *                {"name"="name", "url"="url", "format"="string", "user" = "string"}
     *            },
     *  input={
     *       "class" = "CoreBundle\Form\ChannelType",
     *       "name" = ""
     *  }
     *
     *  )
     *
     */
    public function deleteAction(Request $request, Channel $channel)
    {
        $form =  $this->createForm('CoreBundle\Form\ChannelType', $channel);
        $form->submit($request->request->all());

        $response = $this->get('core.handler.channel')->deleteChannel($channel);

        return $response;
    }
}
