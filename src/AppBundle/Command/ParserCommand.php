<?php
namespace AppBundle\Command;

use FeedIo\Adapter\Guzzle\Response;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use CoreBundle\Entity\Feed;

class ParserCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('app:parser-rss')
            ->setDescription('Run the Rss parser.')
            ->setHelp("This command allows you to run the Rss parser");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln([
            'Parser started',
            '============',
            '',
        ]);

        $countFeeds = $this->getContainer()->get('core.service.parser')->parseFeeds();
        $output->writeln([
            "$countFeeds feeds were added to the DB",
            '============',
            '',
        ]);
        
        $output->writeln([
            'Parser finished',
            '============',
            '',
        ]);
    }
}