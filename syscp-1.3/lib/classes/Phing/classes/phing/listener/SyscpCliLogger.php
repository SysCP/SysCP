<?php

require_once 'phing/listener/DefaultLogger.php';

final class SyscpCliLogger extends DefaultLogger
{
	
	public function __construct() 
	{
		parent::__construct();
	}
    function buildStarted(BuildEvent $event) 
    {
        $this->startTime = Phing::currentTimeMillis();
    }
    function targetStarted(BuildEvent $event) {}
    function buildFinished(BuildEvent $event) {}
    
    function messageLogged(BuildEvent $event) 
    {
    	// If the message output leel is set to debug or verbose
    	// we print out all informations we can gather
		if (    $this->msgOutputLevel == PROJECT_MSG_DEBUG 
		     || $this->msgOutputLevel == PROJECT_MSG_VERBOSE )
		{
            $msg = "";
            if ($event->getTask() !== null) 
            {
                $name = $event->getTask();
                $name = $name->getTaskName();
                $msg = str_pad("[$name] ", self::LEFT_COLUMN_SIZE, " ", STR_PAD_LEFT);
            }
            $msg .= $event->getMessage();
            $this->printMessage($msg, $event->getPriority());		     	
	    }
	    // if the event was an error, we need to display it
	    // to the screen
	    elseif( $event->getPriority() == PROJECT_MSG_ERR )
	    {
            $msg = "";
            if ($event->getTask() !== null) 
            {
                $name = $event->getTask();
                $name = $name->getTaskName();
                $msg = str_pad("[$name] ", self::LEFT_COLUMN_SIZE, " ", STR_PAD_LEFT);
            }
            $msg .= $event->getMessage();
            $this->printMessage($msg, $event->getPriority());		     	
	    	
	    }
	    // otherwise be nice and supress nearly all information, except 
	    // echo and input, unknown tasks will be printed as usual
	    elseif ( $event->getTask() !== null )
    	{
    		$task = $event->getTask();
    		$name = $task->getTaskName();
    		$message = '';
    		switch( $name )
    		{
    			case 'echo':
    				$message = $event->getMessage();
    				$this->printMessage( $message, $event->getPriority() );
    				break;
    			case 'includepath':
    			case 'phing':
    			case 'mkdir':
    			case 'if':
    			case 'php':
    			case 'delete':
    			case 'sql':
    			case 'phingcall':
    			case 'copy':
    			case 'chmod':
    			case 'property':
    			case 'Property':
    			case 'available':
    				break;
    			default: 
                	$msg = str_pad("[$name] ", self::LEFT_COLUMN_SIZE, " ", STR_PAD_LEFT);
            		$msg .= $event->getMessage();
            		$this->printMessage($msg, $event->getPriority());
    				break;
    		}
    	}
    }
}