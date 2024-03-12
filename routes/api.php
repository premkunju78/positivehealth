<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AffirmationController;
use App\Http\Controllers\AlliancePartnerController;
use App\Http\Controllers\AssignmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CaseSheetController;
use App\Http\Controllers\DietPlanController;
use App\Http\Controllers\InvestigationController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageGroupController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\TestCategoryController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultantDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountCouponController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\HealthcoachController;
use App\Http\Controllers\WorkflowscheduleController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientGroupController;
use App\Http\Controllers\HtmlquestionnaireController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MarketingheadController;
use App\Http\Controllers\AccountsinchargeController;
use App\Http\Controllers\DiagnosticheadController;
use App\Http\Controllers\MarketingexecutiveController;
use App\Http\Controllers\SamplecollectorController;
use App\Http\Controllers\DataentryuserController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(["middleware" => ["authWebApi"]], function () {
    Route::get('/get_roles', [AppointmentController::class, 'get_roles']);
    Route::get('/get_users/{id}', [AppointmentController::class, 'get_role_users']);
    Route::get('/get_external_consultants', [AppointmentController::class, 'get_external_consultants']);
    Route::get('/checkfor_client', [ClientController::class, 'isClientExist']);
    Route::post('/create_clients',  [ClientController::class, 'store']);
    Route::post('/create_lead',  [LeadController::class, 'website_store']);
    Route::post('/book_slot',  [BookingController::class, 'store']);
    Route::get('/user_info/{id}',  [UserController::class, 'show']);
});

/*auth middleware api passport token*/
Route::middleware('auth:sanctum')->get('/user', [LoginController::class, 'user']);

Route::get('/auth/user', [AuthController::class, 'authenticate']);

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/generate/password',  [UserController::class, 'generatePassword']);
Route::post('/checkuserpassword',  [UserController::class, 'checkuserpassword']);




Route::get('/search/tags', [TagsController::class, 'index']);

Route::post('/lead/generate',  [LeadController::class, 'store']);
Route::post('/lead/register',  [LeadController::class, 'register']);
Route::post('/lead/sendotp',  [LeadController::class, 'sendotp']);
Route::post('/lead/verifyotp',  [LeadController::class, 'verifyotp']);
Route::get('/alliance-partners/bannerlogo',  [AlliancePartnerController::class, 'bannerlogo']);
Route::get('/alliance-partners/programs',  [AlliancePartnerController::class, 'programs']);

//------------------------------- Permission Groups user -----------\\
//------------------------------------------------------------------\\

Route::group(['middleware' => 'auth:sanctum'], function () {

    //---------------------Role Management-------------------------//
    Route::get('/roles',  [RoleController::class, 'index']);
    Route::get('/roles/list',  [RoleController::class, 'list']);
    Route::get('/adminRoles/list',  [RoleController::class, 'adminRoles']);
    Route::get('/roleUsers/list',  [RoleController::class, 'roleUserList']);
    Route::get('/roles/{id}/edit',  [RoleController::class, 'edit']);
    Route::post('/roles',  [RoleController::class, 'store']);
    Route::put('/roles/{id}',  [RoleController::class, 'update']);
    Route::delete('/roles/{id}',  [RoleController::class, 'destroy']);
    Route::get('/permissions',  [PermissionController::class, 'list']);


    //---------------------Account Management-------------------------//
    Route::post('/account/general',  [AccountController::class, 'updateGeneral']);
    Route::post('/account/information', [AccountController::class, 'updateInformation']);
    Route::delete('/account/certificate/{index}', [AccountController::class, 'removeCertificate']);
    Route::post('/account/bio',  [AccountController::class, 'updateBio']);



    //---------------------Consultant (user) Management-------------------------//
    Route::post('/refer/consultant',  [UserController::class, 'refferConsultant']);
    Route::get('/consultants',  [UserController::class, 'index']);
    Route::post('/consultants/removeconsultant',  [UserController::class, 'removeconsultant']);
    Route::get('/consultants/dashboard/counts',  [DashboardController::class, 'consultantCounts']);
    Route::get('/consultants/specialized',  [UserController::class, 'specialized']);
    Route::post('/consultants/allianassignment',  [UserController::class, 'allianassignment']);
    Route::post('/consultants',  [UserController::class, 'store']);
    Route::get('/payment/consultant',  [UserController::class, 'paymentDetails']);
    Route::get('/consultant/{id}',  [UserController::class, 'show']);
    Route::get('/consultants/{id}/edit',  [UserController::class, 'edit']);
    Route::get('/consultants/list',  [UserController::class, 'list']);
    Route::post('/consultants/{id}',  [UserController::class, 'update']);
    Route::get('/consultant/client/list',  [UserController::class, 'clientList']);
    Route::get('/consultants/{id}/videos',  [UserController::class, 'videos']);
    Route::put('/consultants/{id}/upgrade',  [UserController::class, 'upgrade']);
    
    Route::delete('/consultant/{id}',  [UserController::class, 'destroy']);
    Route::get('/incharges/list',  [UserController::class, 'inchargelist']);



    //---------------------Client Management-------------------------//
    Route::post('/client/assignment',  [AssignmentController::class, 'storeUserClientAssignment']);
    Route::post('/client/hcassignment',  [ClientController::class, 'storehcassignment']);
    Route::post('/client/dhassignment',  [ClientController::class, 'storedhassignment']);
    Route::post('/client/inchargeassignment',  [ClientController::class, 'storeinchargeassignment']);
    Route::get('/client/upcomingAppointment',  [DashboardController::class, 'upcomingAppointment']);
    Route::post('/client/referfriend',  [ClientController::class, 'referfriend']);
    Route::post('/client/updatestatus',  [ClientController::class, 'updatestatus']);
    Route::post('/client/updatetrainingstatus',  [ClientController::class, 'updatetrainingstatus']);
    Route::post('/client/update_programstatus',  [ClientController::class, 'update_programstatus']);
    Route::get('/client/{id}/users',  [AssignmentController::class, 'clientConsultants']);
    Route::get('/client/programlist/{id}',  [ClientController::class, 'programlist']);
    Route::post('/client/update_userprogram',  [ClientController::class, 'update_userprogram']);
    Route::get('/client/instructions',  [ClientController::class, 'instructions']);
    Route::get('/clients',  [ClientController::class, 'index']);
    Route::post('/clients',  [ClientController::class, 'store']);
    Route::get('/clients/{id}/edit',  [ClientController::class, 'edit']);
    Route::delete('/clients/{id}',  [ClientController::class, 'destroy']);
    Route::put('/clients/{id}',  [ClientController::class, 'update']);
    Route::get('/client/list',  [ClientController::class, 'list']);
    Route::get('/client/{id}',  [ClientController::class, 'show']);

    
    Route::get('clientgroup/list', [ClientGroupController::class, 'list']);
    Route::post('clientgroup/clientassignment', [ClientGroupController::class, 'clientassignment']);
    Route::get('clientgroup/assignedusers', [ClientGroupController::class, 'assignedusers']);
    Route::get('/client/dhlist/{id}',  [ClientController::class, 'dhlist']);

    Route::apiResource('clientGroup', ClientGroupController::class);



    //---------------------Coordinator Management-------------------------//
    Route::post('/coordinators/assignment',  [AssignmentController::class, 'storeUserClientAssignment']);
    Route::get('/coordinators/list',  [CoordinatorController::class, 'list']);
    Route::get('/coordinators',  [CoordinatorController::class, 'index']);
    Route::post('/coordinators',  [CoordinatorController::class, 'store']);
    Route::post('/coordinator/call',  [CoordinatorController::class, 'call']);
    Route::get('/coordinators/{id}/edit',  [CoordinatorController::class, 'edit']);
    Route::get('/coordinators/{id}',  [CoordinatorController::class, 'show']);
    Route::post('/coordinators/{id}',  [CoordinatorController::class, 'update']);
    Route::get('/coordinators/alliancepartners/{id}',  [CoordinatorController::class, 'alliancepartners']);
    Route::post('/coordinators/alliancepartners/{id}',  [CoordinatorController::class, 'storeCoodAllianceAssignment']);
    Route::delete('/coordinators/alliancepartner/{id}',  [CoordinatorController::class, 'deleteMapping']);
    Route::get('/coordinators/getallianceCood/{id}',  [CoordinatorController::class, 'getallianceCood']);
    Route::delete('/coordinators/{id}',  [CoordinatorController::class, 'destroy']);


    Route::get('/mh/list',  [MarketingheadController::class, 'list']);
    Route::get('/mh',  [MarketingheadController::class, 'index']);
    Route::post('/mh',  [MarketingheadController::class, 'store']);
    Route::get('/mh/{id}',  [MarketingheadController::class, 'show']);
    Route::post('/mh/{id}',  [MarketingheadController::class, 'update']);
    Route::get('/mh/{id}/edit',  [MarketingheadController::class, 'edit']);
    Route::delete('/mh/{id}',  [MarketingheadController::class, 'destroy']);

    Route::get('/dh/list',  [DiagnosticheadController::class, 'list']);
    Route::get('/dh',  [DiagnosticheadController::class, 'index']);
    Route::post('/dh',  [DiagnosticheadController::class, 'store']);
    Route::get('/dh/{id}',  [DiagnosticheadController::class, 'show']);
    Route::post('/dh/{id}',  [DiagnosticheadController::class, 'update']);
    Route::get('/dh/{id}/edit',  [DiagnosticheadController::class, 'edit']);
    Route::delete('/dh/{id}',  [DiagnosticheadController::class, 'destroy']);


    Route::get('/samplecollector/list',  [SamplecollectorController::class, 'list']);
    Route::get('/samplecollector',  [SamplecollectorController::class, 'index']);
    Route::post('/samplecollector',  [SamplecollectorController::class, 'store']);
    Route::get('/samplecollector/{id}',  [SamplecollectorController::class, 'show']);
    Route::post('/samplecollector/{id}',  [SamplecollectorController::class, 'update']);
    Route::get('/samplecollector/{id}/edit',  [SamplecollectorController::class, 'edit']);
    Route::delete('/samplecollector/{id}',  [SamplecollectorController::class, 'destroy']);

    Route::get('/dataentryuser/list',  [DataentryuserController::class, 'list']);
    Route::get('/dataentryuser',  [DataentryuserController::class, 'index']);
    Route::post('/dataentryuser',  [DataentryuserController::class, 'store']);
    Route::get('/dataentryuser/{id}',  [DataentryuserController::class, 'show']);
    Route::post('/dataentryuser/{id}',  [DataentryuserController::class, 'update']);
    Route::get('/dataentryuser/{id}/edit',  [DataentryuserController::class, 'edit']);
    Route::delete('/dataentryuser/{id}',  [DataentryuserController::class, 'destroy']);


    Route::get('/me/list',  [MarketingexecutiveController::class, 'list']);
    Route::get('/me',  [MarketingexecutiveController::class, 'index']);
    Route::post('/me',  [MarketingexecutiveController::class, 'store']);
    Route::get('/me/{id}',  [MarketingexecutiveController::class, 'show']);
    Route::post('/me/{id}',  [MarketingexecutiveController::class, 'update']);
    Route::get('/me/{id}/edit',  [MarketingexecutiveController::class, 'edit']);
    Route::delete('/me/{id}',  [MarketingexecutiveController::class, 'destroy']);

    Route::get('/accountsincharge/list',  [AccountsinchargeController::class, 'list']);
    Route::get('/accountsincharge',  [AccountsinchargeController::class, 'index']);
    Route::post('/accountsincharge',  [AccountsinchargeController::class, 'store']);
    Route::get('/accountsincharge/{id}',  [AccountsinchargeController::class, 'show']);
    Route::post('/accountsincharge/{id}',  [AccountsinchargeController::class, 'update']);
    Route::get('/accountsincharge/{id}/edit',  [AccountsinchargeController::class, 'edit']);
    Route::delete('/accountsincharge/{id}',  [AccountsinchargeController::class, 'destroy']);

    Route::get('/workflowschedules', [WorkflowscheduleController::class, 'list']);
    Route::post('/workflowschedules', [WorkflowscheduleController::class, 'store']);
    Route::get('/workflowschedules/events', [WorkflowscheduleController::class, 'events']);
    Route::post('/workflowschedules/updatestatus', [WorkflowscheduleController::class, 'updatestatus']);
    Route::post('/workflowschedules/assign_consultant', [WorkflowscheduleController::class, 'assign_consultant']);
    Route::post('/workflowschedules/reschedule', [WorkflowscheduleController::class, 'reschedule']);
    Route::post('/workflowschedules/message/{id}', [WorkflowscheduleController::class, 'message']);
    Route::get('/workflowschedules/messages/{id}', [WorkflowscheduleController::class, 'getMessageList']);
    Route::post('/workflow/call/now', [WorkflowscheduleController::class, 'callNow']);
    Route::post('/workflow/meeting/link/{id}',  [WorkflowscheduleController::class, 'createLink']);




    Route::get('/enquiries', [EnquiryController::class, 'list']);
    Route::post('/enquiries', [EnquiryController::class, 'store']);
    Route::post('/enquiries/updatestatus', [EnquiryController::class, 'updatestatus']);
    Route::post('/enquiries/update', [EnquiryController::class, 'update']);
    Route::post('/enquiries/hcassignment', [EnquiryController::class, 'hcassignment']);


    //---------------------Healthcoach Management-------------------------//
    Route::post('/healthcoaches/assignment',  [AssignmentController::class, 'storeUserClientAssignment']);
    Route::post('/healthcoach/leadassignment',  [HealthcoachController::class, 'storeLeadAssignment']);
    Route::get('/healthcoaches',  [HealthcoachController::class, 'index']);
    Route::post('/healthcoaches',  [HealthcoachController::class, 'store']);
    Route::get('/healthcoaches/list',  [HealthcoachController::class, 'list']);
    Route::get('/healthcoaches/{id}/edit',  [HealthcoachController::class, 'edit']);
    Route::get('/healthcoaches/{id}',  [HealthcoachController::class, 'show']);
    Route::post('/healthcoaches/{id}',  [HealthcoachController::class, 'update']);
    Route::delete('/healthcoach/removeLead/{id}',  [HealthcoachController::class, 'removeLead']);
    Route::get('/healthcoach/{id}/leads',  [HealthcoachController::class, 'leads']);
    Route::delete('/healthcoach/{id}',  [HealthcoachController::class, 'destroy']);

    //---------------------Alliance Partner Management-------------------------//
    Route::get('/alliance-partners',  [AlliancePartnerController::class, 'index']);

    Route::post('/alliance-partners/consultant',  [AlliancePartnerController::class, 'saveMapping']);

    Route::delete('/alliance-partners/consultant/{id}',  [AlliancePartnerController::class, 'deleteMapping']);

    Route::post('/alliance-partners',  [AlliancePartnerController::class, 'store']);
    Route::get('/alliance-partners/partnerTypes',  [AlliancePartnerController::class, 'partnerTypes']);
    Route::get('/alliance-partners/{id}',  [AlliancePartnerController::class, 'show']);
    Route::get('/alliance-partners/{id}/edit',  [AlliancePartnerController::class, 'edit']);
    Route::post('/alliance-partners/{id}',  [AlliancePartnerController::class, 'update']);
    Route::get('/alliance-partner/list',  [AlliancePartnerController::class, 'list']);
    Route::get('/alliance-partner/dashboard',  [AlliancePartnerController::class, 'dashboard']);
    Route::delete('/alliance-partner/{id}',  [AlliancePartnerController::class, 'destroy']);
    Route::post('/alliance-partner/approve/{id}',  [AlliancePartnerController::class, 'approve']);


    //--------------------- Videos Management------------------------------------//
    Route::get('/videos',  [VideoController::class, 'index']);
    Route::get('/video/list',  [VideoController::class, 'list']);
    Route::get('/videostitlelist',  [VideoController::class, 'videostitlelist']);


    //--------------------- Video Meeting Management------------------------------------//
    Route::post('/meeting/link',  [MeetingController::class, 'createLink']);


    //--------------------- Group Management------------------------------------//
    Route::get('/groups',  [GroupController::class, 'index']);
    Route::get('/group/list',  [GroupController::class, 'list']);
    Route::get('/groups/{group}/videos',  [GroupController::class, 'videos']);
    Route::post('/groups',  [GroupController::class, 'store']);
    Route::put('/groups/{group}',  [GroupController::class, 'update']);
    Route::delete('/groups/{url}',  [GroupController::class, 'destroy']);
    Route::delete('/groups/{group}/videos/{video}',  [GroupController::class, 'removeVideo']);


    //--------------------- Assignement Management------------------------------------//
    Route::post('/video/assignment',  [AssignmentController::class, 'store']);
    Route::get('/video/{id}/users',  [AssignmentController::class, 'users']);
    Route::get('/groups/{group}/users',  [AssignmentController::class, 'groupUsers']);
    Route::post('/group/assignment',  [AssignmentController::class, 'storeGroupAssignment']);
    Route::post('/user/assignment',  [AssignmentController::class, 'storeUserVideoAssignment']);
    Route::delete('/assignment/{id}/user',  [AssignmentController::class, 'destroy']);
    Route::post('/questionnaire/assignment',  [AssignmentController::class, 'storeUserQuestionnaireAssignment']);
    Route::get('/questionnaire/{id}/users',  [AssignmentController::class, 'questionnaireUsers']);
    Route::delete('/assignment/{video_id}/user/{user_id}',  [AssignmentController::class, 'removeVideo']);


    //--------------------- Questionnaries Management------------------------------------//
    Route::get('/questionnaire',  [QuestionnaireController::class, 'index']);
    Route::get('/questionnaire/assessmenthistory',  [QuestionnaireController::class, 'assessmenthistory']);
    Route::post('/questionnaire',  [QuestionnaireController::class, 'store']);
    Route::delete('/questionnaire/{id}',  [QuestionnaireController::class, 'destroy']);
    Route::post('/questionnaire/result',  [QuestionnaireController::class, 'storeResult']);
    Route::post('/questionnaire/{id}',  [QuestionnaireController::class, 'update']);
    Route::get('/questionnaire/{id}',  [QuestionnaireController::class, 'show']);

    Route::put('/questionnaire/result/{id}',  [QuestionnaireController::class, 'updateResult']);
    Route::get('/questionnaire/user/{id}',  [QuestionnaireController::class, 'getResult']);

    Route::get('/htmlquestionnaire',  [HtmlquestionnaireController::class, 'index']);
    Route::get('/htmlquestionnaire/list',  [HtmlquestionnaireController::class, 'list']);
    Route::post('/htmlquestionnaire/assignClient',  [HtmlquestionnaireController::class, 'assignClient']);


    Route::get('/feedback/list',  [FeedbackController::class, 'list']);
    Route::post('/feedback/store',  [FeedbackController::class, 'store']);







    //--------------------- Meeting And Availability Management------------------------------------//
    Route::apiResource('availability', AvailabilityController::class);
    Route::post('/availabilities/list',  [AvailabilityController::class, 'list']);
    Route::apiResource('booking', BookingController::class);
    Route::get('/bookings/list', [BookingController::class, 'schedules']);
    Route::post('/bookings/updatestatus', [BookingController::class, 'updatestatus']);
    Route::post('/schedules', [BookingController::class, 'list']);
    Route::get('/meetings/list', [BookingController::class, 'todayMeetings']);
    Route::post('/call/now', [MeetingController::class, 'callNow']);



    //--------------------- Package and Package Group Management------------------------------------//

    Route::post('/program/freeSession',  [PackageController::class, 'bookFreeSession']);
    Route::post('/program/callbackreq',  [PackageController::class, 'callBackReq']);
    Route::get('/packages/list',  [PackageController::class, 'list']);
    Route::get('/packages/details',  [PackageController::class, 'details']);
    Route::get('/packages/me',  [PackageController::class, 'myPackages']);
    Route::get('/package/alliance-partner/list', [PackageController::class, 'partners']);
    Route::get('/package/users/list', [PackageController::class, 'users']);
    Route::post('/package/assignment',  [AssignmentController::class, 'storeUserPackageAssignment']);
    Route::post('/package/customassignment',  [AssignmentController::class, 'storeCustomUserPackageAssignment']);
    Route::get('/package/getPrice',  [PackageController::class, 'getPrice']);
    Route::get('/package/planslist/{id}',  [PackageController::class, 'planslist']);
    Route::get('/package/{id}/users',  [AssignmentController::class, 'packageUsers']);
    Route::post('/package/{id}/enroll',  [PackageController::class, 'enroll']);
    Route::post('/package/{id}',  [PackageController::class, 'update']);
    Route::apiResource('package', PackageController::class);
    Route::get('/packageGroup/{id}/packages',  [AssignmentController::class, 'groupPackages']);
    Route::apiResource('packageGroup', PackageGroupController::class);
    Route::get('/packageGroup/alliance-partner/list', [PackageGroupController::class, 'partners']);
    Route::post('/packageGroup/assignment',  [AssignmentController::class, 'storeUserPackageGroupAssignment']);
    Route::get('/packageGroup/{id}/users',  [AssignmentController::class, 'packageGroupUsers']);
    Route::delete('/packageGroup/mapping/{id}',  [PackageGroupController::class, 'removeMapping']);



    //--------------------- Diet Plan Management------------------------------------//
    Route::apiResource('dietPlan', DietPlanController::class);
    Route::post('/dietPlan/assignment',  [AssignmentController::class, 'storeUserPlanAssignment']);
    Route::post('/dietPlan/{id}',  [DietPlanController::class, 'update']);
    Route::get('/dietPlan/{id}/users',  [AssignmentController::class, 'dietPlanUsers']);


    //--------------------- Recipe Management------------------------------------//
    Route::apiResource('recipe', RecipeController::class);
    Route::post('/recipe/assignment',  [AssignmentController::class, 'storeUserRecipeAssignment']);
    Route::post('/recipe/{id}',  [RecipeController::class, 'update']);
    Route::get('/recipe/{id}/users',  [AssignmentController::class, 'recipeUsers']);


    Route::get('/tags',  [TagsController::class, 'index']);



    Route::get('/caseSheet/client/{id}',  [CaseSheetController::class, 'index']);
    Route::post('/caseSheet',  [CaseSheetController::class, 'store']);


    Route::get('/notification/{id}',  [NotificationController::class, 'index']);



    //--------------------- Investigation Management------------------------------------//
    Route::get('/investigation/test/list',  [InvestigationController::class, 'test']);
    Route::post('/investigation/storeconfig',  [InvestigationController::class, 'storeInvestConfig']);

    Route::post('/investigation/{id}/upload',  [InvestigationController::class, 'upload']);
    Route::put('/investigation/{id}/upload',  [InvestigationController::class, 'removeFile']);
    Route::apiResource('investigation', InvestigationController::class);
    Route::post('/investigation/updateTestData',  [InvestigationController::class, 'updateTestData']);

    //--------------------- Test Category Management------------------------------------//

    Route::get('testCategory/list', [TestCategoryController::class, 'list']);
    Route::apiResource('testCategory', TestCategoryController::class);
    Route::apiResource('testItem', TestController::class);


    Route::post('razorpay/order', [RazorpayController::class, 'order'])->name('order');
    //Route::post('razorpaypayment', [RazorpayController::class, 'payment'])->name('payment');
    Route::post('razorpay/payment', [RazorpayController::class, 'payment'])->name('payment');


    //--------------------- Reminder Management------------------------------------//
    Route::apiResource('reminder', ReminderController::class);
    Route::post('/reminder/updatestatus', [ReminderController::class, 'updatestatus']);


    //--------------------- Affirmation Management------------------------------------//

    Route::get('affirmation/today', [AffirmationController::class, 'show']);
    Route::apiResource('affirmation', AffirmationController::class);


    //--------------------- Leads Management------------------------------------//
    Route::get('/lead/list',  [LeadController::class, 'list']);
    Route::post('/lead/assignhc',  [LeadController::class, 'assignhc']);
    Route::post('/lead/assignprogram',  [LeadController::class, 'assignprogram']);
    Route::get('/lead/getLeadStatus',  [LeadController::class, 'getLeadStatus']);
    Route::post('/lead/updatestatus',  [LeadController::class, 'updatestatus']);
    Route::get('/lead/getQueriesList/{id}',  [LeadController::class, 'getQueriesList']);
    Route::post('/lead/storequery',  [LeadController::class, 'storequery']);
    Route::post('/lead/storeRemark',  [LeadController::class, 'storeRemark']);
    Route::get('/lead/checkCreateLead',  [LeadController::class, 'checkCreateLead']);

    Route::apiResource('lead', LeadController::class);



    //--------------------- Prescriptions------------------------------------//
    Route::apiResource('prescription', PrescriptionController::class);


    //--------------------- Discount Coupon------------------------------------//
    Route::get('/discountCoupon/list',  [DiscountCouponController::class, 'list']);
    Route::apiResource('discountCoupon', DiscountCouponController::class);
    Route::post('/discountCoupon/assignment',  [AssignmentController::class, 'storeDiscountCouponAssignment']);

    Route::get('/coupon', [DiscountCouponController::class, 'validateCouponCode']);
    Route::get('/chat/rooms', [ChatController::class, 'rooms']);
    Route::get('/chat/messages/{id}', [ChatController::class, 'messages']);
    Route::post('/chat/sendmessage', [ChatController::class, 'sendmessage']);
    Route::post('/alliancepartner/callcoordinator', [AlliancePartnerController::class, 'callcoordinator']);
});
Route::get('/users/roles',  [UserController::class, 'get_users_and_roles']);
Route::post('/enquiries/call_init',  [EnquiryController::class, 'call']);
Route::get('/lead/program/{id}',  [LeadController::class, 'assignedprogram']);
Route::post('razorpay/leadorder', [RazorpayController::class, 'leadorder'])->name('leadorder');
Route::post('razorpay/leadpayment', [RazorpayController::class, 'leadpayment'])->name('leadpayment');
Route::get('/checkcoupon', [DiscountCouponController::class, 'validateCouponCode']);
Route::post('/submit_html_questinnaires', [HtmlquestionnaireController::class, 'submit_html_questinnaires']);
