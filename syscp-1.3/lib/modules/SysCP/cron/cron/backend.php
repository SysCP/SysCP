<?php

// +----------------------------------------------------------------
// | ACTUAL CRONSCRIPT WORK
// +----------------------------------------------------------------
// we init some vars we really need

$idList = array();

// list of processed task id's

$classes = array();

// list of instanciated classes
// We start to fetch all the tasks which are sheduled

$query = 'SELECT * FROM `%s` ORDER BY `id` ASC';
$query = sprintf($query, TABLE_PANEL_TASKS);
$result = $this->DatabaseHandler->query($query);

while(false !== ($row = $this->DatabaseHandler->fetchArray($result)))
{
    // we need to reparse the params first

    $row['params'] = unserialize(urldecode($row['params']));

    // and put the values into short vars, to make the following more readable

    $file = $row['file'];
    $class = $row['class'];
    $method = $row['method'];
    $params = $row['params'];

    // now we need to check if the requested class is already instanciated

    if(!isset($classes[$class])
       && file_exists(SYSCP_PATH_BASE.$file))
    {
        $this->LogHandler->info(Syscp_Handler_Log_Interface::FACILITY_SYSTEM, sprintf('Instanciating class %s', $class));

        // it is not, lets load it and instanciate

        require_once SYSCP_PATH_BASE.$file;

        // instanciate

        $classes[$class] = new $class();

        // init the hook

        $classes[$class]->initialize($this->DatabaseHandler, $this->ConfigHandler, $this->HookHandler, $this->LogHandler, $this->TemplateHandler);
    }

    // lets check if the class we need to have exists now

    if(isset($classes[$class]))
    {
        // the class exists, lets call the requested method

        $this->LogHandler->info(Syscp_Handler_Log_Interface::FACILITY_SYSTEM, sprintf('Executing %s::%s', $class, $method));
        $classes[$class]->$method($params);

        // and remove the scheduled function call

        $this->LogHandler->info(Syscp_Handler_Log_Interface::FACILITY_SYSTEM, sprintf('Removing %s::%s from schedule list', $class, $method));
        $query = 'DELETE FROM `%s` WHERE `id`=\'%s\'';
        $query = sprintf($query, TABLE_PANEL_TASKS, $row['id']);
        $this->DatabaseHandler->query($query);
    }
    else
    {
        $error = 'The scheduled function %s::%s in file %s cannot be found!';
        $error = sprintf($error, $class, $method, $file);
        $this->LogHandler->critical(Syscp_Handler_Log_Interface::FACILITY_SYSTEM, $error);
        $this->LogHandler->critical(Syscp_Handler_Log_Interface::FACILITY_SYSTEM, 'Exiting without lockfile removal...');
        throw new Syscp_Exception($error);
    }

    // cleanup - to have a clean context for next run

    unset($file);
    unset($class);
    unset($method);
    unset($params);
}

