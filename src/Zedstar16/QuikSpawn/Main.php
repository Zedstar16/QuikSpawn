<?php

declare(strict_types=1);

namespace Zedstar16\QuikSpawn;

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\inventory\Inventory;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {


    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this ,$this);
    }

    /**
     * @param EntityDamageEvent $event
     * Priority HIGHEST
     */

    public function onDamage(EntityDamageEvent $event)
    {
        if ($event->getEntity() instanceof Player) {
            if ($event->getEntity()->getHealth() < $event->getFinalDamage()) {
                $player = $event->getEntity()->getPlayer();
                $player->addTitle(TextFormat::RED."You Died");
                $pos = new Vector3($player->getX(), $player->getY(), $player->getZ());
                $player->setHealth($player->getMaxHealth());
                $player->setFood(20);
                $player->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
                $player->removeAllEffects();
                $player->getInventory()->dropContents($player->getLevel(), $pos);
                $player->getInventory()->clearAll();
                Server::getInstance()->getPluginManager()->callEvent(new PlayerDeathEvent(Server::getInstance()->getPlayer($player->getName()), $player->getInventory()->getContents(), null));
                $event->setCancelled();
            }
        }

    }

}