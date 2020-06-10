<?php

namespace tatchan\npc;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use tatchan\npc\Form\npc;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
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
                if ($sender instanceof Player) {
                    $sender->sendForm(new npc());
                    return true;
                }
            case "npcremover":
                if ($sender instanceof Player) {
                    $item = Item::get(369, 0, 1);
                    $item->setCustomName("§aNPCREMOVER");
                    $sender->getInventory()->addItem($item);
                    $sender->sendMessage("§a<<NPCリムーバーを出しました");
                    return true;
                }
            default:
                return false;
        }
    }

    public function onDamage(EntityDamageEvent $event)
    {
        if ($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
            if ($event instanceof EntityDamageByEntityEvent) {
                $entity = $event->getEntity();
                $damager = $event->getDamager();
                if ($damager instanceof Player) {
                    if (($speak = $entity->namedtag->getCompoundTag("speak")) !== null) {
                        foreach ($speak as $stringTag) {
                            $damager->sendMessage($stringTag->getValue());
                        }
                    }
                    $name = $damager->getInventory()->getItemInHand()->getName();
                    if (($type = $entity->namedtag->getCompoundTag("type")) !== null) {
        
                        foreach ($type as $stringTag2) {
                            if ($stringTag2->getValue() == "npc") {
                                if ($name == "§aNPCREMOVER") {
                                    $entity->kill();
                                }
                                $event->setCancelled(true);
                            }
                        }
                    }
                }
            }
        }
    }
}
