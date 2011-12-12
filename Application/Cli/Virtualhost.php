<?php

namespace Application\Cli;

use Symfony\Component\Console\Application,
	  Application\Cli\Command;
    
/**
 * Calculator application.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class Virtualhost extends Application {
	/**
     * Virtualhost constructor.
     */
    public function __construct() {
    	parent::__construct('Welcome to Apache Virtualhost generator', '1.0');
    	
    	$this->addCommands(array(
			  new Command\DirectoryCommand(),
			  new Command\ApacheConfCommand()
		  ));
		
    }
}