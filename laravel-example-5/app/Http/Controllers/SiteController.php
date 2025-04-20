<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSite;
use App\DataTables\SiteDataTable;
use App\Http\Requests\UpdateSite;
use App\DataTables\SitePageDataTable;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->expectsJson()) {
            return [
                'items' => (new SiteDataTable(auth()->user()))->get(),
            ];
        }

        return view('sites.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Site::class);

        return view('sites.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSite  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSite $request)
    {
        $site = $request->persist();

        if (request()->expectsJson()) {
            return [
                'redirect' => route('sites.show', [
                    'site' => $site,
                    'alert' => "Site \"{$site->name}\" created successfully",
                    'alert_type' => 'success',
                    'alert_timeout' => 5000,
                ]),
            ];
        }

        return redirect(route('sites.show', ['site' => $site]))->with(
            'status',
            "Site \"{$site->name}\" created successfully"
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        $this->authorize('view', $site);

        if (request()->expectsJson()) {
            return [
                'items' => (new SitePageDataTable($site))->get(),
            ];
        }

        return view('sites.show', [
            'site' => $site,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        $this->authorize('update', $site);

        return view('sites.edit', ['site' => $site]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSite  $request
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSite $request, Site $site)
    {
        $site = $request->persist();
        $status = "Site \"{$site->name}\" updated successfully";

        if (request()->expectsJson()) {
            return [
                'status' => $status,
            ];
        }

        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        $this->authorize('delete', $site);

        try {
            $site->delete();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $status = "Site \"{$site->name}\" deleted successfully";
        if (request()->expectsJson()) {
            return [
                'status' => $status,
            ];
        }

        return redirect(route('sites.index'))->with('status', $status);
    }
}
