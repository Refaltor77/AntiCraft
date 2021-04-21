<?php

namespace refaltor\antiCraft;

use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\Listener;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase implements Listener
{
	private static $ids = [];
    public function onEnable()
    {
        $this->saveDefaultConfig();
        Server::getInstance()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCraft(CraftItemEvent $event){
        $config = $this->getConfig();
        $player = $event->getPlayer();
		foreach($config->get("craft") as $id => $messages){
			$ids = explode(":", $id);
			self::$ids[$ids[0] . ":" . $ids[1]] = $messages;
		}
		foreach ($event->getOutputs() as $item){
			if (array_key_exists($item->getId() . ":" . $item->getDamage(), self::$ids)){
				$event->setCancelled(true);
				$player->sendMessage(self::$ids[$item->getId() . ":" . $item->getDamage()]);
			}
		}
    }
}