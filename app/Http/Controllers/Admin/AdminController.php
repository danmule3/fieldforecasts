<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Every admin controller extends this. Authorization itself
 * (auth + the `access-admin-panel` gate) is enforced at the route
 * level in routes/admin.php via middleware — not here — since
 * Laravel 11+ favors route/route-group middleware registration over
 * calling $this->middleware() inside a controller constructor. This
 * base class exists as the shared type for `instanceof` checks and a
 * home for any admin-wide helpers added later (e.g. breadcrumbs).
 */
abstract class AdminController extends Controller
{
}
