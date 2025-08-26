<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDiscussionRequest;
use App\Http\Requests\UpdateDiscussionRequest;
use Illuminate\Support\Str;

class DiscussionController extends Controller
{
    // Optional: protect with auth
    // public function __construct() { $this->middleware('auth'); }

    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'untuk-saya');

        $query = Discussion::with('user')->latest('created_at');

        if ($activeTab === 'disimpan') {
            $discussions = collect();
        } else {
            $discussions = $query->paginate(10);
        }

        $postsToDisplay = $discussions instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $discussions->getCollection()->map(fn ($d) => $this->mapDiscussionToPost($d))
            : collect();

        return view('forum.page', [
            'activeTab'      => $activeTab,
            'postsToDisplay' => $postsToDisplay,
            'paginator'      => $discussions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $discussions : null,
        ]);
    }

    private function mapDiscussionToPost(Discussion $d): array
    {
        return [
            'title'         => $d->title,
            'time'          => optional($d->created_at)->diffForHumans(),
            'image'         => $this->imageUrl($d->image),
            'content'       => Str::limit((string) $d->desc, 280),
            'best_comment' => [
                'author_name'   => null,
                'author_avatar' => null,
                'content'       => null,
            ],
            'reply_count' => 0,
            'author_name'   => $d->user->name ?? 'Anonim',
            'author_avatar' => $this->userAvatar($d),
        ];
    }

    private function userAvatar(Discussion $d): ?string
    {
        if (!empty($d->user?->avatar)) {
            return Str::startsWith($d->user->avatar, ['http://','https://'])
                ? $d->user->avatar
                : Storage::disk('public')->url($d->user->avatar);
        }
        return asset('images/default-avatar.png');
    }

    private function imageUrl(?string $path): ?string
    {
        if (!$path) return null;
        $path = preg_replace('#^(public/|storage/)+#', '', $path);

        return '/storage/' . ltrim($path, '/');
    }

    public function create()
    {
        return view('discussions.create');
    }

    public function store(StoreDiscussionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id() ?? $request->user_id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('discussion-photos', 'public');
        }
        try {
            Discussion::create($data);
        } catch (\Exception $e){
            dd($e->getMessage());
        }

         return redirect()->route('forum')->with('success', 'Discussion created!');
    }


    public function show(Discussion $discussion)
    {
        $discussion->load('user');
        return view('discussions.show', compact('discussion'));
    }

    public function edit(Discussion $discussion)
    {
        return view('discussions.edit', compact('discussion'));
    }

    public function update(UpdateDiscussionRequest $request, Discussion $discussion)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($discussion->image && Storage::disk('public')->exists($discussion->image)) {
                Storage::disk('public')->delete($discussion->image);
            }
            $data['image'] = $request->file('image')->store('discussions', 'public');
        }

        $discussion->update($data);

        return redirect()
            ->route('discussions.show', $discussion)
            ->with('success', 'Discussion updated!');
    }

    public function destroy(Discussion $discussion)
    {
        if ($discussion->image && Storage::disk('public')->exists($discussion->image)) {
            Storage::disk('public')->delete($discussion->image);
        }

        $discussion->delete();

        return redirect()
            ->route('discussions.index')
            ->with('success', 'Discussion deleted!');
    }
}
