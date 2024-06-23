<?php

namespace Modules\TravelAgency\Services;

use ReflectionClass;
use ReflectionException;

class IntegrationFactory
{
    private string $namespace = 'Modules/TravelAgency/Services/Integrations/';

    public function getByModule($moduleName, string $methodName): array
    {
        return call_user_func([__NAMESPACE__ . '\Integrations\\' . $moduleName, $methodName]);

    }

    public function handle(string $method): array
    {
        $classNames = $this->getClassNames($this->namespace);

        $result = [];

        foreach ($classNames as $name) {
            array_merge($result, call_user_func([__NAMESPACE__ . '\Integrations\\' . $name, $method]));
        }

        return $result;
    }

    public function getClassNames(string $pathModule): array
    {
        $folderPath = base_path() . '/' . $pathModule;
        $files = scandir($folderPath);
        $classList = [];

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $className = pathinfo($file, PATHINFO_FILENAME);
                $classList[] = $className;
            }
        }

        return $classList;
    }

    /**
     * @throws ReflectionException
     */
    public function newHandler(string $methodName): array
    {

        $folderPath = __DIR__ . "/Integrations";
        $files = scandir($folderPath);
        $result = [];
        $beforeClasses = get_declared_classes();
        foreach ($files as $file) {
            if (is_file($folderPath . DIRECTORY_SEPARATOR . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                include_once $folderPath . DIRECTORY_SEPARATOR . $file;

                // Step 3: Use reflection to get all methods of each class
                $afterClasses = get_declared_classes();
                $newClasses = array_diff($afterClasses, $beforeClasses);
                foreach ($newClasses as $className) {
                    $reflectionClass = new ReflectionClass($className);

                    // Step 4: Call the methods
                    if ($reflectionClass->hasMethod($methodName)) {

                        $method = $reflectionClass->getMethod($methodName);

                        if (!$method->isStatic()) {

                            // Instantiate the class and call the method
                            $object = new $className();
                            $res = $object->$methodName();
                            $result = $result + $res;
                        }
                    }
                }

            }
        }
        return $result;
    }
}