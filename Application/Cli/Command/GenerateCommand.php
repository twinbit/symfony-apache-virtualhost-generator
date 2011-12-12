<?php

namespace Application\Cli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\ArrayInput;

class GenerateCommand extends Console\Command\Command {
  protected function configure() {
    $this->setName('host:create')
      ->setDescription('Create Apache and Directories configurations with a single commmand')
      ->addArgument('domain', InputArgument::REQUIRED, 'Application domain')
      ->addArgument('ip', InputArgument::REQUIRED, 'Apache host ip')
      ->addArgument('path', InputArgument::OPTIONAL, 'Filesytem path', '/tmp')
      ->addOption('alias', 'a', InputOption::VALUE_OPTIONAL, 'Apache domain Alias')
      ->addOption('email', 'e', InputOption::VALUE_OPTIONAL, 'Apache administrator email')
      ->addOption('port', 'p',  InputOption::VALUE_OPTIONAL, 'Apache host port', 80)
      ->addOption('user', 'u', InputOption::VALUE_OPTIONAL, 'File system (apache) user/group', 'www-data')
      ->addOption('apachepath', 'ap',  InputOption::VALUE_OPTIONAL, 'Apache sites path', '/etc/apache2/sites-available');
  }
  
  protected function execute(InputInterface $input, OutputInterface $output) { 
    $domain       = $input->getArgument('domain');
    $ip           = $input->getArgument('ip');
    $path         = $input->getArgument('path');
    $alias        = $input->getOption('alias');
    $email        = $input->getOption('email');
    $port         = $input->getOption('port');
    $user         = $input->getOption('user');
    $apache_sites_path  = $input->getOption('apachepath');
    
    // Create directories
    $command = $this->getApplication()->find('directory:create');
    $arguments = array(
            'command'     => 'directory:create',
            'domain' => $domain,
            'path'        => $path,
            '-u'          => $user
    );
    $input = new ArrayInput($arguments);
    $command->run($input, $output);
    
    // Create Apache Configuration
    $command = $this->getApplication()->find('apache:create');
    
    // build using fs path + domain name (the same schema used for directory generation)
    $apache_fs_path = $path . DIRECTORY_SEPARATOR . $domain;
    $arguments = array(
            'command'     => 'apache:create',
            'domain'      => $domain,
            'ip'          => $ip,
            'path'        => $apache_fs_path,
            '-a'          => "www.$domain " . $alias,
            '-e'          => $email,
            '-p'          => $port
    );
    $input = new ArrayInput($arguments);
    $command->run($input, $output);
  }

}