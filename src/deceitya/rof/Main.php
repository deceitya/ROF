<?php
namespace deceitya\rof;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;

class Main extends PluginBase
{
    private $forms = [];

    public function onEnable()
    {
        $this->saveResource('form.json');
        $stream = fopen($this->getDataFolder() . 'form.json', 'r');
        $this->forms = json_decode(stream_get_contents($stream), true);
        fclose($stream);

        mt_srand();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!($sender instanceof Player)) {
            $sender->sendMessage('サーバー内から操作して下さい。');
            return true;
        }

        if (!isset($args[0])) {
            return false;
        }

        if (!isset($this->forms[$args[0]])) {
            $sender->sendMessage("{$args[0]}というフォームは存在しません。");
            return true;
        }

        $packet = new ModalFormRequestPacket();
        $packet->formId = mt_rand(10000, 1000000);
        $packet->formData = json_encode($this->forms[$args[0]]);
        $sender->dataPacket($packet);

        return true;
    }
}
