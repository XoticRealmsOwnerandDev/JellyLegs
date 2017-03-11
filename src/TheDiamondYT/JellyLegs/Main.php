<?php
/*
 *       _      _ _       _                    
 *      | |    | | |     | |                   
 *      | | ___| | |_   _| |     ___  __ _ ___ 
 *  _   | |/ _ \ | | | | | |    / _ \/ _` / __|
 * | |__| |  __/ | | |_| | |___|  __/ (_| \__ \
 *  \____/ \___|_|_|\__, |______\___|\__, |___/
 *                   __/ |            __/ |    
 *                  |___/            |___/  
 *
 *
 * By TheDiamondYT
 */
namespace TheDiamondYT\JellyLegs;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

use pocketmine\event\entity\EntityDamageEvent;

class Main extends PluginBase implements Listener {
	
	public $prefix = "";
	public $players = array();
	
	public function onEnable() {
		$this->saveDefaultConfig();
		$this->prefix = $this->getConfig()->get("prefix", "§5[§bJellyLegs§5] ");
		$this->getServer()->getCommandMap()->register("jellylegs", new JellyLegsCommand($this));
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getLogger()->info($this->prefix . TF::GREEN . "JellyLegs by TheDiamondYT loaded!");
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
