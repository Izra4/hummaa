<?php 

namespace App\Events;

use App\Models\Material;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaterialProgressUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Material $material,
        public User $user,
        public int $progressPercentage
    ) {}
}