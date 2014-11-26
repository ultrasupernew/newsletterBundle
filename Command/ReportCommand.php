<?php

namespace Usn\NewsletterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReportCommand extends ContainerAwareCommand
{

  protected function configure()
  {
    $this
        ->setName('report:subscriber')
        ->setDescription('Get number of subscribers')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {

    $em = $this->getContainer()->get('doctrine')->getEntityManager('default');

    $newsletter = $em
      ->getRepository('UsnNewsletterBundle:Newsletter')
      ->findAll();

    $default_timezone = date_default_timezone_get();
    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y年n月j日", time());
    date_default_timezone_set($default_timezone);

    $message = \Swift_Message::newInstance()
        ->setSubject('fan.redbull.jp メルマガ登録者数 - ' . $today)
        ->setFrom('no-reply@fan.redbull.jp')
        ->setTo('rb_blue@ultrasupernew.com')
        ->setBody($today . "\n\n" . '登録者数：' . count($newsletter));
    $this->getContainer()->get('mailer')->send($message);

    //$output->writeln('登録者数：' . count($newsletter));
  }
}
