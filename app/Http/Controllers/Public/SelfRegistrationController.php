<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySelfRegistrationRequest;
use App\Http\Requests\StoreSelfRegistrationRequest;
use App\Http\Requests\UpdateSelfRegistrationRequest;
use App\Models\Person;
use App\Models\IdType;
use App\Models\SelfRegistration;
use App\Models\VisitingOffice;
use App\Models\VisitingOfficeCategory;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class SelfRegistrationController extends Controller
{
    // use MediaUploadingTrait;



    public function create_visitor()
    {

        $id_types = IdType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $visiting_office_categories = VisitingOfficeCategory::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('public.selfRegistrations.create_visitor', compact('id_types', 'visiting_office_categories'));
    }
    public function create_gallery()
    {

        $id_types = IdType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $visiting_office_categories = VisitingOfficeCategory::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('public.selfRegistrations.create_gallery', compact('id_types', 'visiting_office_categories'));
    }
    public function store_visitor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'mobile' => 'required|min:10',
            'id_type_id' => 'required',
            'id_detail' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'district' => 'required',
            'pincode' => 'required',
        ]);
        $validator->after(function ($validator) use ($request) {

            $person = Person:://where('mobile', $request->mobile)->
            when($request->id_type_id != -1, function($query) use ($request, ) {
                return $query->where('id_type_id', $request->id_type_id)
                        ->where('id_detail', $request->id_detail);
                //return $query->orwhere('id_detail', $request->id_detail);
            })
            ->first();

            if ($person) {

                $validator->errors()->add(
                    'id_detail', 'Person already exists with same id detail'
                );
            }

            $selfRegistration = SelfRegistration:://where('mobile', $request->mobile)->
            orWhere(function ($query) use ($request) {
                $query->where('id_type_id', $request->id_type_id)
                    ->where('id_detail', $request->id_detail);
            })->first();

            if ($selfRegistration) {
                $validator->errors()->add(
                    'id_detail', 'Registration already exists with same id detail'
                );
            }

            $idType = IdType::find($request->id_type_id);
            if ($idType && $idType->name == 'AADHAAR') {

                if (($idType->min_length && strlen($request->id_detail) < $idType->min_length) ) {
                    $validator->errors()->add(
                        'id_detail', "Should be at least {$idType->min_length} digits"
                    );
                }

                if (($idType->max_length && strlen($request->id_detail) > $idType->max_length) ) {
                    $validator->errors()->add(
                        'id_detail', "Should be at most {$idType->max_length} digits"
                    );
                }
            }


        });


        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $selfRegistration = null;

        \DB::transaction(function () use ($request, &$selfRegistration) {
            \Log::info($request->all());
            $age = Carbon::createFromFormat('Y-m-d', $request->dob)->age;
            $lastNumberOfToday = SelfRegistration::whereDate('created_at', Carbon::today())->orderBy('id', 'desc')->first();
            $lastNumber = $lastNumberOfToday ? $lastNumberOfToday->number : 0;
            $number = $lastNumber + 1;

            $selfRegistration = SelfRegistration::create(
                $request->all()
                + ['age' => $age, 'pass_type' => 'visitor', 'number' => $number]);
        });

        return view('public.selfRegistrations.show_visitor', compact('selfRegistration') );

    }

    public function store_gallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'mobile' => 'required|min:10',
            'id_type_id' => 'required',
            'id_detail' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'district' => 'required',
            'pincode' => 'required',
            'post_office' => 'required',
            'group_persons' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {

            $person = Person:://where('mobile', $request->mobile)->
            when($request->id_type_id != -1, function($query) use ($request, ) {
                return $query->where('id_type_id', $request->id_type_id)
                        ->where('id_detail', $request->id_detail);
                //return $query->orwhere('id_detail', $request->id_detail);
            })
            ->first();

            if ($person) {

                $validator->errors()->add(
                    'id_detail', 'Person already exists with same id detail'
                );
            }

            $selfRegistration = SelfRegistration:://where('mobile', $request->mobile)->
            orWhere(function ($query) use ($request) {
                $query->where('id_type_id', $request->id_type_id)
                    ->where('id_detail', $request->id_detail);
            })->first();

            if ($selfRegistration) {
                $validator->errors()->add(
                    'id_detail', 'Registration already exists with same id detail'
                );
            }


            $idType = IdType::find($request->id_type_id);
            if ($idType && $idType->name == 'AADHAAR') {

                if (($idType->min_length && strlen($request->id_detail) < $idType->min_length) ) {
                    $validator->errors()->add(
                        'id_detail', "Should be at least {$idType->min_length} digits"
                    );
                }

                if (($idType->max_length && strlen($request->id_detail) > $idType->max_length) ) {
                    $validator->errors()->add(
                        'id_detail', "Should be at most {$idType->max_length} digits"
                    );
                }
            }

        });



        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
            // return redirect('/')->withErrors($validator)->withInput();
        }

        $selfRegistration = null;
        \DB::transaction(function () use ($request, &$selfRegistration) {
            \Log::info($request->all());
            $age = Carbon::createFromFormat('Y-m-d', $request->dob)->age;
            $lastNumberOfToday = SelfRegistration::whereDate('created_at', Carbon::today())->orderBy('id', 'desc')->first();
            $lastNumber = $lastNumberOfToday ? $lastNumberOfToday->number : 0;
            $number = $lastNumber + 1;

            $selfRegistration = SelfRegistration::create(
                $request->all()
                + ['age' => $age, 'pass_type' => 'gallery', 'number' => $number]);
        });

        return view('public.selfRegistrations.show_gallery', compact('selfRegistration') );

    }
    // public function update(UpdateSelfRegistrationRequest $request, SelfRegistration $selfRegistration)
    // {
    //     $selfRegistration->update($request->all());

    //     if ($request->input('photo', false)) {
    //         if (! $selfRegistration->photo || $request->input('photo') !== $selfRegistration->photo->file_name) {
    //             if ($selfRegistration->photo) {
    //                 $selfRegistration->photo->delete();
    //             }
    //             $selfRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
    //         }
    //     } elseif ($selfRegistration->photo) {
    //         $selfRegistration->photo->delete();
    //     }

    //     return redirect()->route('public.self-registrations.index');
    // }

    public function show(SelfRegistration $selfRegistration)
    {
       // abort_if(Gate::denies('self_registration_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $selfRegistration->load('id_type', 'visiting_office_category');

        return view('public.selfRegistrations.show', compact('selfRegistration'));
    }

    // public function destroy(SelfRegistration $selfRegistration)
    // {
    //    // abort_if(Gate::denies('self_registration_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $selfRegistration->delete();

    //     return back();
    // }


    // public function storeCKEditorImages(Request $request)
    // {
    //    // abort_if(Gate::denies('self_registration_create') && Gate::denies('self_registration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $model         = new SelfRegistration();
    //     $model->id     = $request->input('crud_id', 0);
    //     $model->exists = true;
    //     $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

    //     return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    // }
}
