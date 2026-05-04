<?php

namespace Database\Seeders;

use App\Models\Choice;
use App\Models\Node;
use App\Models\Story;
use App\Models\Token;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoryWeaverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // STORY
        $story = Story::create([
            'title' => 'La Porta Sconosciuta',
            'description' => 'Una breve avventura di test'
        ]);

        // TOKENS
        $key = Token::create([
            'story_id' => $story->id,
            'name' => 'Chiave Dorata',
        ]);

        $memory = Token::create([
            'story_id' => $story->id,
            'name' => 'Frammento di Memoria',
        ]);

        // NODES
        $start = Node::create([
            'story_id' => $story->id,
            'title' => 'Inizio',
            'text' => 'Ti svegli davanti a una porta misteriosa.',
            'is_start' => true
        ]);

        $left = Node::create([
            'story_id' => $story->id,
            'title' => 'Strada Sinistra',
            'text' => 'Trovi un sentiero oscuro.'
        ]);

        $right = Node::create([
            'story_id' => $story->id,
            'title' => 'Porta',
            'text' => 'La porta si apre lentamente.'
        ]);

        // CHOICES
        $choice1 = Choice::create([
            'node_id' => $start->id,
            'text' => 'Vai a sinistra',
            'next_node_id' => $left->id,
            'order' => 1
        ]);

        $choice2 = Choice::create([
            'node_id' => $start->id,
            'text' => 'Apri la porta',
            'next_node_id' => $right->id,
            'order' => 2
        ]);

        // TOKENS ASSIGNMENT
        $choice2->tokens()->attach($key->id);
        $choice1->tokens()->attach($memory->id);
    }
}
