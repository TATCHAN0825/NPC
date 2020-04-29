<?php

namespace tatchan\npc\Form;

use pocketmine\form\Form;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\entity\{Entity, Human};
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\utils\Config;
class npc implements Form{
public function onEnable() {
 }
    public function handleResponse(Player $player, $data): void {

        if ($data === null) {
            return;
        }

$nbt = Entity::createBaseNBT($player, null, $player->yaw, $player->pitch);
$nbt->setTag($player->namedtag->getTag("Skin"));
$npc = new Human($player->getLevel(), $nbt);
$npc->setNameTag($data[0]);
$speak = $npc->namedtag->getCompoundTag("speak") ?? new CompoundTag("speak");
$speak->setString($data[1], $data[1]);
$npc->namedtag->setTag($speak);
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