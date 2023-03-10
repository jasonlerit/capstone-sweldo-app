<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Utils\ValidationUtil;
use Illuminate\Http\Request;

class AdminLeaveController extends Controller
{
    /**
     * Get all leaves
     */
    public function index(Request $request)
    {
        if ($request->name == null) {
            $leaves = Leave::paginate(10);
        } else {
            $leaves = Leave::with('user')
                ->whereHas('user', function ($query) use ($request) {
                    $query->where('first_name', 'LIKE', "%" . $request->name . "%")
                        ->orWhere('last_name', 'LIKE', "%" . $request->name . "%")
                        ->orWhere('email', 'LIKE', "%" . $request->name . "%");
                })
                ->paginate(10);
        }
        $employeesName = [];
        foreach ($leaves->items() as $item) {
            $employeesName[] = [
                'id' => $item->id,
                'email' => $item->user->email,
                'name' => $item->user->first_name . ' ' . $item->user->last_name,
                'date' => $item->date,
                'status' => $item->status,
            ];
        }
        $leaves = $leaves->toArray();
        $leaves['data'] = $employeesName;
        return response()->json([
            'leaves' => $leaves,
        ]);
    }

    /**
     * Get leave
     */
    public function show(Request $request)
    {
        $id = $request->id;
        $result = ValidationUtil::validateId($id);
        if ($result != null) {
            return response()->json([
                'message' => $result,
                'type' => 'id'
            ], 400);
        }
        $leave = Leave::find($id);
        if ($leave == null) {
            return response()->json([
                'message' => 'Leave not found',
            ], 400);
        }
        return response()->json([
            'leave' => $leave,
        ]);
    }

    /**
     * Approve leave
     */
    public function approve(Request $request)
    {
        $id = $request->id;
        $result = ValidationUtil::validateId($id);
        if ($result != null) {
            return response()->json([
                'message' => $result,
                'type' => 'id'
            ], 400);
        }
        $leave = Leave::find($id);
        if ($leave == null) {
            return response()->json([
                'message' => 'Leave not found',
            ], 400);
        }
        $leave->update([
            'status' => 'approved'
        ]);
        return response()->json([
            'message' => 'Leave has been approved'
        ]);
    }

    /**
     * Decline leave
     */
    public function decline(Request $request)
    {
        $id = $request->id;
        $result = ValidationUtil::validateId($id);
        if ($result != null) {
            return response()->json([
                'message' => $result,
                'type' => 'id'
            ], 400);
        }
        $leave = Leave::find($id);
        if ($leave == null) {
            return response()->json([
                'message' => 'Leave not found',
            ], 400);
        }
        $leave->update([
            'status' => 'declined'
        ]);
        return response()->json([
            'message' => 'Leave has been declined'
        ]);
    }

    /**
     * Delete leave
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $result = ValidationUtil::validateId($id);
        if ($result != null) {
            return response()->json([
                'message' => $result,
                'type' => 'id'
            ], 400);
        }
        $leave = Leave::find($id);
        if ($leave == null) {
            return response()->json([
                'message' => 'Leave not found',
            ], 400);
        }
        $leave->delete();
        return response()->json([
            'message' => 'Leave deleted successfully'
        ]);
    }
}
