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

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use pocketmine\utils\TextFormat as TF;

class JellyLegsCommand implements CommandExecutor {
	
	private $plugin;
	
	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
	    if($command->getName() === "jellylegs") {
	    	switch(count($args)) {
			case 0:
				if($sender instanceof Player) {
					if(!$sender->hasPermission("jellylegs.toggle.self")) { 
						return false;
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
					return false;
				}
		        case 1:
				if(!$sender->hasPermission("jellylegs.toggle.others")) { 
					return false;
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
					$sender->sendMessage(TF::RED . "That player cannot be found");
					return true;
				}
			default:
				$sender->sendMessage($sender instanceof Player ? TF::RED . "Usage: /jellylegs [player]" : TF::RED . "Usage: /jellylegs <player>");
				return false;
			}
		}
	}
}