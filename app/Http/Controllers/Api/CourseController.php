<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function courseList(){
        $data = Course::select('name', 'thumbnail', 'description', 'price')->get();

        return response()->json([
            'code' => 200,
            'msg' => 'Course List',
            'data' => $data,
        ], 200);
    }
}
