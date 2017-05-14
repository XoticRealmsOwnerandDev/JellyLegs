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
	
	private $language;
	
	public $players = array();
	
	public function onLoad() {
	    //self::$instance = $this; 
	       
	    // Create config
	    $this->saveDefaultConfig();
	     
	    // Load language
	    $this->language = new Config($this->getFile() . "resources/lang/" . $this->getConfig()->get("language", "en") . ".json", Config::JSON);
	}
	
	public function onEnable() {
		$this->getServer()->getCommandMap()->register("jellylegs", new JellyLegsCommand($this));
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	/**
	 * @param EntityDamageEvent $ev
	 *
	 * @priority        HIGH
	 * @ignoreCancelled false
	 */
	public function onEntityFall(EntityDamageEvent $ev) {
		if(($player = $ev->getEntity()) instanceof Player) {
			if($ev->getCause() === EntityDamageEvent::CAUSE_FALL) {
				if($this->hasFallDamage($player)) {
					$ev->setCancelled();
				}
			}
		}
	}
	
	/**
	 * Toggle fall damage for the specified player.
	 *
	 * @param Player    $player
	 * @param bool|null $value
	 */
	public function toggleFallDamage(Player $player, $value = null) {
	    if($value === null) {
	        if($this->hasFallDamage($player)) {
	            $this->toggleFallDamage($player, true);
	        } else {
	            $this->toggleFallDamage($player, false);
	        }
	    }
	    elseif($value === true) {
	        unset($this->players[$player->getName()]);
	    } else {
	        $this->players[$player->getName()] = $player;
	    }
	}
	
	/** 
	 * Check if the specified player can take fall damage.
	 *
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function hasFallDamage(Player $player): bool {
	    return in_array($player->getName(), $this->players);
	}
	
	/**
	 * Translate a message.
	 *
	 * @param string $text
	 * @param array  $params
	 *
	 * @return string
	 */
	public function translate(string $text, array $params = []): string {
	    if(!empty($params)) {
	        return vsprintf($this->language->getNested($text), $params);
	    }
	    return $this->language->getNested($text);
	}
}
