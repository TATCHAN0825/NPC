<?php

namespace tatchan\npc\Form;

use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\entity\Human;
use pocketmine\nbt\tag\CompoundTag;

class npc implements Form{
    public function handleResponse(Player $player, $data): void {

        if ($data === null) {
            return;
        }

$nbt = Human::createBaseNBT($player, null, $player->yaw, $player->pitch);
$nbt->setTag($player->namedtag->getTag("Skin"));
$npc = new Human($player->getLevel(), $nbt);
$npc->setNameTag($data[0]);
$type = $npc->namedtag->getCompoundTag("type") ?? new CompoundTag("type");
$speak = $npc->namedtag->getCompoundTag("speak") ?? new CompoundTag("speak");
$speak->setString($data[1], $data[1]);
$type->setString("npc","npc");
$npc->namedtag->setTag($speak);
$npc->namedtag->setTag($type);
$npc->setImmobile();
$npc->spawnToAll();
    }

    public function jsonSerialize(){

        return [
            "type" => "custom_form",
            "title" => "NPC",
            "content" => [
                [
                    "type" => "input",
                    "text" => "名前",
                    "placeholder" => "ここに記入",
                    "default" => "",
                ],
                [
                    "type" => "input",
                    "text" => "しゃべること",
                    "placeholder" => "ここに記入",
                    "default" => ""
                ]
            ]
        ];
    }
}