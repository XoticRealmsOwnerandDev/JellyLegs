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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;

use pocketmine\utils\TextFormat as TF;

class JellyLegsCommand extends Command implements PluginIdentifiableCommand {
	
	private $plugin;
	
	public function __construct(Main $plugin) {
	    parent::__construct("jellylegs", "Toggle fall damage for yourself or others.", "/jellylegs [player]", ["nofall"]);
	    $this->setPermission("jellylegs.toggle.self");
	    
		$this->plugin = $plugin;
	}
	
	public function execute(CommandSender $sender, $label, array $args) {
	    if(empty($args)) {
			if($sender instanceof Player) {
				if(!$sender->hasPermission("jellylegs.*") || !$sender->hasPermission("jellylegs.toggle.self")) { 
					$sender->sendMessage(TF::DARK_RED . $this->plugin->translate("commands.no-permission"));
					return true;
				}
				if($this->plugin->hasFallDamage($sender)) {
				    $this->plugin->toggleFallDamage($sender, true);
					$sender->sendMessage($this->plugin->prefix . TF::RED . "You can now take fall damage.");		
				} else {
				    $this->plugin->toggleFallDamage($sender, false);
					$sender->sendMessage($this->plugin->prefix . TF::GREEN . "You can no longer take fall damage.");		
				}
			}
			else {
				$sender->sendMessage(TF::RED . "Usage: /jellylegs <player>");
				return true;
			}
		}
		if(count($args) === 1) {
			if(strtolower($args[0]) === "reload") {
				if(!$sender->hasPermission("jellylegs.*")) {
					$sender->sendMessage(TF::DARK_RED . $this->plugin->translate("commands.no-permission"));
					return true;
				}
				$this->plugin->reloadConfig();
				$sender->sendMessage($this->plugin->prefix . TF::GOLD . "Config has been reloaded!");
				return true;
			}
			if(!$sender->hasPermission("jellylegs.*") || !$sender->hasPermission("jellylegs.toggle.others")) { 
				$sender->sendMessage(TF::DARK_RED . $this->plugin->translate("commands.no-permission"));
				return true;
			}

			$target = $this->plugin->getServer()->getPlayer($args[0]);
					
			if($target !== null) {
			    $this->plugin->toggleFallDamage($target);
				if($this->plugin->hasFallDamage($target)) {
					$sender->sendMessage($this->plugin->prefix . TF::RED . "Player '" . $target->getName() . "' can now take fall damage.");
					$target->sendMessage($this->plugin->prefix . TF::RED . "You can now take fall damage.");
					return true;
				} else {
					$sender->sendMessage($this->plugin->prefix . TF::GREEN . "Player '" . $target->getName() . "' can no longer take fall damage.");
					$target->sendMessage($this->plugin->prefix . TF::GREEN . "You can no longer take fall damage.");
					return true;
				} 
			}
			else {
				$sender->sendMessage(TF::RED . $this->plugin->translate("commands.player-not-found"));
				return true;
			}
		}
		if(count($args) >= 2) {
			$sender->sendMessage($sender instanceof Player ? TF::RED . "Usage: /jellylegs [player]" : TF::RED . "Usage: /jellylegs <player>");
			return false;
		}
	}
	
	public function getPlugin(){
		return $this->plugin;
	}
}
