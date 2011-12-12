<?php

namespace Application\Cli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

class ApacheCreateCommand extends Console\Command\Command {
  
  protected function configure() {
    $this->setName('apache:create')
      ->setDescription('Create Apache Configuration based on custom template')
      ->addArgument('domain', InputArgument::REQUIRED, 'Application domain')
      ->addArgument('ip', InputArgument::REQUIRED, 'IP')
      ->addArgument('path', InputArgument::OPTIONAL, 'Where is deployed this web application ?', '/tmp')
      ->addOption('alias', 'a', InputOption::VALUE_OPTIONAL, 'Domain Alias')
      ->addOption('email', 'e', InputOption::VALUE_OPTIONAL, 'Administrator email')
      ->addOption('port', 'p',  InputOption::VALUE_OPTIONAL, 'PORT', 80)
      ->addOption('apachepath', 'ap',  InputOption::VALUE_OPTIONAL, 'Apache sites path', '/etc/apache2/sites-available')
      ->setHelp('HELP NOT AVAILABLE');
  }

  protected function execute(InputInterface $input, OutputInterface $output) { 
    $template = file_get_contents(APPLICATION_PATH.'/Templates/apache-conf.template');
    
    // standard arguments/options
    $domain  = $input->getArgument('domain');
    $ip   = $input->getArgument('ip');
    $port = $input->getOption('port');
    $path = $input->getArgument('path');
    $apache_path = $input->getOption('apachepath');
    
    // all this parts needs to be refactored, is very crappy!
    // options
    if ($alias = $input->getOption('alias')) {
      $alias = 'ServerAlias ' . $alias;
      $template = str_replace('#alias', "\t\t\n$alias", $template);
    }
    else {
      $template = str_replace('#alias', '', $template);
    }
    if ($email = $input->getOption('email')) {
      $email  = 'ServerAdmin ' . $email;
      $template = str_replace('#admin_email', "\t\t\n$email", $template);
    }
    else {
      $template = str_replace('#admin_email', '', $template);
    }
    $template = str_replace(array('#domain', '#ip', '#path', '#port'), array($domain, $ip, $path, $port), $template);
//    file_put_content()
    $output->writeln($template);
  }

}