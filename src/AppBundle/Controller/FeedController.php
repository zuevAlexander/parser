<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\Entity\Feed;
use CoreBundle\Entity\Channel;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Feed controller.
 *
 * @RouteResource("Feed")
*/

class FeedController extends FOSRestController
{
    /**
     * Lists all Channel entities.
     *
     *
     * @ApiDoc(
     *     description="Retrieve list of feeds.",
     *     section = "FEEDS",
     *     statusCodes={
     *         400 = "Validation failed."
     *     },
     *     responseMap={
     *          200 = "CoreBundle\Entity\Feed",
     *         400 = {
     *             "class"="CommonBundle\Model\ValidationErrors",
     *             "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *         }
     *     }
     *  )
     *
     */
    public function cgetAction(Channel $channel)
    {
        $feeds = $channel->getFeeds();

        $serializer = $this->get('jms_serializer');
        $jsonContent = $serializer->serialize($feeds, 'json');
        $view = $this->view($jsonContent, 200);

//        var_dump($this->handleView($view));
        return $feeds;
    }

}
