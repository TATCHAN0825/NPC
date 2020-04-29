<?php

declare(strict_types=1);

namespace tatchan\npc;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\entity\{Entity, Human};
use tatchan\npc\Form\npc;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\utils\Config;
use pocketmine\item\Item;
class main extends PluginBase implements Listener
{

    public function onEnable(): void
    {
        $this->getLogger()->info("Hello World!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "npc":
                $sender->sendForm(new npc());
                return true;
            case "npcremover":
                $item = Item::get(369,0,1);
                $item->setCustomName("§aNPCREMOVER");
                $sender->getInventory()->addItem($item);
                return true;
            default:
                return false;
        }
    }

    public function onDamage(EntityDamageEvent $event)
    {
        if ($event->getCause() === 1) {
            $damager = $event->getDamager();
            $entity = $event->getEntity();
            $name = $damager->getInventory()->getItemInHand()->getName();
            if ($event->getEntity() instanceof Human) {
                $id = $entity->getId();
                if (($speak = $entity->namedtag->getCompoundTag("speak")) !== null) {
                    foreach ($speak as $stringTag) {
                        $damager->sendMessage($stringTag->getValue());
                    }
                }
                if($name == "§aNPCREMOVER"){
                    $entity->kill();
                }
                $event->setCancelled();
            }
        }
    }
}
