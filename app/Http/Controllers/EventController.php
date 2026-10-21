<?php

namespace App\Http\Controllers;

use App\Models\news;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Sidebar → News: news items and events, each with any number of photos (event_photos).
class EventController extends Controller
{
    // /newscontent
    public function index()
    {
        // events without a photo are not listed (defect D9).
        $events = news::has('photos')->with('photos')->get();

        return view('newscontent', [
            'onevents' => $events->where('status', 'Ongoing')->values(),
            'pastevents' => $events->where('status', 'Past')->values(),
        ]);
    }

    // POST /insert_news
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => Upload::IMAGE,
        ]);

        $id = DB::table('events')->insertGetId([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'venue' => $request->venue,
            'article_link' => $request->link,
            'admin_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($request->file('images', []) as $file) {
            $this->addPhoto($id, $file);
        }

        return redirect('/newscontent')->with('success', 'event added successfully!');
    }

    // GET /editnews?id=
    public function edit(Request $request)
    {
        $event = $this->findForEdit('events', $request);

        return view('editnews', ['form' => [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'venue' => $event->venue,
            'link' => $event->article_link,
            'startdate' => $this->dateInput($event->start_date),
            'enddate' => $this->dateInput($event->end_date),
        ]]);
    }

    // POST /editnews_route
    public function update(Request $request)
    {
        // validate everything before any update so a bad upload can't leave a half-saved record.
        $request->validate([
            'id' => 'required|exists:events,id',
            'news' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'startdate' => 'nullable|date',
            'enddate' => 'nullable|date|after_or_equal:startdate',
            'image' => 'nullable|'.Upload::IMAGE,
        ]);

        $id = $request->input('id');
        DB::table('events')->where('id', $id)->update([
            'title' => $request->news,
            'venue' => $request->venue,
            'article_link' => $request->link,
            'start_date' => $request->startdate,
            'description' => $request->description,
            'end_date' => $request->enddate,
        ]);

        // the image is optional; a new one is added to the event's photos instead of replacing them.
        if ($request->hasFile('image')) {
            $this->addPhoto($id, $request->file('image'));
        }

        return redirect('/newscontent')->with('success', 'event updated successfully!');
    }

    private function addPhoto(int|string $eventId, $file): void
    {
        DB::table('event_photos')->insert([
            'photo' => Upload::store($file, 'images/events'),
            'event_id' => $eventId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
