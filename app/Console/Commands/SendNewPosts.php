<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\SentPost;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNewPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send new posts to all subscribers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts = Post::whereDoesntHave('sentPosts')->get();

        foreach ($posts as $post) {
            $subscriptions = Subscription::where('website_id', $post->website_id)->get();
            foreach ($subscriptions as $subscription) {
                $sentPostExist = SentPost::where('post_id', $post->id)->where('user_id', $subscription->user_id)->exists();

                if (!$sentPostExist) {
                    Mail::raw(
                        "Title: {$post->title} \n Description: {$post->description}",
                        function ($message) use ($subscription) {
                            $message->to($subscription->user->email)
                                ->subject('New Post Available');
                        }
                    );

                    SentPost::create([
                        'post_id' => $post->id,
                        'user_id' => $subscription->user_id
                    ]);
                }
            }
        }

        $this->info('Emails have been sent successfully.');

        return 0;
    }
}
