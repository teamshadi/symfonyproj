<?php

namespace AppBundle\Command;

use \FfaPhp\Common\TreasuryFactory;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;


class CashManagementCommand extends Command
{
  protected function configure() {
    $this
        //the name of the command (the part after "bin/console")
       ->setname('treasury:cash-management')

       // the short description shown while running "php bin/console list"
       ->setDescription('cash managament report.')

      //the full command description shown when running the command with
     //the "--help" option

       ->setHelp('This command allows you to get the cash management report...')


     //configure an Option
       ->addOption(
         'format',
         null,
         InputOption::VALUE_OPTIONAL,
         'console|email    how do you need the format as email or console?',
         'console'
       )
       ->addOption(
         'dd',
         null,
         InputOption::VALUE_OPTIONAL,
         '2017-04-04   the date of the report',
         date("Y-m-d",strtotime('yesterday'))
       )
       ->addOption(
         'emailTo',
         null,
         InputOption::VALUE_OPTIONAL,
         's.akiki@ffaprivatebank.com;shadiakiki1986@gmail.com    If set, the email will be send to the specific emails',
         null
       )
       ->addOption(
         'nDaysBack',
         null,
         InputOption::VALUE_OPTIONAL,
         '30    number of days back to compute report',
         30
       )

     ;
  }

  protected function execute(InputInterface $input, OutputInterface $output){
    $format=$input->getOption('format');
    $emailTo=$input->getOption('emailTo');
    $dd=$input->getOption('dd');
    $nDaysBack = $input->getOption('nDaysBack');

    if(!is_null($emailTo)) {
      if(!!$emailTo) $emailTo = explode(";",$emailTo);
      $format="email";
    }

    if($format=="email" && !$emailTo) {
      # get from the ffa-jobs-emails server
      # https://github.com/minerva22/ffa-jobs-emails
      if(!getenv("FFA_JOBS_EMAILS_URL")) {
        throw new \Exception("format==email and emailTo not passed and env var FFA_JOBS_EMAILS_URL missing");
      }

      $url = getenv("FFA_JOBS_EMAILS_URL");
      $je = new \FfaJobsSettings\JobsEmails($url);
      $emailTo = $je->getEmails("Cash Management");
    }

    $dd = \DateTime::createFromFormat("!Y-m-d",$dd);

    assert_options(ASSERT_BAIL,true); // to fail on failed asserts instead of just issuing a warning
    $factory = new TreasuryFactory();
    $report = $factory->cashManagement($dd, NULL, $nDaysBack);

    switch($format) {
      case "console":
        echo($report->toConsole());
        break;
      case "email":
        $emailer = new \FfaPhp\Common\Emailer($report,$emailTo);
        $emailer->send();
        break;
      case "quiet":
        break;
      default:
        throw new \Exception("Invalid format");
    }

  }

}
