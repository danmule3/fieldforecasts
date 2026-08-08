<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Menus themselves (header/footer) are fixed by `location` and seeded
 * once — admin work here is entirely about managing each menu's items,
 * so this controller skips create/destroy for Menu and focuses on the
 * items CRUD nested under a menu.
 */
class MenuController extends AdminController
{
    public function index(): View
    {
        return view('admin.menus.index', ['menus' => Menu::with('items.children')->get()]);
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menus.edit', ['menu' => $menu->load('items.children')]);
    }

    public function storeItem(Request $request, Menu $menu): RedirectResponse
    {
        $data = $this->validated($request);
        $data['menu_id'] = $menu->id;

        MenuItem::create($data);

        return redirect()->route('admin.menus.edit', $menu)->with('status', 'Menu item added.');
    }

    public function updateItem(Request $request, Menu $menu, MenuItem $item): RedirectResponse
    {
        $item->update($this->validated($request));

        return redirect()->route('admin.menus.edit', $menu)->with('status', 'Menu item updated.');
    }

    public function destroyItem(Menu $menu, MenuItem $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('admin.menus.edit', $menu)->with('status', 'Menu item removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:60'],
            'url' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:menu_items,id'],
            'display_order' => ['required', 'integer', 'min:0'],
        ]);

        $data['opens_new_tab'] = $request->boolean('opens_new_tab');

        return $data;
    }
}
