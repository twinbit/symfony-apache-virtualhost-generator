<?php

namespace Application\Cli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

class ApacheConfCommand extends Console\Command\Command {
  protected function configure() {
    $this->setName('apache:create')
      ->setDescription('Create Apache Configuration based on custom template')
      ->setHelp('HELP NOT AVAILABLE');
  }

  protected function execute(InputInterface $input, OutputInterface $output) { 
    return 10;
  }

}