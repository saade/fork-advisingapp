<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportStudent;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportStudentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('report_student_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.report-student.index');
    }

    public function create()
    {
        abort_if(Gate::denies('report_student_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.report-student.create');
    }

    public function edit(ReportStudent $reportStudent)
    {
        abort_if(Gate::denies('report_student_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.report-student.edit', compact('reportStudent'));
    }

    public function show(ReportStudent $reportStudent)
    {
        abort_if(Gate::denies('report_student_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.report-student.show', compact('reportStudent'));
    }
}
