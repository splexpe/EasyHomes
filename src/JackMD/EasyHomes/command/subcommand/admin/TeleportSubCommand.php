<?php
declare(strict_types = 1);

/**
 *  ___              _  _
 * | __|__ _ ____  _| || |___ _ __  ___ ___
 * | _|/ _` (_-< || | __ / _ \ '  \/ -_|_-<
 * |___\__,_/__/\_, |_||_\___/_|_|_\___/__/
 *              |__/
 *
 * EasyHomes, a Homes plugin for PocketMine-MP.
 * Copyright (c) 2018 JackMD  < https://github.com/JackMD >
 *
 * Discord: JackMD#3717
 * Twitter: JackMTaylor_
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * EasyHomes is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 * ------------------------------------------------------------------------
 */

namespace JackMD\EasyHomes\command\subcommand\admin;

use JackMD\EasyHomes\command\SubCommand;
use JackMD\EasyHomes\language\Lang;
use JackMD\EasyHomes\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TeleportSubCommand extends SubCommand{
	
	/**
	 * @param CommandSender $sender
	 * @return bool
	 */
	public function canUse(CommandSender $sender): bool{
		return $sender->hasPermission("eh.command.admin.tp");
	}
	
	/**
	 * @return string
	 */
	public function getName(): string{
		return Lang::get("command.admin.teleport.name");
	}
	
	/**
	 * @return string
	 */
	public function getUsage(): string{
		return Lang::get("command.admin.teleport.usage");
	}
	
	/**
	 * @return string
	 */
	public function getDescription(): string{
		return Lang::get("command.admin.teleport.desc");
	}
	
	/**
	 * @return array
	 */
	public function getAliases(): array{
		return Lang::get("command.admin.teleport.aliases");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param array         $args
	 * @return bool
	 */
	public function execute(CommandSender $sender, array $args): bool{
		if(!$sender instanceof Player){
			$sender->sendMessage($this->prefix . Lang::get("command.in_game"));
			return false;
		}
		if((count($args) > 2) || (!isset($args[0])) || (!isset($args[1]))){
			$sender->sendMessage($this->prefix . $this->getUsage());
			return false;
		}
		$player = Utils::checkPlayer($args[0]);
		if(is_null($player)){
			$sender->sendMessage($this->prefix . str_replace("{player}", $args[0], Lang::get("command.player_not_found")));
			return false;
		}
		if(!$this->plugin->getProvider()->homeExists($player->getName(), $args[1])){
			$sender->sendMessage($this->prefix . str_replace("{home_name}", $args[1], Lang::get("command.admin.teleport.home_not_exist")));
			return false;
		}
		$homeLocation = $this->plugin->getProvider()->getHome($player->getName(), $args[1]);
		$this->plugin->getAPI()->teleportToHome($sender, $homeLocation);
		$sender->sendMessage($this->prefix . str_replace("{home_name}", $args[1], Lang::get("command.admin.teleport.success")));
		return true;
	}
}