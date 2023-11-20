<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PostController extends Controller
{

    
    public function index(Request $request)
    {
        //bonus filtering
        $author = $request->query('author');
        $title = $request->query('title');
        $content = $request->query('content');
    
        $query = Post::query();
    
        //filters
        if ($author) {
            $query->where('author', 'like', '%' . $author . '%');
        }
    
        if ($title) {
          
            $query->orWhere('title', 'like', '%' . $title . '%');
        }
        if ($content) {
           
            $query->orWhere('content', 'like', '%' . $content . '%');
        }
    
        // Fetch posts
        $posts = $query->get();
    
        return response()->json(['data' => $posts]);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        return response()->json(['data' => $post]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'author' => 'required|string',
        ]);

        $validatedData['published_at'] = Carbon::now();
        $post = Post::create($validatedData);

        return response()->json(['message' => 'Post successfully created', 'data' => $post], 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'author' => 'required|string',
        ]);

        $post = Post::findOrFail($id);
        $post->update($validatedData);

        return response()->json(['message' => 'Post successfully updated', 'data' => $post], 200);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}