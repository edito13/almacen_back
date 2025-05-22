<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Equipment;

class LowStockNotification extends Notification
{
    use Queueable;

    public $equipment;

    public function __construct(Equipment $equipment)
    {
        $this->equipment = $equipment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⚠️ Estoque Baixo: ' . $this->equipment->name)
            ->line('O equipamento "' . $this->equipment->name . '" está com o estoque baixo.')
            ->line('Quantidade atual: ' . $this->equipment->quantity)
            ->line('Quantidade mínima: ' . $this->equipment->min_quantity)
            ->action('Ver Equipamento', url('/equipments/' . $this->equipment->id))
            ->line('Favor tomar as devidas providências.');
    }
}

