<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class SavePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,'https://jsonplaceholder.typicode.com/posts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch); // Close the connection

        foreach ($result as $post){
            $post = Post::create([
                'title' => $post['title'],
                'body' => $post['body'],
                'user_id' => $post['userId']
            ]);
        }
    }
}
