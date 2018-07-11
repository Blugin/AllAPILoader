<?php

namespace TheNewHEROBRINE\AllAPILoader\Loaders;

use pocketmine\plugin\PharPluginLoader;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginDescription;
use pocketmine\Server;

class AllPharPluginLoader extends PharPluginLoader{
	/**
	 * Gets the PluginDescription from the file
	 *
	 * @param string $file
	 *
	 * @return null|PluginDescription
	 */
	public function getPluginDescription(string $file) : ?PluginDescription{
		$phar = new \Phar($file);
		if(isset($phar["plugin.yml"])){
			$pluginYml = $phar["plugin.yml"];
			if($pluginYml instanceof \PharFileInfo){
				$server = Server::getInstance();
				$description = new PluginDescription($pluginYml->getContent());
				if(!$server->getPluginManager()->getPlugin($description->getName()) instanceof Plugin and !in_array($server->getApiVersion(), $description->getCompatibleApis())){
					$api = (new \ReflectionClass("pocketmine\plugin\PluginDescription"))->getProperty("api");
					$api->setAccessible(true);
					$api->setValue($description, [$server->getApiVersion()]);
					return $description;
				}
			}
		}

		return null;
	}
}