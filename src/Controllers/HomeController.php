<?php
namespace App\Controllers;

use App\Models\Candidate;
use App\Models\Vote;

class HomeController
{
    public function index(): void
    {
        $candidates = Candidate::getAll();
        $totalVotes = Vote::getTotalVotes();
        
        require __DIR__ . '/../../public/views/home.php';
    }
}
