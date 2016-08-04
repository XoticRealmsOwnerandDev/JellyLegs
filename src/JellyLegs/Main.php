<?php

/*
 * 
 *   __________         ______                                   ____      _________ 
 *   |__   __| |        |  __ \(_)                               | \ \   / /__   __|
 *      | |  | |__   ___| |  | |_  __ _ _ __ ___   ___  _ __   __| |\ \_/ /   | |   
 *      | |  | '_ \ / _ \ |  | | |/ _` | '_ ` _ \ / _ \| '_ \ / _` | \   /    | |   
 *      | |  | | | |  __/ |__| | | (_| | | | | | | (_) | | | | (_| |  | |     | |   
 *      |_|  |_| |_|\___|_____/|_|\__,_|_| |_| |_|\___/|_| |_|\__,_|  |_|     |_|         
 *
 *
 */
namespace JellyLegs;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;

use pocketmine\utils\Config;

use pocketmine\event\entity\EntityDamageEvent;

class Main extends PluginBase implements Listener {
	
	public $prefix = "";
	public $players = array();
	
	public function onEnable() {
		$this->getCommand("jellylegs")->setExecutor(new JellyLegsCommand($this));
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$this->prefix = $this->getConfig()->get("prefix", "§2[§bJellyLegs§2] ");
	}
	
	public function onFall(EntityDamageEvent $ev) {
		if($ev->getEntity() instanceof Player) {
			if($ev->getCause() === EntityDamageEvent::CAUSE_FALL) {
				if(in_array($ev->getEntity()->getName(), $this->players)) {
					$ev->setCancelled();
				}
			}
		}
	}
}