<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyRecommendingOfficeCategoryRequest;
use App\Http\Requests\StoreRecommendingOfficeCategoryRequest;
use App\Http\Requests\UpdateRecommendingOfficeCategoryRequest;
use App\Models\RecommendingOfficeCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecommendingOfficeCategoryController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('recommending_office_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recommendingOfficeCategories = RecommendingOfficeCategory::all();

        return view('admin.recommendingOfficeCategories.index', compact('recommendingOfficeCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('recommending_office_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recommendingOfficeCategories.create');
    }

    public function store(StoreRecommendingOfficeCategoryRequest $request)
    {
        $recommendingOfficeCategory = RecommendingOfficeCategory::create($request->all());

        return redirect()->route('admin.recommending-office-categories.index');
    }

    public function edit(RecommendingOfficeCategory $recommendingOfficeCategory)
    {
        abort_if(Gate::denies('recommending_office_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recommendingOfficeCategories.edit', compact('recommendingOfficeCategory'));
    }

    public function update(UpdateRecommendingOfficeCategoryRequest $request, RecommendingOfficeCategory $recommendingOfficeCategory)
    {
        $recommendingOfficeCategory->update($request->all());

        return redirect()->route('admin.recommending-office-categories.index');
    }

    public function show(RecommendingOfficeCategory $recommendingOfficeCategory)
    {
        abort_if(Gate::denies('recommending_office_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.recommendingOfficeCategories.show', compact('recommendingOfficeCategory'));
    }

    public function destroy(RecommendingOfficeCategory $recommendingOfficeCategory)
    {
        abort_if(Gate::denies('recommending_office_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $recommendingOfficeCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyRecommendingOfficeCategoryRequest $request)
    {
        $recommendingOfficeCategories = RecommendingOfficeCategory::find(request('ids'));

        foreach ($recommendingOfficeCategories as $recommendingOfficeCategory) {
            $recommendingOfficeCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
