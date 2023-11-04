<?php

namespace App\Http\Controllers;

use App\Models\KeywordReply;
use App\Models\KeywordReplyText;
use App\Models\SearchTerm;
use App\Models\SearchTermExclusion;
use Illuminate\Http\Request;

class SearchTermsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $searchTerms = SearchTerm::paginate(10);

        return view('search-terms.index', compact('searchTerms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('search-terms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $searchTerm = SearchTerm::create();
        $searchTermExclusion = null;

        if ($request->filled('tags')) {
            $searchTerm->attachTags($request->get('tags'));
        }

        if ($request->filled('exclusions')) {
            $searchTermExclusion = SearchTermExclusion::create();
            $searchTermExclusion->attachTags($request->get('exclusions'));
        }

        $keywordReply = KeywordReply::create([
            'search_term_id' => $searchTerm->id,
            'search_term_exclusion_id' =>$searchTermExclusion ? $searchTermExclusion->id : null,
        ]);

        if ($request->filled('replies')) {
            foreach ($request->get('replies') as $reply) {
                $keywordReply->replies()->create([
                    'reply' => $reply,
                ]);
            }
        }

        return redirect()->route('search-terms.index');

//        foreach ($request->get('keywords') as $keyword)
    }

    /**
     * Display the specified resource.
     */
    public function show(SearchTerm $searchTerm)
    {
        $searchTerm->load( 'keyword.replies', 'keyword.searchTermExclusion');
        return view('search-terms.show', compact('searchTerm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SearchTerm $searchTerm)
    {
        $searchTerm->load( 'keyword.replies', 'keyword.searchTermExclusion');
        return view('search-terms.edit', compact('searchTerm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SearchTerm $searchTerm)
    {
        if ($request->filled('tags')) {
            $searchTerm->syncTags($request->get('tags'));
        }
        $searchExclusion = $searchTerm->keyword->searchTermExclusion;
        if ($request->filled('exclusions') && $searchExclusion == null) {
            $searchExclusions = SearchTermExclusion::create();
            $searchExclusions->attachTags($request->get('exclusions'));

            $searchTerm->keyword
                ->update([
                    'search_term_exclusion_id' => $searchExclusion->id,
                ]);
        }

        if ($request->filled('replies')) {
            foreach ($request->get('replies') as $id => $reply) {
                $keywordReply = $searchTerm->keyword->replies()->where('id', $id)->first();
                if ($keywordReply) {
                    $keywordReply->update([
                        'reply' => $reply,
                    ]);

                    continue;
                }

                $searchTerm->keyword->replies()->create([
                    'reply' => $reply,
                ]);
            }
        }

        return redirect()->route('search-terms.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SearchTerm $searchTerm)
    {
        $searchTerm->delete();

        return back();
    }

    public function deleteReply(KeywordReplyText $reply)
    {
        $reply->delete();

        return response()->json(['success' => true]);
    }
}
