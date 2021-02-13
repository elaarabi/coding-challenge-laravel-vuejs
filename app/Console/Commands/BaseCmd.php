<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;

abstract class BaseCmd extends Command
{
    /**
     * Format CLI Output
     * @param array $data
     */
    public function formatResult(array $data)
    {
        switch ($data['type']) {
            case 'success' :
                $this->info("Success : " . $data['info']);
                break;
            case 'error' :
                $this->error("Error : " . $data['info']);
                break;
        }
    }
}
