<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $limit     = (int) $request->query('limit', 25);
        $sortKey   = $request->query('sortKey', 'default');
        $sortOrder = $request->query('sortOrder', 'asc');

        $allowedSorts = ['slug', 'default'];
        if (! in_array($sortKey, $allowedSorts, true)) {
            $sortKey = 'default';
        }

        if (! in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'asc';
        }

        $query = Contact::query();

        if ($search = trim((string) $request->query('searchTerm', ''))) {
            $query->where('slug', 'ILIKE', "%{$search}%");
        }

        if ($sortKey === 'default') {
            $query->orderByRaw("
                CASE
                    WHEN slug='email' THEN 1
                    WHEN slug='phone_number' THEN 2
                    WHEN slug='address' THEN 3
                    WHEN slug='instagram' THEN 4
                    WHEN slug='tiktok' THEN 5
                END
            ");
        } else {
            $query->orderBy($sortKey, $sortOrder);
        }

        $contacts = $query
            ->paginate($limit)
            ->withQueryString();

        return Inertia::render('Manage/Contacts/Index', [
            'contacts' => ContactResource::collection($contacts),
            'limit'      => $limit,
            'searchTerm' => $request->query('searchTerm', ''),
        ]);
    }

    public function edit(Contact $contact): \Inertia\Response
    {
        return Inertia::render('Manage/Contacts/Edit', [
            'contact' => new ContactResource($contact),
        ]);
    }

    public function update(Request $request, Contact $contact): \Illuminate\Http\RedirectResponse
    {
        $rules = [
            'value' => ['required', 'max:255'],
            'is_active' => ['nullable', 'boolean']
        ];

        if ($contact->slug === 'instagram' || $contact->slug === 'tiktok') {
            $rules['icon'] = ['required'];
        }

        if ($contact->slug === 'address') {
            $rules['value'] = ['nullable'];
            $rules['locale_value'] = ['required', 'array'];
            $rules['locale_value.*'] = ['required', 'string'];
        }

        $rules = $request->validate($rules);

        if (!$request->has('is_active')) {
            $rules['is_active'] = false;
        }

        $contact->update($rules);

        return redirect()->route('manage.contacts.index')->with('success', __('Updated msg', ['name' => __('Contact')]));
    }
}
