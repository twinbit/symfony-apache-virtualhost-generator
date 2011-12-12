<?php

namespace Application\Cli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;


class DirectoryCreateCommand extends Console\Command\Command {
  
  protected function configure() {
    $this->setName('directory:create')
      ->setDescription('Create Directory Structure')
      ->setHelp('HELP NOT AVAILABLE')
      ->addArgument('domain', InputArgument::REQUIRED, 'Application name')
      ->addArgument('path', InputArgument::OPTIONAL, 'Where do you want deploy this web application ?', '/tmp')
      ->addOption('user', 'u', InputOption::VALUE_OPTIONAL, 'Specify the Apache user-group (default is "www-data")', 'www-data')
      ->setHelp(sprintf(
              '%Create the directory structure, if you not use pass any arguments will be defaulted to /var/www %s',
              PHP_EOL,
              PHP_EOL
      ));
  }

  protected function execute(InputInterface $input, OutputInterface $output) { 
    $app  = $input->getArgument('domain');
    $path = $input->getArgument('path');
    $user = $input->getOption('user');
    
    /**
     *  Structure:
          application/web
          application/etc
          application/etc/logrotate.d
          application/logs
    */
    $messages = array();
    try {
      $dirs = array('web', 'etc/logrotate.d', 'logs');
      foreach ($dirs as $dir) {
        $base = $dest[$dir] = $path . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . $dir;
        @mkdir($base, 0755, true);
        @chown($base, $user);
      }
      // sample welcome file in web root
      @file_put_contents($dest['web'].'/index.php', 'Welcome to '.$app."\n");
    }
    catch (Exception $e) {
      $output->writeln($e->getMessage);
    }
    
    // build message
    $messages = $this->_checkExists($dest, $input);
    $output->writeln($messages);
  }
  
  private function _checkExists($dirs, $input) {
    foreach ($dirs as $dir) {
      if (!file_exists($dir)) {
        $messages[] = "<error> Not created: $dir </error>";
      }
      else {
        $messages[] = "<info> Created: $dir </info>";
      }
      // check owner
      $owner = posix_getpwuid(fileowner($dir));
      $user = $input->getOption('user');
      if ($owner['name'] != $user) {
        $messages[] = "<comment> Chown not possible for user {$user} on: '$dir' <error> actually is owned by {$owner['name']} </error> </comment>";
      }
    }
    return $messages;
  }
  
}