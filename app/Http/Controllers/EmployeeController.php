<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Response\BaseResponse;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Employee::with('division');

            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->has('division_id')) {
                $query->where('division_id', $request->division_id);
            }

            $employees = $query->paginate(10);

            return BaseResponse::successWithPagination('Data employees berhasil diambil', $employees->items(), [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
                'next_page_url' => $employees->nextPageUrl(),
                'previous_page_url' => $employees->previousPageUrl(),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return BaseResponse::error($th->getMessage(), 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'division' => 'required|exists:divisions,id',
                'position' => 'required|string|max:255',
            ]);

            $image = $request->file('image');
            $path =  $image->getClientOriginalName();
            Storage::disk('public')->put("/images/".$path, file_get_contents($image));

            Employee::create([
                'image' => $path,
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'division_id' => $validated['division'],
                'position' => $validated['position'],
            ]);

            return BaseResponse::actionSuccess('Data employee berhasil dibuat');
        } catch (\Throwable $th) {
            //throw $th;
            return BaseResponse::error($th->getMessage(), 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'division' => 'required|exists:divisions,id',
                'position' => 'required|string|max:255',
            ]);

            $employee = Employee::findOrFail($id);

            // Cek dan update gambar jika ada
            if ($request->hasFile('image')) {
                // Hapus gambar lama
                if ($employee->image) {
                    Storage::delete($employee->image);
                }

                $image = $request->file('image');
                $path =  $image->getClientOriginalName();

                $employee->image =  $path;
                Storage::disk('public')->put("/images/".$path , file_get_contents($image));
    
            }

            // Update data employee
            $employee->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'division_id' => $validated['division'],
                'position' => $validated['position'],
            ]);

            return BaseResponse::actionSuccess('Data employee berhasil diperbarui');
        } catch (\Throwable $th) {
            //throw $th;
            return BaseResponse::error($th->getMessage(), 500);
        }
    }
    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            // Hapus gambar jika ada
            if ($employee->image) {
                Storage::delete($employee->image);
            }

            // Hapus data employee
            $employee->delete();

            return BaseResponse::actionSuccess('Data employee berhasil dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            return BaseResponse::error($th->getMessage(), 500);
        }
    }
}
