<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Http\Resources\AboutResource;
use App\Models\About;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index(): \Inertia\Response
    {
        $about = About::first();

        return Inertia::render('Manage/About/Index', [
            'about' => $about ? new AboutResource($about) : null
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Manage/About/Create');
    }

    public function store(AboutRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $about = About::create([
                'title' => $data['title'],
                'body'  => $data['body'],
                'market_title'  => $data['market_title'],
            ]);

            $about->addMediaFromRequest('file')
                ->usingName($data['title']['en'])
                ->usingFileName(
                    Str::slug($data['title']['en']) . '.' .
                        $request->file('file')->getClientOriginalExtension()
                )
                ->toMediaCollection('about_image');

            $about->addMediaFromRequest('market_file')
                ->usingName('market_file')
                ->usingFileName(
                    'market_file.' . $request->file('market_file')->getClientOriginalExtension()
                )
                ->toMediaCollection('market_image');

            DB::commit();

            return Redirect::route('manage.about.index')
                ->with('success', 'About us has been created.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('About creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create. Please try again.');
        }
    }

    public function edit(About $about): \Inertia\Response
    {
        return Inertia::render('Manage/About/Edit', [
            'about' => new AboutResource($about),
        ]);
    }

    public function update(AboutRequest $request, About $about): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $about->update([
                'title' => $data['title'],
                'body'  => $data['body'],
                'market_title'  => $data['market_title'],
            ]);

            if ($request->hasFile('file')) {
                $about->addMediaFromRequest('file')
                    ->usingName($data['title']['en'])
                    ->usingFileName(
                        Str::slug($data['title']['en']) . '.' .
                            $request->file('file')->getClientOriginalExtension()
                    )
                    ->toMediaCollection('about_image');
            }

            if ($request->hasFile('market_file')) {
                $about->addMediaFromRequest('market_file')
                    ->usingName('market_file')
                    ->usingFileName(
                        'market_file.' . $request->file('market_file')->getClientOriginalExtension()
                    )
                    ->toMediaCollection('market_image');
            }

            DB::commit();
            return Redirect::route('manage.about.index')
                ->with('success', 'About us has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('About update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update. Please try again.');
        }
    }

    public function destroy(About $about): \Illuminate\Http\RedirectResponse
    {
        $about->delete();

        return Redirect::route('manage.about.index')->with('warning', 'About us has been deleted.');
    }
}
