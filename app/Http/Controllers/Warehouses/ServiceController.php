<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function get_services() {
        $services = Service::all();

        return view('services', ['services' => $services]);
    }

    public function add_service(Request $request) {
        $validate_data = $request->validate([
            'name' => ['required', Rule::unique('services', 'name')],
            'description' => ['required'],
            'price'=> ['required'],
        ]);

        $service =  Service::create($validate_data);

        return response()->json(['message' => 'Service created successfully', 'service' => $service]);

    }

    public function delete_service(Request $request) {
        $validate_data = $request->validate([
            'service_id' => ['required'],
        ]);

        $service = Service::find($validate_data['service_id']);

        $service->delete();

        return response()->json(['message' => 'service_id number deleted successfully']);
    }

    public function modify_service(Request $request)
    {
        $validate_data = $request->validate([
            'service_id' => ['required'],
            'name' => ['required'],
            'description' => ['required'],
            'price' => ['required'],
        ]);

        $service = Service::find($validate_data['service_id']);

        unset($validate_data['service_id']);


        $service->update($validate_data);

        return response()->json(['message' => 'service modified successfully']);
    }

    public function get_search_services(Request $request) {
        $service_id = $request->input('service_id', null);
        $name = $request->input('name', null);
        $description = $request->input('description', null);
        $price_min = $request->input('price_min', null);
        $price_max = $request->input('price_max', null);

        $serviceQuery = Service::query();

        if ($service_id) {
            $serviceQuery->where(function ($query) use ($service_id) {
                $query->where('id', 'like', '%' . $service_id . '%');
            });
        }

        if ($name) {
            $serviceQuery->where(function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }

        if ($description) {
            $serviceQuery->where(function ($query) use ($description) {
                $query->where('description', 'like', '%' . $description . '%');
            });
        }

        if ($price_min !== null && $price_max !== null) {
            $serviceQuery->whereBetween('price', [$price_min, $price_max]);
        } elseif ($price_min !== null) {
            $serviceQuery->where('price', '>=', $price_min);
        } elseif ($price_max !== null) {
            $serviceQuery->where('price', '<=', $price_max);
        }

        $services = $serviceQuery->get();

        return response()->json([
            'message' => 'Services returned successfully',
            'services' => $services,
        ]);
    }
}
