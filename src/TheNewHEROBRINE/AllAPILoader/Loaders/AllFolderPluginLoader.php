<?php

namespace TheNewHEROBRINE\AllAPILoader\Loaders;

use FolderPluginLoader\FolderPluginLoader;
use pocketmine\plugin\{
	Plugin, PluginDescription
};
use pocketmine\Server;

class AllFolderPluginLoader extends FolderPluginLoader{
	/**
	 * Gets the PluginDescription from the file
	 *
	 * @param string $file
	 *
	 * @return null|PluginDescription
	 */
	public function getPluginDescription(string $file) : ?PluginDescription{
		if(is_dir($file) and file_exists($file . "/plugin.yml")){
			$yaml = @file_get_contents($file . "/plugin.yml");
			if($yaml != ""){
				$server = Server::getInstance();
				$description = new PluginDescription($yaml);
				if(!$server->getPluginManager()->getPlugin($description->getName()) instanceof Plugin and !in_array($server->getApiVersion(), $description->getCompatibleApis())){
					$api = (new \ReflectionClass("pocketmine\plugin\PluginDescription"))->getProperty("api");
					$api->setAccessible(true);
					$api->setValue($description, [$server->getApiVersion()]);
					return $description;
				}
				return $description;
			}
		}

		return null;
	}

}