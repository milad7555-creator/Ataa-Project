<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrphanRequest;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\StoreUniversityRequest;
use App\Models\Beneficiary;
use App\Models\Orphan;
use App\Models\RequestModel;
use App\Models\Patient;
use App\Models\SchoolStudent;
use App\Models\UniversityStudent;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function storePatientRequest(StorePatientRequest $request)
    {
        $user = Auth::user();

        // 1) تحديد بيانات المستفيد
        if ($request->is_self) {

            // المستفيد هو المستخدم نفسه
            $beneficiaryData = [
                'full_name'        => $request->full_name,
                'address'          => $request->address,
                'email'            => $user->email,
                'phone'            => $user->phone,
                'personal_picture' => null, // لا يسمح برفع صورة شخصية
            ];
        } else {

            // المستفيد شخص آخر
            $beneficiaryData = [
                'full_name'        => $request->full_name,
                'address'          => $request->address,
                'email'            => $request->email,
                'phone'            => $request->phone,
                'personal_picture' => null, // لا يوجد رفع صورة شخصية
            ];
        }

        // 2) إنشاء أو تحديث المستفيد حسب الاسم
        $beneficiary = Beneficiary::updateOrCreate(
            ['full_name' => $request->full_name],
            $beneficiaryData
        );

        // 3) إنشاء الطلب الأساسي
        $requestModel = RequestModel::create([
            'user_id'        => $user->id,
            'beneficiary_id' => $beneficiary->id,
            'request_type'   => 'patient',
            'status'         => 'pending',
            'description'    => $request->description,
        ]);

        // 4) رفع الملفات
        $medicalReportPath = $request->file('medical_report')
            ->store('medical_reports', 'public');

        $nationalIdPath = $request->file('national_id')
            ->store('national_ids', 'public');

        // 5) إنشاء سجل المريض
        $patient = Patient::create([
            'request_id'      => $requestModel->id,
            'required_amount' => $request->required_amount,
            'medical_report'  => $medicalReportPath,
            'national_id'     => $nationalIdPath,
        ]);

        return response()->json([
            'message'     => 'Patient request created successfully',
            'beneficiary' => $beneficiary,
            'request'     => $requestModel,
            'patient'     => $patient,
        ], 201);
    }


    public function storeOrphanRequest(StoreOrphanRequest $request)
    {
        $user = Auth::user();

        // 1) تجهيز بيانات المستفيد
        $beneficiaryData = [
            'full_name'        => $request->full_name,
            'address'          => $request->address,
            'email'            => $user->email, // دائماً من حسابه
            'phone'            => $request->phone ?? $user->phone, // إذا أضاف رقم جديد نستخدمه
            'personal_picture' => null,
        ];

        // 2) إنشاء أو تحديث المستفيد حسب الاسم
        $beneficiary = Beneficiary::updateOrCreate(
            ['full_name' => $request->full_name],
            $beneficiaryData
        );

        // 3) إنشاء الطلب الأساسي
        $requestModel = RequestModel::create([
            'user_id'        => $user->id,
            'beneficiary_id' => $beneficiary->id,
            'request_type'   => 'orphan',
            'status'         => 'pending',
            'description'    => $request->description,
        ]);

        // 4) رفع الملفات
        $familyBookletPath = $request->file('family_booklet')
            ->store('family_booklets', 'public');

        $deathCertificatePath = $request->file('father_death_certificate')
            ->store('death_certificates', 'public');

        // 5) required_amount = 0 دائماً
        $orphan = Orphan::create([
            'request_id'               => $requestModel->id,
            'family_booklet'           => $familyBookletPath,
            'father_death_certificate' => $deathCertificatePath,
            'required_amount'          => 0,
        ]);

        return response()->json([
            'message'     => 'Orphan request created successfully',
            'beneficiary' => $beneficiary,
            'request'     => $requestModel,
            'orphan'      => $orphan,
        ], 201);
    }



    public function storeSchoolRequest(StoreSchoolRequest $request)
    {
        $user = Auth::user();

        // 1) بيانات المستفيد — دائماً من اليوزر
        $beneficiaryData = [
            'full_name'        => $request->full_name,
            'address'          => $request->address,
            'email'            => $user->email, // دائماً من حسابه
            'phone'            => $user->phone, // دائماً من حسابه
            'personal_picture' => null,
        ];

        // 2) إنشاء أو تحديث المستفيد حسب الاسم
        $beneficiary = Beneficiary::updateOrCreate(
            ['full_name' => $request->full_name],
            $beneficiaryData
        );

        // 3) إنشاء الطلب الأساسي
        $requestModel = RequestModel::create([
            'user_id'        => $user->id,
            'beneficiary_id' => $beneficiary->id,
            'request_type'   => 'school',
            'status'         => 'pending',
            'description'    => $request->description,
        ]);

        // 4) رفع ملف دفتر العائلة
        $familyBookPhotoPath = $request->file('family_book_photo')
            ->store('family_book_photos', 'public');

        // 5) إنشاء سجل الطالب المدرسي
        $school = SchoolStudent::create([
            'request_id'        => $requestModel->id,
            'academic_grade'    => $request->academic_grade,
            'school_name'       => $request->school_name,
            'family_book_photo' => $familyBookPhotoPath,
            'required_amount'   => 0, //      
        ]);

        return response()->json([
            'message'     => 'School student request created successfully',
            'beneficiary' => $beneficiary,
            'request'     => $requestModel,
            'school'      => $school,
        ], 201);
    }



    public function storeUniversityRequest(StoreUniversityRequest $request)
    {
        $user = Auth::user();

        // 1) بيانات المستفيد — دائماً من اليوزر
        $beneficiaryData = [
            'full_name'        => $request->full_name,
            'address'          => $request->address,
            'email'            => $user->email, // من حساب اليوزر
            'phone'            => $user->phone, // من حساب اليوزر
            'personal_picture' => null,
        ];

        // 2) إنشاء أو تحديث المستفيد حسب الاسم
        $beneficiary = Beneficiary::updateOrCreate(
            ['full_name' => $request->full_name],
            $beneficiaryData
        );

        // 3) إنشاء الطلب الأساسي
        $requestModel = RequestModel::create([
            'user_id'        => $user->id,
            'beneficiary_id' => $beneficiary->id,
            'request_type'   => 'university',
            'status'         => 'pending',
            'description'    => $request->description,
        ]);

        // 4) رفع صورة بطاقة الجامعة
        $universityIdPath = $request->file('university_id_photo')
            ->store('university_id_photos', 'public');

        // 5) إنشاء سجل الطالب الجامعي
        $universityStudent = UniversityStudent::create([
            'request_id'          => $requestModel->id,
            'academic_year'       => $request->academic_year,
            'university_id_photo' => $universityIdPath,
            'support_type'        => $request->support_type,
            'required_amount'     => 0, // دائماً صفر
        ]);

        return response()->json([
            'message'           => 'University student request created successfully',
            'beneficiary'       => $beneficiary,
            'request'           => $requestModel,
            'universityStudent' => $universityStudent,
        ], 201);
    }
}
