<?php 

require __DIR__.'/vendor/autoload.php';
use Symfony\Component\Console\Application;


use AppBundle\Command\CashManagementCommand;
use AppBundle\Command\PnLCommand;
use AppBundle\Command\TreasuryFfa017Command;
use AppBundle\Command\SecuritiesCountryCommand;

// to run the command php ffa.php treasury:cash-management --format=email
// php ffa.php treasury:cash-management



$commandCashManagement = new CashManagementCommand();
$commandPnL = new PnLCommand();
$commandtreasuryffai007 = new TreasuryFfa017Command();
$commandSecuritiesCountry = new SecuritiesCountryCommand();

$application = new Application();



$application->add($commandCashManagement);
$application->add($commandPnL);
$application->add($commandtreasuryffai007);
$application->add($commandSecuritiesCountry);


$application->setDefaultCommand($commandCashManagement->getName());
$application->setDefaultCommand($commandPnL->getName());
$application->setDefaultCommand($commandtreasuryffai007->getName());
$application->setDefaultCommand($commandSecuritiesCountry->getName());


$application->run();



?>