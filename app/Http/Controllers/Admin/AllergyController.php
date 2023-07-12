<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Allergy;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $allergies = Allergy::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $allergies = new Allergy();
        }

        $allergies = $allergies->orderBy('name')->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.allergy.index', compact('allergies', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:allergies'
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        $allergy = new Allergy();
        $allergy->name = $request->name[array_search('en', $request->lang)];
        $allergy->save();

        $data = [];
        foreach($request->lang as $index=>$key)
        {
            if($request->name[$index] && $key != 'en')
            {
                array_push($data, Array(
                    'translationable_type'  => 'App\Model\Allergy',
                    'translationable_id'    => $allergy->id,
                    'locale'                => $key,
                    'key'                   => 'name',
                    'value'                 => $request->name[$index],
                ));
            }
        }
        if(count($data))
        {
            Translation::insert($data);
        }

        Toastr::success(translate('Allergy added successfully!'));
        return back();
    }

    public function edit($id)
    {
        $allergy = Allergy::withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.allergy.edit', compact('allergy'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:allergies,name,' . $id,
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error('Name is too long!');
                return back();
            }
        }

        $allergy = Allergy::find($id);
        $allergy->name = $request->name[array_search('en', $request->lang)];
        $allergy->save();

        foreach($request->lang as $index=>$key)
        {
            if($request->name[$index] && $key != 'en')
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Model\Allergy',
                        'translationable_id'    => $allergy->id,
                        'locale'                => $key,
                        'key'                   => 'name'],
                    ['value'                 => $request->name[$index]]
                );
            }
        }
        Toastr::success(translate('Allergy updated successfully!'));
        return back();
    }

    public function delete(Request $request)
    {
        $allergy = Allergy::find($request->id);
        $allergy->delete();
        Toastr::success(translate('Allergy removed!'));
        return back();
    }
}
