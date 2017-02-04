<?php

namespace App\Services;

use App\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

class RssFeed
{
  /**
   * Return the content of the RSS feed
   */
  public function getRSS()
  {
    if (Cache::has('rss-feed')) {
      return Cache::get('rss-feed');
    }

    $rss = $this->buildRssData();
    Cache::add('rss-feed', $rss, 120);

    return $rss;
  }

  /**
   * Return a string with the feed data
   *
   * @return string
   */
  protected function buildRssData()
  {
    $now = Carbon::now();
    $feed = new Feed();
    $channel = new Channel();
    $channel
      ->title(config('event.title'))
      ->description(config('event.description'))
      ->url(url())
      ->language('en')
      ->copyright('Copyright (c) '.config('event.author'))
      ->lastBuildDate($now->timestamp)
      ->appendTo($feed);

    /*$posts = Post::where('published_at', '<=', $now)
      ->where('is_draft', 0)
      ->orderBy('published_at', 'desc')
      ->take(config('event.rss_size'))
      ->get();
    */

    $events = Event::future()->orderBy('start_at','desc')->take(config('event.rss_size'))->get();

    foreach ($events as $event) {
      $item = new Item();
      $item
        ->title($event->name)
        ->description($event->start_at->format('l F jS Y').'<br>'.$event->description)
        ->contentEncoded('<div>'.$event->start_at->format('l F jS Y').'<br>'.$event->description.'</div>')
        ->url(route('events.show', $event->id))
        ->pubDate($event->created_at->timestamp)
        ->guid($event->id, true)
        ->category('')
        ->appendTo($channel);
    }

    $feed = (string)$feed;

    // Replace a couple items to make the feed more compliant
    $feed = str_replace(
      '<rss version="2.0">',
      '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">',
      $feed
    );
    $feed = str_replace(
      '<channel>',
      '<channel>'."\n".'    <atom:link href="'.url('/rss').
      '" rel="self" type="application/rss+xml" />',
      $feed
    );

    return $feed;
  }
}