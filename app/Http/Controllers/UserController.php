<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $limit = (int) $request->query('limit', 10);
        $sortKey   = $request->query('sortKey',   'created_at');
        $sortOrder = $request->query('sortOrder', 'desc');
        $allowedSorts = ['name', 'email', 'created_at'];

        if (! in_array($sortKey, $allowedSorts, true)) {
            $sortKey = 'created_at';
        }

        if (! in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'desc';
        }

        $query = User::query();

        if ($search = trim((string) $request->query('searchTerm', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name',  'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        $users = $query
            ->orderBy($sortKey, $sortOrder)
            ->paginate($limit)
            ->withQueryString();

        return Inertia::render('Manage/Users/Index', [
            'users' => UserResource::collection($users),
            'limit' => $limit,
            'searchTerm'    => $request->query('searchTerm', ''),
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Manage/Users/Create');
    }

    public function show(User $user): \Inertia\Response
    {
        return Inertia::render('Manage/Users/Show', [
            'user' => new UserResource($user)
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'name' => [
                'required',
                'min:2'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'password' => [
                'required',
                Password::default(),
            ],
        ]);

        User::create($data);

        return Redirect::route('manage.users.index')->with('success', 'User has been created');
    }

    public function edit(User $user): \Inertia\Response
    {
        return Inertia::render('Manage/Users/Edit', [
            'user' => new UserResource($user)
        ]);
    }

    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'min:2'],
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', Password::default()],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        $previousPath = parse_url(url()->previous(), PHP_URL_PATH);

        if (Str::endsWith($previousPath, '/edit')) {
            return Redirect::route('manage.users.index')->with('success', 'User has been updated.');
        }

        return back()->with('success', 'User has been updated.');
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        $user->delete();

        return Redirect::route('manage.users.index')->with('warning', 'User has been deleted.');
    }
}
