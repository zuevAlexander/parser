<?php
namespace CoreBundle\Service;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\Feed;

class ParserFeedService
{
    
    /**
     * @var \CoreBundle\Handler\FeedHandler
     */
    private $feedHandler;

    /**
     * @var \CoreBundle\Handler\ChannelHandler
     */
    private $channelHandler;
    
    /**
     * @var \FeedIo\FeedIo
     */
    private $feedIo;

    /**
     * ParserFeedService constructor.
     * @param \CoreBundle\Handler\FeedHandler $feedHandler
     * @param \CoreBundle\Handler\ChannelHandler $channelHandler
     * @param \FeedIo\FeedIo $feedIo
     */
    public function __construct(\CoreBundle\Handler\FeedHandler $feedHandler, \CoreBundle\Handler\ChannelHandler $channelHandler, \FeedIo\FeedIo $feedIo)
    {
        $this->feedHandler = $feedHandler;
        $this->channelHandler = $channelHandler;
        $this->feedIo = $feedIo;
    }
    
    public function parseFeeds()
    {
        $modifiedSince = new \DateTime(date("Y-m-d H:i:s", strtotime("-10 day")));

        $channels = $this->channelHandler->getAllChannel();

        $countFeeds = 0;
        
        foreach ($channels as $channel) {

            $url = $channel->getUrl();

            $feedRss = $this->feedIo->readSince($url, $modifiedSince)->getFeed();
            
            foreach ($feedRss as $item) {

                if (!($this->feedHandler->getFeedByUrl($item->getLink()))) {
                    $feed = new Feed();
                    $feed->setTitle($item->getTitle());
                    $feed->setDescription($item->getDescription());
                    $feed->setDate($item->getLastModified());
                    $feed->setLink($item->getLink());
                    $feed->setChannel($channel);
                    $this->feedHandler->createFeed($feed);
                    $countFeeds++;
                }
            }
        }

        return $countFeeds;
    }

}