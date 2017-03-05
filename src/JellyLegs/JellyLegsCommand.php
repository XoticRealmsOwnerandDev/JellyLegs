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
namespace JellyLegs;

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

use pocketmine\utils\TextFormat as TF;

class JellyLegsCommand implements CommandExecutor {
	
	private $plugin;
	
	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
	    if($command->getName() === "jellylegs") {
	        if(empty($args)) {
				if($sender instanceof Player) {
					if(!$sender->hasPermission("jellylegs.*") || !$sender->hasPermission("jellylegs.toggle.self")) { 
						$sender->sendMessage($this->plugin->prefix . TF::DARK_RED . "No permission.");
						return true;
					}
					elseif(in_array($sender->getName(), $this->plugin->players)) {
						unset($this->plugin->players[$sender->getName()]);
						$sender->sendMessage($this->plugin->prefix . TF::RED . "You can now take fall damage.");
						return true;
					} 
					else {
						$this->plugin->players[$sender->getName()] = $sender->getName();
						$sender->sendMessage($this->plugin->prefix . TF::GREEN . "You can no longer take fall damage.");
						return true;
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
						$sender->sendMessage($this->plugin->prefix . TF::DARK_RED . "No permission.");
						return true;
					}
					$this->plugin->reloadConfig();
					$sender->sendMessage($this->plugin->prefix . TF::GOLD . "Config has been reloaded!");
					return true;
				}
				if(!$sender->hasPermission("jellylegs.*") || !$sender->hasPermission("jellylegs.toggle.others")) { 
					$sender->sendMessage($this->plugin->prefix . TF::DARK_RED . "No permission.");
					return true;
				}

				$target = $this->plugin->getServer()->getPlayer($args[0]);
					
				if(!$target == null) {
					if(in_array($target->getName(), $this->plugin->players)) {
						unset($this->plugin->players[$target->getName()]);
						$sender->sendMessage($this->plugin->prefix . TF::RED . "Player '" . $target->getName() . "' can now take fall damage.");
						$target->sendMessage($this->plugin->prefix . TF::RED . "You can now take fall damage.");
						return true;
					} 
					else {
						$this->plugin->players[$target->getName()] = $target->getName();
						$sender->sendMessage($this->plugin->prefix . TF::GREEN . "Player '" . $target->getName() . "' can no longer take fall damage.");
						$target->sendMessage($this->plugin->prefix . TF::GREEN . "You can no longer take fall damage.");
						return true;
					} 
				}
				else {
					$sender->sendMessage(TF::RED . "That player cannot be found.");
					return true;
				}
			}
			if(count($args) >= 2) {
				$sender->sendMessage($sender instanceof Player ? TF::RED . "Usage: /jellylegs [player]" : TF::RED . "Usage: /jellylegs <player>");
				return false;
			}
		}
	}
}
