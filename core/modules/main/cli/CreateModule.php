<?php

    namespace thebuggenie\core\modules\main\cli;

    use TBGContext;

    /**
     * CLI command class, main -> create_module
     *
     * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
     * @version 3.1
     * @license http://opensource.org/licenses/MPL-2.0 Mozilla Public License 2.0 (MPL 2.0)
     * @package thebuggenie
     * @subpackage core
     */

    /**
     * CLI command class, main -> create_module
     *
     * @package thebuggenie
     * @subpackage core
     */
    class CreateModule extends \TBGCliCommand
    {

        protected function _setup()
        {
            $this->_command_name = 'create_module';
            $this->_description = "Create an empty module ready to start developing";
            $this->addRequiredArgument('module_name', "The module to create, typically 'MyModule' or similar - no spaces!");
        }

        public function do_execute()
        {
            if (TBGContext::isInstallmode())
            {
                $this->cliEcho("Create module\n", 'white', 'bold');
                $this->cliEcho("The Bug Genie is not installed\n", 'red');
            }
            else
            {
                $module_name = ucfirst($this->getProvidedArgument('module_name'));
                $module_key = mb_strtolower($module_name);
                $module_description = "Autogenerated module {$module_name}";
                $this->cliEcho("Initializing empty module ");
                $this->cliEcho("{$module_key}\n", 'white', 'bold');
                $this->cliEcho("Checking that the module doesn't exist ... ");
                if (file_exists(THEBUGGENIE_MODULES_PATH . $module_key))
                {
                    $this->cliEcho("fail\n", 'red');
                    $this->cliEcho("A module with this name already exists\n", 'red');
                    return false;
                }
                else
                {
                    $this->cliEcho("OK\n", 'green', 'bold');
                }

                $this->cliEcho("Checking for conflicting classnames ... ");
                if (class_exists($module_name))
                {
                    $this->cliEcho("fail\n", 'red');
                    $this->cliEcho("A class with this name already exists\n", 'red');
                    return false;
                }
                else
                {
                    $this->cliEcho("OK\n", 'green', 'bold');
                }

                $this->cliEcho("Checking that the module path is writable ... ");
                if (!is_writable(THEBUGGENIE_MODULES_PATH))
                {
                    $this->cliEcho("fail\n", 'red');
                    $this->cliEcho("Module path isn't writable\n\n", 'red');
                    $this->cliEcho("Please make sure that the following path is writable: \n");
                    $this->cliEcho(THEBUGGENIE_MODULES_PATH, 'cyan');
                    return false;
                }
                else
                {
                    $this->cliEcho("OK\n", 'green', 'bold');
                }

                $this->cliEcho("\nCreating module directory structure ... \n", 'white', 'bold');
                $this_module_path = THEBUGGENIE_MODULES_PATH . $module_key . DS;
                mkdir(THEBUGGENIE_MODULES_PATH . $module_key);
                $this->cliEcho('modules' . DS . "{$module_key}\n");
                mkdir($this_module_path . 'classes');
                $this->cliEcho('modules' . DS . $module_key . DS . "classes\n");
                mkdir($this_module_path . 'classes' . DS . 'cli');
                $this->cliEcho('modules' . DS . $module_key . DS . 'classes' . DS . "cli\n");
                mkdir($this_module_path . 'templates');
                $this->cliEcho('modules' . DS . $module_key . DS . "templates\n");
                $this->cliEcho("... ", 'white', 'bold');
                $this->cliEcho("OK\n", 'green', 'bold');

                $this->cliEcho("\nCreating module files ... \n", 'white', 'bold');
                file_put_contents($this_module_path . "class", "{$module_name}|0.1");
                $this->cliEcho('modules' . DS . $module_key . DS . "class\n");
                file_put_contents($this_module_path . "module", $module_description);
                $this->cliEcho('modules' . DS . $module_key . DS . "module\n");

                $module_class_template = file_get_contents(THEBUGGENIE_MODULES_PATH . "main" . DS . "fixtures" . DS . "emptymoduleclass.txt");
                $module_class_content = str_replace(array('module_key', 'module_name', 'module_description'), array($module_key, $module_name, $module_description), $module_class_template);
                file_put_contents($this_module_path . "classes" . DS . $module_name . ".class.php", $module_class_content);
                $this->cliEcho("modules" . DS . $module_key . DS . "classes" . DS . $module_name . ".class.php\n");

                $module_actions_class_template = file_get_contents(THEBUGGENIE_MODULES_PATH . "main" . DS . "fixtures" . DS . "emptymoduleactionsclass.txt");
                $module_actions_class_content = str_replace(array('module_key', 'module_name', 'module_description'), array($module_key, $module_name, $module_description), $module_actions_class_template);
                file_put_contents($this_module_path . "classes" . DS . "actions.class.php", $module_actions_class_content);
                $this->cliEcho("modules" . DS . $module_key . DS . "classes" . DS . "actions.class.php\n");

                $module_actioncomponents_class_template = file_get_contents(THEBUGGENIE_MODULES_PATH . "main" . DS . "fixtures" . DS . "emptymoduleactioncomponentsclass.txt");
                $module_actioncomponents_class_content = str_replace(array('module_key', 'module_name', 'module_description'), array($module_key, $module_name, $module_description), $module_actioncomponents_class_template);
                file_put_contents($this_module_path . "classes" . DS . "actioncomponents.class.php", $module_actioncomponents_class_content);
                $this->cliEcho("modules" . DS . $module_key . DS . "classes" . DS . "actioncomponents.class.php\n");

                file_put_contents($this_module_path . "templates" . DS . "index.html.php", "{$module_name} frontpage");
                $this->cliEcho("modules" . DS . $module_key . DS . "templates" . DS . "index.html.php\n");

                $this->cliEcho("... ", 'white', 'bold');
                $this->cliEcho("OK\n\n", 'green', 'bold');

                $this->cliEcho("The module was created successfully!\n", 'green');
            }
        }

    }
