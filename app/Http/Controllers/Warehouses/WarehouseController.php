<?php

namespace App\Http\Controllers\Warehouses;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function get_warehouses() {
        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            $warehouse->manager_id = $warehouse->user->id;
        }

        return view('warehouses', ['warehouses' => $warehouses]);
    }

    public function get_warehouse(Warehouse $warehouse) {
        $users = User::all();

        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehousec) {
            $warehousec->manager_id = $warehousec->user->id;
        }

        return view('warehouse', ['warehouse' => $warehouse, 'users' => $users, 'warehouses' => $warehouses]);
    }

    public function add_warehouse(Request $request) {
        $validate_data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'warehouse' => ['required'],
            'location' => ['required'],
            'capacity' => ['required'],
        ]);

        $warehouse =  Warehouse::create($validate_data);
        $warehouse->manager_id = $warehouse->user->id;

        return response()->json(['message' => 'Warehouse created successfully', 'warehouse' => $warehouse]);

    }

    public function delete_warehouse(Request $request) {
        $validate_data = $request->validate([
            'warehouse_id' => ['required'],
        ]);

        $warehouse = Warehouse::find($validate_data['warehouse_id']);

        $warehouse->delete();

        return response()->json(['message' => 'Warehouse deleted successfully']);
    }

    public function modify_warehouse(Request $request)
    {
        $validate_data = $request->validate([
            'warehouse_id' => ['required'],
            'user_id' => ['required', 'exists:users,id'],
            'warehouse' => ['required'],
            'location' => ['required'],
            'capacity' => ['required'],
        ]);

        $warehouse = Warehouse::find($validate_data['warehouse_id']);

        unset($validate_data['warehouse_id']);


        $warehouse->update($validate_data);

        return response()->json(['message' => 'warehouse modified successfully']);
    }

    public function get_search_warehouses(Request $request) {
        $warehouse_id = $request->input('waarehouse_id', null);
        $location = $request->input('location', null);
        $capacity = $request->input('capacity', null);
        $warehouse = $request->input('warehouse', null);
        $manager = $request->input('manager', null);

        $warehouseQuery = Warehouse::query();

        if ($warehouse_id) {
            $warehouseQuery->where(function ($query) use ($warehouse_id) {
                $query->where('id', 'like', '%' . $warehouse_id . '%');
            });
        }

        if ($location) {
            $warehouseQuery->where(function ($query) use ($location) {
                $query->where('location', 'like', '%' . $location . '%');
            });
        }

        if ($capacity) {
            $warehouseQuery->where(function ($query) use ($capacity) {
                $query->where('capacity', 'like', '%' . $capacity . '%');
            });
        }

        if ($warehouse) {
            $warehouseQuery->where(function ($query) use ($warehouse) {
                $query->where('warehouse', 'like', '%' . $warehouse . '%');
            });
        }

        if ($manager) {
            $warehouseQuery->where(function ($query) use ($manager) {
                $query->where('user_id', 'like', '%' . $manager . '%');
            });
        }

        $warehouses = $warehouseQuery->get();

        foreach ($warehouses as $warehouse) {
            $warehouse->manager_id = $warehouse->user->id;
        }

        return response()->json([
            'message' => 'Warehouses returned successfully',
            'warehouses' => $warehouses,
        ]);
    }

    public function select_warehouses(Request $request) {
        $search_term = $request->search_term;
        $warehouses = Warehouse::where('warehouse', 'like', '%' . $search_term . '%')->get();

        return response()->json(['warehouses' => $warehouses]);
    }

    public function add_warehouse_form(Request $request) {
        $validate_data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'warehouse' => ['required'],
            'location' => ['required'],
            'capacity' => ['required'],
        ]);

        $warehouse =  Warehouse::create($validate_data);

        $users = User::all();

        $warehouses = Warehouse::all();

        return response()->json(['message' => 'Warehouse created successfully', 'warehouse' => $warehouse]);

    }

    public function delete_warehouse_form(Request $request) {
        $validate_data = $request->validate([
            'warehouse_id' => ['required'],
        ]);

        $warehouse = Warehouse::find($validate_data['warehouse_id']);

        $warehouse->delete();

        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            $warehouse->manager_id = $warehouse->user->id;
        }

        return response()->json(['message' => 'Warehouse deleted successfully']);
    }
}
