<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Habit;
use App\Models\Category;
use App\Models\HabitLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers    = User::where('role', 'user')->count();
        $totalHabits   = Habit::count();
        $totalLogs     = HabitLog::count();
        $totalCategory = Category::count();

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalHabits',
            'totalLogs',
            'totalCategory',
            'recentUsers'
        ));
    }

    public function users()
    {
        $users = User::where('role', 'user')
            ->withCount('habits')
            ->latest()
            ->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function categories()
    {
        $categories = Category::withCount('habits')->get();

        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('admin.categories')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    public function destroyUser(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.users')
                ->with('error', 'Tidak bisa menghapus akun admin.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil dihapus.');
    }
}
