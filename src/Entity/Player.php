<?php
namespace App\Entity;

class Player
{
    private const PLAY_PLAY_STATUS = 'play';
    private const BENCH_PLAY_STATUS = 'bench';

    private int $number;
    private string $name;
    private string $playStatus;
    private int $inMinute;
    private int $outMinute;

    public function __construct(int $number, string $name)
    {
        $this->number = $number;
        $this->name = $name;
        $this->playStatus = self::BENCH_PLAY_STATUS;
        $this->inMinute = 0;
        $this->outMinute = 0;
    }

    public function getPlayerGoals(array $message, string $player_number, string $team_name): void
    {
        $count = 0;
        $i = 0;
        foreach ($message as $message) {
            if (($message['type'] == "goal") && 
                ($message['details']['playerNumber'] == $player_number) &&
                ($message['details']['team'] == $team_name)
               ) {
                $count++;
            }
        }
    
        if ($count > 0) {
            for ($i=0; $i < $count; $i++) {
                echo "<img src='https://www.soccer.ru/sites/all/themes/newtheme/images/events-icons/goal.png'>","&nbsp";
            }
        }
    }

    public function getCards(array $message, string $player_number, string $team_name): void {
        $count = 0;

        foreach ($message as $message) {
            if (($message['type'] == "yellowCard") && 
                ($message['details']['playerNumber'] == $player_number) &&
                ($message['details']['team'] == $team_name)
               ) {
                $count++;
                if ($count == 1) {
                    echo "<img src='https://www.soccer.ru/sites/all/themes/newtheme/images/events-icons/card.png'>","&nbsp";
                } else {
                    echo "<img src='https://www.soccer.ru/sites/all/themes/newtheme/images/events-icons/yellow_red.png'>","&nbsp";
                }
            } elseif (($message['type'] == "redCard") && 
                      ($message['details']['playerNumber'] == $player_number) &&
                      ($message['details']['team'] == $team_name)
            ) { echo "<img src='https://www.soccer.ru/sites/all/themes/newtheme/images/events-icons/yellow_red.png'>","&nbsp"; }
        }
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInMinute(): int
    {
        return $this->inMinute;
    }

    public function getOutMinute(): int
    {
        return $this->outMinute;
    }

    public function isPlay(): bool
    {
        return $this->playStatus === self::PLAY_PLAY_STATUS;
    }

    public function getPlayTime(): int
    {
        if(!$this->outMinute) {
            return 0;
        }

        return $this->outMinute - $this->inMinute;
    }

    public function goToPlay(int $minute): void
    {
        $this->inMinute = $minute;
        $this->playStatus = self::PLAY_PLAY_STATUS;
    }

    public function goToBench(int $minute): void
    {
        $this->outMinute = $minute;
        $this->playStatus = self::BENCH_PLAY_STATUS;
    }
}