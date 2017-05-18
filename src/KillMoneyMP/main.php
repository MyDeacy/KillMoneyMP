<?php
namespace KillMoneyMP;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
class main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->mp = $this->getServer()->getPluginManager()->getPlugin("MoneyPlusAPI");
		$this->unit = $this->mp->getUnit();

		if(!file_exists($this->getDataFolder())){
		    mkdir($this->getDataFolder(), 0744, true);
		}
		$this->c = new Config($this->getDataFolder() . "config.yml", Config::YAML,
		array(
		'Add-Money'=>'10',
		));
	}

	public function onKill(PlayerDeathEvent $event){
		$cause = $event->getEntity()->getLastDamageCause();
		$name = $event->getEntity()->getName();
		if ($cause instanceof EntityDamageByEntityEvent){
			$player = $cause->getDamager();
			$name = $player->getName();
			$price = $this->c->get("Add-Money");
			$this->mp->addMoney($name, $price);
			$player->sendMessage("§e >> ".$price.$this->unit." 入手しました!");
		}
	}
}