<?php


use App\Http\Controllers\Api\ClubCommitteeController;
use App\Http\Controllers\Api\ClubMediaController;
use App\Http\Controllers\Api\ClubModeratorController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\CreateMailListController;
use App\Http\Controllers\Api\FundEventController;
use App\Http\Controllers\APi\GuidelineController;
use App\Http\Controllers\Api\InstituteController;
use App\Http\Controllers\Api\NewsletterMailController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\ThanaController;
use App\Http\Controllers\Api\AlumniController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CCCFaqController;
use App\Http\Controllers\Api\CccNewsController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\JobBlogController;
use App\Http\Controllers\Api\JobPostController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AboutCCCController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\MagazineController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\TrainingController;
use App\Http\Controllers\Api\WhoWeAreController;
use App\Http\Controllers\Api\WorkshopController;
use App\Http\Controllers\Api\AdminauthController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\JobSeekerController;
use App\Http\Controllers\Api\AlumniAuthController;
use App\Http\Controllers\Api\AudioVideoController;
use App\Http\Controllers\Api\CareerTipsController;
use App\Http\Controllers\Api\CccUpdatesController;
use App\Http\Controllers\Api\ClubController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\MajorMinorController;
use App\Http\Controllers\Api\MenuActionController;
use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\JobCategoryController;
use App\Http\Controllers\Api\NoticeEventController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\CompanyDetailController;
use App\Http\Controllers\Api\JobPostSearchController;
use App\Http\Controllers\Api\JobSubCategoryController;
use App\Http\Controllers\Api\StudentWelfareController;
use App\Http\Controllers\Api\UserMenuActionController;
use App\Http\Controllers\Api\JobInterestedAreaController;
use App\Http\Controllers\Api\CoCurricularActivityController;
use App\Http\Controllers\Api\ConnectionController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\ProfessionalCertificationController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\JobProfileController;
use App\Http\Controllers\Api\ResumeController;
use App\Http\Controllers\Api\NewsFeedController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\GroupMemberController;
use App\Http\Controllers\Api\GroupNewsFeedController;
use App\Http\Controllers\Api\CareerApplicationController;
use App\Http\Controllers\Api\JobPrefferedAreaController;
use App\Http\Controllers\Api\EmploymentHistoryController;
use App\Http\Controllers\Api\SpecializationController;
use App\Http\Controllers\Api\LanguageProficiencyController;
use App\Http\Controllers\Api\ReferenceControler;
use App\Http\Controllers\Api\OrganizationTypeController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\ChapterMemberController;
use App\Http\Controllers\Api\ClassMemoriesController;
use App\Http\Controllers\Api\ClassMemoriesMemberController;
use App\Http\Controllers\Api\ClassMemoriesNewsFeedController;
use App\Http\Controllers\Api\EndorsementController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventMemberController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\OffensiveWordController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReportTypeController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TrainingSummaryController;
use App\Http\Controllers\Api\ResumeFileController;






header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, PATCH, DELETE');
header('Access-Control-Allow-Headers: Content-Type') ;





















Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// sanctum authentication (User)
Route::post('/auth/register',[AuthController::class,'createUser']);
Route::post('/auth/login',[AuthController::class,'loginUser']);

// sanctum authentication (Admin)
Route::post('/adminauth/register',[AdminauthController::class,'createAdmin']);
Route::post('/adminauth/login',[AdminauthController::class,'loginAdmin']);
Route::get('/user/info',[AdminauthController::class,'userInfo']);


// sanctum authentication (alumni)
Route::post('alumni/login', [AlumniAuthController::class, 'alumniLogin'])->name('alumni.login');
Route::post('alumni/register',[AlumniAuthController::class, 'alumniRegister'])->name('alumni.register');
Route::post('alumni/forgot-password', [AlumniAuthController::class, 'alumniForgotPassword'])->name('alumni.forgot_password');
Route::post('alumni/reset-password', [AlumniAuthController::class, 'alumniResetPassword'])->name('alumni.reset_password');

//User
Route::apiResource('user',UserController::class);

// admin
Route::get('admin/deleted-list', [AdminController::class, 'deletedListIndex'])->name('admin.deleted_list');
Route::get('admin/restore/{id}', [AdminController::class, 'restore'])->name('admin.restore');
Route::delete('admin/force-delete/{id}', [AdminController::class, 'forceDelete'])->name('admin.force_delete');
Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
Route::post('admin/status', [AdminController::class, 'status'])->name('admin.status');
Route::apiResource('admin',AdminController::class);

//about-ccc
Route::get('/about-ccc/edit/{id}', [AboutCCCController::class, 'edit'])->name('about-ccc.edit');
Route::post('about-ccc/status', [AboutCCCController::class, 'status'])->name('about-ccc.status');
Route::apiResource('about-ccc',AboutCCCController::class);

// job-blog
Route::apiResource('job-blog',JobBlogController::class);

//ccc-faq
Route::get('ccc-faq/deleted-list', [CCCFaqController::class, 'deletedListIndex'])->name('ccc-faq.deleted_list');
Route::get('ccc-faq/restore/{id}', [CCCFaqController::class, 'restore'])->name('ccc-faq.restore');
Route::delete('ccc-faq/force-delete/{id}', [CCCFaqController::class, 'forceDelete'])->name('ccc-faq.force_delete');
Route::get('/ccc-faq/edit/{id}', [CCCFaqController::class, 'edit'])->name('ccc-faq.edit');
Route::post('ccc-faq/status', [CCCFaqController::class, 'status'])->name('ccc-faq.status');
Route::apiResource('ccc-faq',CCCFaqController::class);

//club
Route::get('club/deleted-list', [ClubController::class, 'deletedListIndex'])->name('club.deleted_list');
Route::get('club/restore/{id}', [ClubController::class, 'restore'])->name('club.restore');
Route::delete('club/force-delete/{id}', [ClubController::class, 'forceDelete'])->name('club.force_delete');
Route::get('/club/edit/{id}', [ClubController::class, 'edit'])->name('club.edit');
Route::post('club/status', [ClubController::class, 'status'])->name('club.status');
Route::apiResource('club',ClubController::class);

//club_media
Route::get('club-media-event-list', [ClubMediaController::class, 'clubMediaEventList'])->name('club_media_event_list');
Route::get('club-media/deleted-list/{club_id}', [ClubMediaController::class, 'deletedListIndex'])->name('club_media.deleted_list');
Route::get('club-media/restore/{id}', [ClubMediaController::class, 'restore'])->name('club_media.restore');
Route::delete('club-media/force-delete/{id}', [ClubMediaController::class, 'forceDelete'])->name('club_media.force_delete');
Route::get('/club-media/edit/{id}', [ClubMediaController::class, 'edit'])->name('club_media.edit');
Route::post('club-media/status', [ClubMediaController::class, 'status'])->name('club_media.status');
Route::get('club-media/list/{club_id}', [ClubMediaController::class, 'clubMediaIndex'])->name('clubMediaIndex');
Route::get('club-media/{club_id}/{club_type}', [ClubMediaController::class, 'typeWiseMedia'])->name('typeWiseMedia');
Route::apiResource('club-media',ClubMediaController::class);

//club_moderator
Route::get('club-moderator/deleted-list/{club_id}', [ClubModeratorController::class, 'deletedListIndex'])->name('club_moderator.deleted_list');
Route::get('club-moderator/restore/{id}', [ClubModeratorController::class, 'restore'])->name('club_moderator.restore');
Route::delete('club-moderator/force-delete/{id}', [ClubModeratorController::class, 'forceDelete'])->name('club_moderator.force_delete');
Route::get('/club-moderator/edit/{id}', [ClubModeratorController::class, 'edit'])->name('club_moderator.edit');
Route::post('club-moderator/status', [ClubModeratorController::class, 'status'])->name('club_moderator.status');
Route::get('club-moderator/list/{club_id}', [ClubModeratorController::class, 'clubModeratorIndex'])->name('clubModeratorIndex');
Route::apiResource('club-moderator',ClubModeratorController::class);

//Club Committee
Route::get('club-committee/deleted-list/{club_id}', [ClubCommitteeController::class, 'deletedListIndex'])->name('club_committee.deleted_list');
Route::get('club-committee/restore/{id}', [ClubCommitteeController::class, 'restore'])->name('club_committee.restore');
Route::delete('club-committee/force-delete/{id}', [ClubCommitteeController::class, 'forceDelete'])->name('club_committee.force_delete');
Route::get('/club-committee/edit/{id}', [ClubCommitteeController::class, 'edit'])->name('club_committee.edit');
Route::post('club-committee/status', [ClubCommitteeController::class, 'status'])->name('club_committee.status');
Route::get('club-committee/list/{club_id}', [ClubCommitteeController::class, 'clubCommitteeIndex'])->name('clubCommitteeIndex');
Route::apiResource('club-committee',ClubCommitteeController::class);

// student
Route::get('/students/deleted-list', [StudentController::class, 'deletedListIndex'])->name('students.deleted-list');
Route::get('/students/restore/{id}', [StudentController::class, 'restore'])->name('students.restore');
Route::delete('/students/force-delete/{id}', [StudentController::class, 'forceDelete'])->name('students.force-delete');
Route::post('/students/status', [StudentController::class, 'status'])->name('students.status');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::apiResource('students',StudentController::class);

//skill
Route::post('skill/status', [SkillController::class, 'status'])->name('skill.status');
Route::delete('/skill/force-delete/{id}', [SkillController::class, 'forceDelete'])->name('skill.force_destroy');
Route::get('/skill/restore/{id}', [SkillController::class, 'restore'])->name('skill.restore');
Route::get('/skill/deleted-list', [SkillController::class, 'deletedListIndex'])->name('skill.deleted_list');
Route::get('skill/edit/{id}', [SkillController::class, 'edit'])->name('skill.edit');
Route::apiResource('skill',SkillController::class);

//news
Route::post('news/status', [NewsController::class, 'status'])->name('news.status');
Route::delete('/news/force-delete/{id}', [NewsController::class, 'forceDelete'])->name('news.force_destroy');
Route::get('/news/restore/{id}', [NewsController::class, 'restore'])->name('news.restore');
Route::get('/news/deleted-list', [NewsController::class, 'deletedListIndex'])->name('news.deleted_list');
Route::get('news/edit/{id}', [NewsController::class, 'edit'])->name('news.edit');
Route::apiResource('news',NewsController::class);

//JobInterestedArea
Route::post('job-interested-area/status', [JobInterestedAreaController::class, 'status'])->name('job-interested-area.status');
Route::delete('/job-interested-area/force-delete/{id}', [JobInterestedAreaController::class, 'forceDelete'])->name('job-interested-area.force_destroy');
Route::get('/job-interested-area/restore/{id}', [JobInterestedAreaController::class, 'restore'])->name('job-interested-area.restore');
Route::get('/job-interested-area/deleted-list', [JobInterestedAreaController::class, 'deletedListIndex'])->name('job-interested-area.deleted_list');
Route::get('job-interested-area-edit/{id}', [JobInterestedAreaController::class, 'edit'])->name('job-interested-area.edit');
Route::apiResource('job-interested-area',JobInterestedAreaController::class);

// Major-Minor
Route::get('major-minor-deleted-list', [MajorMinorController::class, 'deletedListIndex'])->name('major-minor.deleted_list');
Route::get('major-minor-restore/{id}', [MajorMinorController::class, 'restore'])->name('major-minor.restore');
Route::post('major-minor-status', [MajorMinorController::class, 'status'])->name('major-minor.status');
Route::delete('major-minor-force-delete/{id}', [MajorMinorController::class, 'forceDelete'])->name('major-minor.force_delete');
Route::get('major-minor-edit/{id}', [MajorMinorController::class, 'edit'])->name('major-minor.edit');
Route::apiResource('major-minor',MajorMinorController::class);

// achievement
Route::post('achievement/status', [AchievementController::class, 'status'])->name('achievement.status');
Route::delete('/achievement/force-delete/{id}', [AchievementController::class, 'forceDelete'])->name('achievement.force_destroy');
Route::get('/achievement/restore/{id}', [AchievementController::class, 'restore'])->name('achievement.restore');
Route::get('/achievement/deleted-list', [AchievementController::class, 'deletedListIndex'])->name('achievement.deleted_list');
Route::get('/achievement/edit/{id}', [AchievementController::class, 'edit'])->name('achievement.edit');
Route::apiResource('achievement', AchievementController::class);

// Interest
Route::post('interest/status', [InterestController::class, 'status'])->name('interest.status');
Route::delete('/interest/force-delete/{id}', [InterestController::class, 'forceDelete'])->name('interest.force_destroy');
Route::get('/interest/restore/{id}', [InterestController::class, 'restore'])->name('interest.restore');
Route::get('/interest/deleted-list', [InterestController::class, 'deletedListIndex'])->name('interest.deleted_list');
Route::get('/interest/edit/{id}', [InterestController::class, 'edit'])->name('interest.edit');
Route::apiResource('interest',InterestController::class);

// Magazine
Route::get('magazine-deleted_list', [MagazineController::class, 'deletedListIndex'])->name('magazine.deleted_list');
Route::get('magazine-restore/{id}', [MagazineController::class, 'restore'])->name('magazine.restore');
Route::delete('magazine-force-delete/{id}', [MagazineController::class, 'forceDelete'])->name('magazine.force_delete');
Route::post('magazine-status', [MagazineController::class, 'status'])->name('magazine.status');
Route::get('magazine-edit/{id}', [MagazineController::class, 'edit'])->name('magazine.edit');
Route::apiResource('magazine',MagazineController::class);

//division
Route::get('division-deleted_list', [DivisionController::class, 'deletedListIndex'])->name('division.deleted_list');
Route::get('division-restore/{id}', [DivisionController::class, 'restore'])->name('division.restore');
Route::delete('division-force-delete/{id}', [DivisionController::class, 'forceDelete'])->name('division.force_delete');
Route::post('division-status', [DivisionController::class, 'status'])->name('division.status');
Route::get('division-edit/{id}', [DivisionController::class, 'edit'])->name('division.edit');
Route::apiResource('division',DivisionController::class);

//thana
Route::get('thana-deleted_list', [ThanaController::class, 'deletedListIndex'])->name('thana.deleted_list');
Route::get('thana-restore/{id}', [ThanaController::class, 'restore'])->name('thana.restore');
Route::delete('thana-force-delete/{id}', [ThanaController::class, 'forceDelete'])->name('thana.force_delete');
Route::post('thana-status', [ThanaController::class, 'status'])->name('thana.status');
Route::get('thana-edit/{id}', [ThanaController::class, 'edit'])->name('thana.edit');
Route::get('district/thanas/{id}', [ThanaController::class, 'district_thanas']);

Route::apiResource('thana',ThanaController::class);

//Deparment
Route::get('department-deleted_list', [DepartmentController::class, 'deletedListIndex'])->name('department.deleted_list');
Route::get('department-restore/{id}', [DepartmentController::class, 'restore'])->name('department.restore');
Route::delete('department-force-delete/{id}', [DepartmentController::class, 'forceDelete'])->name('department.force_delete');
Route::post('department-status', [DepartmentController::class, 'status'])->name('department.status');
Route::get('department-edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
Route::apiResource('department',DepartmentController::class);

//Menu
Route::get('menu-deleted_list', [MenuController::class, 'deletedListIndex'])->name('menu.deleted_list');
Route::get('menu-restore/{id}', [MenuController::class, 'restore'])->name('menu.restore');
Route::delete('menu-force-delete/{id}', [MenuController::class, 'forceDelete'])->name('menu.force_delete');
Route::post('menu-multiple-delete', [MenuController::class, 'multipleDelete'])->name('menu.multiple_delete');
Route::post('menu-multiple-restore', [MenuController::class, 'multipleRestore'])->name('menu.multiple_restore');
Route::post('menu-status', [MenuController::class, 'status'])->name('menu.status');
Route::get('menu-edit/{id}', [MenuController::class, 'edit'])->name('menu.edit');
Route::apiResource('menu',MenuController::class);
//MenuAction
Route::get('menu-action-deleted_list', [MenuActionController::class, 'deletedListIndex'])->name('menu_action.deleted_list');
Route::get('menu-action-restore/{id}', [MenuActionController::class, 'restore'])->name('menu_action.restore');
Route::delete('menu-action-force-delete/{id}', [MenuActionController::class, 'forceDelete'])->name('menu_action.force_delete');
Route::post('menu-action-status', [MenuActionController::class, 'status'])->name('menu_action.status');
Route::get('menu-action-edit/{id}', [MenuActionController::class, 'edit'])->name('menu_action.edit');
Route::apiResource('menu-action',MenuActionController::class);
//Role
Route::get('role-deleted_list', [RoleController::class, 'deletedListIndex'])->name('role.deleted_list');
Route::get('role-restore/{id}', [RoleController::class, 'restore'])->name('role.restore');
Route::delete('role-force-delete/{id}', [RoleController::class, 'forceDelete'])->name('role.force_destroy');
Route::any('role-permission/{id}', [RoleController::class, 'permission'])->name('role.permission');
Route::post('role-status', [RoleController::class, 'status'])->name('role.status');
Route::get('role-edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
Route::apiResource('role',RoleController::class);
//ProfessionalCertification
Route::get('p-certification-deleted-list', [ProfessionalCertificationController::class, 'deletedListIndex'])->name('p_certification.deleted_list');
Route::get('p-certification-restore/{id}', [ProfessionalCertificationController::class, 'restore'])->name('p_certification.restore');
Route::get('p-certification-force-delete/{id}', [ProfessionalCertificationController::class, 'forceDelete'])->name('p_certification.force_destroy');
Route::post('p-certification-status', [ProfessionalCertificationController::class, 'status'])->name('p_certification.status');
Route::get('p-certification-edit/{id}', [ProfessionalCertificationController::class, 'edit'])->name('p_certification.edit');
Route::get('user-certification/{resume_id}', [ProfessionalCertificationController::class, 'userCertificaion']);
Route::apiResource('professional-certification',ProfessionalCertificationController::class);

//banner
Route::post('/banners/status', [BannerController::class, 'status'])->name('bannesr.status');
Route::delete('/banners/force-delete/{id}', [BannerController::class, 'forceDelete'])->name('banners.force_destroy');
Route::get('/banners/edit/{id}', [BannerController::class, 'edit'])->name('banners.edit');
Route::get('/banners/restore/{id}', [BannerController::class, 'restore'])->name('banners.restore');
Route::get('/banners/deleted-list', [BannerController::class, 'deletedListIndex'])->name('banners.deleted_list');
Route::apiResource('banners',BannerController::class);

//StudentWelfare
Route::post('/student-welfare/status', [StudentWelfareController::class, 'status'])->name('student-welfare.status');
Route::delete('/student-welfare/force-delete/{id}', [StudentWelfareController::class, 'forceDelete'])->name('student-welfare.force_destroy');
Route::get('/student-welfare/edit/{id}', [StudentWelfareController::class, 'edit'])->name('student-welfare.edit');
Route::get('/student-welfare/restore/{id}', [StudentWelfareController::class, 'restore'])->name('student-welfare.restore');
Route::get('/student-welfare/deleted-list', [StudentWelfareController::class, 'deletedListIndex'])->name('student-welfare.deleted_list');
Route::apiResource('/student-welfare',StudentWelfareController::class);

//site-setting
Route::post('/site-settings/status', [SiteSettingController::class, 'status'])->name('site-settings.status');
Route::delete('/site-settings/force-delete/{id}', [SiteSettingController::class, 'forceDelete'])->name('site-settings.force_destroy');
Route::get('/site-settings/edit/{id}', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
Route::get('/site-settings/restore/{id}', [SiteSettingController::class, 'restore'])->name('site-settings.restore');
Route::get('/site-settings/deleted-list', [SiteSettingController::class, 'deletedListIndex'])->name('site-settings.deleted_list');
Route::apiResource('site-settings',SiteSettingController::class);

// audio video
Route::apiResource('audio-video',AudioVideoController::class);

//workshop
Route::post('workshop/status', [WorkshopController::class, 'status'])->name('workshop.status');
Route::delete('/workshop/force-delete/{id}', [WorkshopController::class, 'forceDelete'])->name('workshop.force_destroy');
Route::get('/workshop/edit/{id}', [WorkshopController::class, 'edit'])->name('workshop.edit');
Route::get('/workshop/restore/{id}', [WorkshopController::class, 'restore'])->name('workshop.restore');
Route::get('/workshop/deleted-list', [WorkshopController::class, 'deletedListIndex'])->name('workshop.deleted_list');
Route::apiResource('workshop',WorkshopController::class);

//training
Route::post('training/status', [TrainingController::class, 'status'])->name('training.status');
Route::delete('/training/force-delete/{id}', [TrainingController::class, 'forceDelete'])->name('training.force_destroy');
Route::get('/training/edit/{id}', [TrainingController::class, 'edit'])->name('training.edit');
Route::get('/training/restore/{id}', [TrainingController::class, 'restore'])->name('training.restore');
Route::get('/training/deleted-list', [TrainingController::class, 'deletedListIndex'])->name('training.deleted_list');
Route::apiResource('training',TrainingController::class);

//slider
Route::post('slider/status', [SliderController::class, 'status'])->name('slider.status');
Route::delete('slider/force-delete/{id}', [SliderController::class, 'forceDelete'])->name('slider.force_destroy');
Route::get('slider/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit');
Route::get('slider/restore/{id}', [SliderController::class, 'restore'])->name('slider.restore');
Route::get('slider/deleted-list', [SliderController::class, 'deletedListIndex'])->name('slider.deleted_list');
Route::apiResource('slider',SliderController::class);

//contact-us
Route::post('contactUs/status', [ContactUsController::class, 'status'])->name('contactUs.status');
Route::delete('contactUs/force-delete/{id}', [ContactUsController::class, 'forceDelete'])->name('contactUs.force_destroy');
Route::get('contactUs/edit/{id}', [ContactUsController::class, 'edit'])->name('contactUs.edit');
Route::get('contactUs/restore/{id}', [ContactUsController::class, 'restore'])->name('contactUs.restore');
Route::get('contactUs/deleted-list', [ContactUsController::class, 'deletedListIndex'])->name('contactUs.deleted_list');
Route::apiResource('contactUs',ContactUsController::class);




Route::apiResource('jobSeeker',JobSeekerController::class);

Route::post('co-activity-status', [CoCurricularActivityController::class, 'status'])->name('co_activity.status');
Route::get('co-activity-edit/{id}', [CoCurricularActivityController::class, 'edit'])->name('co_activity.edit');
Route::delete('co-activity-force-delete/{id}', [CoCurricularActivityController::class, 'forceDelete'])->name('co_activity.force_destroy');
Route::get('co-activity-restore/{id}', [CoCurricularActivityController::class, 'restore'])->name('co_activity.restore');
Route::get('co-activity-deleted_list', [CoCurricularActivityController::class, 'deletedListIndex'])->name('co_activity.deleted_list');
Route::apiResource('co-curricular-activity',CoCurricularActivityController::class);

//company-detail
Route::post('company-detail/status', [CompanyDetailController::class, 'status'])->name('company-detail.status');
Route::delete('/company-detail/force-delete/{id}', [CompanyDetailController::class, 'forceDelete'])->name('company-detail.force_destroy');
Route::get('/company-detail/edit/{id}', [CompanyDetailController::class, 'edit'])->name('company-detail.edit');
Route::get('/company-detail/restore/{id}', [CompanyDetailController::class, 'restore'])->name('company-detail.restore');
Route::get('/company-detail/deleted-list', [CompanyDetailController::class, 'deletedListIndex'])->name('company-detail.deleted_list');
Route::apiResource('company-detail',CompanyDetailController::class);

// Designation
Route::post('designation/status', [DesignationController::class, 'status'])->name('designation.status');
Route::delete('/designation/force-delete/{id}', [DesignationController::class, 'forceDelete'])->name('designation.force_destroy');
Route::get('/designation/restore/{id}', [DesignationController::class, 'restore'])->name('designation.restore');
Route::get('/designation/deleted-list', [DesignationController::class, 'deletedListIndex'])->name('designation.deleted_list');
Route::get('/designation/edit/{id}', [DesignationController::class, 'edit'])->name('designation.edit');
Route::apiResource('designation', DesignationController::class);

// Country
Route::post('country/status', [CountryController::class, 'status'])->name('country.status');
Route::delete('/country/force-delete/{id}', [CountryController::class, 'forceDelete'])->name('country.force_destroy');
Route::get('/country/restore/{id}', [CountryController::class, 'restore'])->name('country.restore');
Route::get('/country/deleted-list', [CountryController::class, 'deletedListIndex'])->name('country.deleted_list');
Route::get('/country/edit/{id}', [CountryController::class, 'edit'])->name('country.edit');
Route::apiResource('country', CountryController::class);

// District
Route::post('district/status', [DistrictController::class, 'status'])->name('district.status');
Route::delete('/district/force-delete/{id}', [DistrictController::class, 'forceDelete'])->name('district.force_destroy');
Route::get('/district/restore/{id}', [DistrictController::class, 'restore'])->name('district.restore');
Route::get('/district/deleted-list', [DistrictController::class, 'deletedListIndex'])->name('district.deleted_list');
Route::get('district/create', [DistrictController::class, 'create'])->name('district.create');
Route::get('division/districts/{id}', [DistrictController::class, 'division_districts']);


Route::apiResource('district', DistrictController::class);

// User Menu Action
Route::get('menu/action/{menu_id}', [UserMenuActionController::class, 'index'])->name('user_menu_action.index');
Route::get('/menu/action/deleted-list/{menu_id}', [UserMenuActionController::class, 'deletedListIndex'])->name('user_menu_action.deleted_list');
Route::post('menu/action/store/{menu_id}', [UserMenuActionController::class, 'store'])->name('user_menu_action.store');
Route::post('menu/action/update/{menu_id}/{id}', [UserMenuActionController::class, 'update'])->name('user_menu_action.update');
Route::delete('menu/action/destroy/{menu_id}/{id}', [UserMenuActionController::class, 'destroy'])->name('user_menu_action.destroy');
Route::get('/menu/action/restore/{id}', [UserMenuActionController::class, 'restore'])->name('user_menu_action.restore');
Route::delete('/menu/action/force-delete/{id}', [UserMenuActionController::class, 'forceDelete'])->name('user_menu_action.force_destroy');
Route::post('menu/action/status', [UserMenuActionController::class, 'status'])->name('user_menu_action.status');

// Job Category
Route::post('job-category/status', [JobCategoryController::class, 'status'])->name('job_category.status');
Route::delete('/job-category/force-delete/{id}', [JobCategoryController::class, 'forceDelete'])->name('job_category.force_destroy');
Route::get('/job-category/restore/{id}', [JobCategoryController::class, 'restore'])->name('job_category.restore');
Route::get('/job-category/deleted-list', [JobCategoryController::class, 'deletedListIndex'])->name('job_category.deleted_list');
Route::get('/job-category/edit/{id}', [JobCategoryController::class, 'edit'])->name('job_category.edit');
Route::apiResource('job-category', JobCategoryController::class);


// job sub category
Route::apiResource('jobsub-category', JobSubCategoryController::class);

// Alumni
Route::delete('alumnis/massDestroy/{alumnis}', [AlumniController::class, 'massDestroy']);
Route::get('alumnis/export/{alumniIds}', [AlumniController::class, 'exportXLS']);
Route::get('read-profile-completion-notification', [AlumniController::class, 'readProfileCompletionNotification']);
Route::get('get-unread-profile-completion-notification', [AlumniController::class, 'getUnreadProfileCompletionNotification']);
Route::post('profile-completion-notification/{receiverAlumniId}', [AlumniController::class, 'profileCompletionNotification']);
Route::get('invite-others', [AlumniController::class, 'inviteOthers']);
Route::get('alumni/search', [AlumniController::class, 'search'])->name('alumni.search');
Route::get('/alumni/profile-completion-percentage/{id}', [AlumniController::class, 'alumniProfileCompletionPercentage'])->name('alumni.profile_completion_percentage');
Route::post('alumni/status', [AlumniController::class, 'status'])->name('alumni.status');
Route::delete('/alumni/force-delete/{id}', [AlumniController::class, 'forceDelete'])->name('alumni.force_destroy');
Route::get('/alumni/restore/{id}', [AlumniController::class, 'restore'])->name('alumni.restore');
Route::get('/alumni/deleted-list', [AlumniController::class, 'deletedListIndex'])->name('alumni.deleted_list');
Route::get('/alumni/edit/{id}', [AlumniController::class, 'edit'])->name('alumni.edit');
Route::get('/alumnis/all', [AlumniController::class, 'allAlumnis'])->name('all.alumnis');
Route::apiResource('alumni', AlumniController::class);

// Offensive word routes
Route::apiResource('offensive-word', OffensiveWordController::class);

//Newsletter mail
Route::apiResource('newsletter_mail', NewsletterMailController::class);
Route::apiResource('create-mail-list', CreateMailListController::class);

//system users
Route::get('system/users', [UserController::class, 'systemUsers'])->name('system.users');

//Fund-event
Route::get('fund-event-details/{fundEventId}', [FundEventController::class, 'fundEventDetails'])->name('fundEvent.details');
Route::get('fund-event/get-creator-wise', [FundEventController::class, 'getCreatorWise'])->name('fundEvent.getCreatorWise');
Route::post('fund-payment', [FundEventController::class, 'fundPayment'])->name('fundEvent.fundPayment');
Route::apiResource('fund-event', FundEventController::class);


//Job-post
//approval

Route::get('/singlecategory/jobs/{id}', [JobPostController::class, 'singlecategory']);
Route::get('/recent/jobs', [JobPostController::class, 'recentjobs']);
Route::post('/job/search', [JobPostController::class, 'jobsearch']);
Route::get('/homesearch-jobs/{searchkeyword}', [JobPostController::class, 'homesearchjob']);
// Route::get('/homesearch-jobs', [JobPostController::class, 'homesearchjob']);

Route::get('/salaryranges', [JobPostController::class, 'salaryrange']);
Route::get('/selectedtype', [JobPostController::class, 'selectedtype']);



// jobpostsearch
Route::get('/jobpostsearch/{keyword?}', [JobPostSearchController::class, 'jobpostsearchlist']);
Route::get('/prices', [JobPostSearchController::class, 'pricelist']);



// ApplyJob
Route::get('application/download/{id}', [JobApplicationController::class, 'download']);
Route::get('application/getResumeFile/{id}', [JobApplicationController::class, 'getResumeFile']);
Route::post('send-mail', [JobApplicationController::class, 'sendMail']);
Route::post('job-applications/shortlisted/{id}', [JobApplicationController::class, 'shortlist'])->name('jobApplications.shortlisted');
Route::post('job-applications/removeShortlist/{id}', [JobApplicationController::class, 'removeShortlist'])->name('jobApplications.removeShortlist');
Route::get('job-applications/allShortlist/{jobId}', [JobApplicationController::class, 'allShortlist'])->name('jobApplications.allShortlist');
Route::get('job-applications/withdraw/{jobId}', [JobApplicationController::class, 'withdrawApplication']);
Route::get('job-applications/{jobId}', [JobApplicationController::class, 'jobApplications']);


Route::get('user-job-application/{resume_id}', [JobApplicationController::class, 'userJobApplication']);
Route::get('user-job-withdraw-application-list/{resume_id}', [JobApplicationController::class, 'userJobWithdrawApplicationList']);
// Route::get('job-applications/withdraw/{id}', [JobApplicationController::class, 'jobApplicationWithdraw']);
Route::post('job-applications/withdraw', [JobApplicationController::class, 'jobApplicationWithdraw']);

Route::get('job-applications/withdraw-cancle/{id}', [JobApplicationController::class, 'jobApplicationWithdrawCancle']);



Route::apiResource('jobapplications',JobApplicationController::class);


// Job Profile and user rating route  create
Route::post('job-profile/store/user-ratings', [JobProfileController::class, 'userRatingStore']);
Route::put('job-profile/update/user-ratings/{id}', [JobProfileController::class, 'userRatingUpdate']);
Route::get('job-profile/user-ratings/{resume_id}', [JobProfileController::class, 'userRatingIndex']);
Route::post('/jobprofile/login',[JobProfileController::class,'userlogin']);
Route::get('/jobprofile/logout',[JobProfileController::class,'userlogout']);
Route::put('jobprofile/account-update/{user_id}',[JobProfileController::class,'accountUpdate']);
Route::get('/jobprofile/home/{resume_id}',[JobProfileController::class,'userprofile']);
Route::apiResource('jobprofile',JobProfileController::class);



// Resume Create

Route::get('user/resume-files/{id}',[ResumeFileController::class,'userResumeFile']);
Route::apiResource('resume-files',ResumeFileController::class);
Route::get('user/resume/{user_id}',[ResumeController::class,'userResume']);
Route::put('resume-files-cv-video/{id}',[ResumeController::class,'userResumeFiles']);
Route::apiResource('resumes',ResumeController::class);



// career-application Create
Route::get('user/career-applications/{resume_id}',[CareerApplicationController::class,'userCareerApplication']);
Route::apiResource('career-applications',CareerApplicationController::class);
// job-preffered-areas
Route::get('user/job-preffered-areas/{resume_id}',[JobPrefferedAreaController::class,'userJobPrefferedArea']);
Route::apiResource('job-preffered-areas',JobPrefferedAreaController::class);

// EmploymentHistory
Route::get('user/employment-history/{resume_id}',[EmploymentHistoryController::class,'userEmploymentHistory']);
Route::apiResource('employment-history',EmploymentHistoryController::class);

// Specilization
Route::get('user-specialization/{resume_id}',[SpecializationController::class,'userSpecialization']);
Route::apiResource('specialization',SpecializationController::class);

// Address Create
Route::get('user-address/{user_id}',[AddressController::class,'useraddress']);
Route::apiResource('address',AddressController::class);

// LanguageProficiency
Route::apiResource('language-proficiency',LanguageProficiencyController::class);

// Reference
Route::get('user-reference/{resume_id}',[ReferenceControler::class,'userReference']);
Route::apiResource('reference',ReferenceControler::class);




// Organization Type
Route::apiResource('organization-type',OrganizationTypeController::class);

// Organization Type
Route::get('user/training-summaries/{resume_id}',[TrainingSummaryController::class,'user_training_summary']);
Route::apiResource('training-summaries',TrainingSummaryController::class);





Route::post('job-post/approval/{id}', [JobPostController::class, 'jobPostApproval'])->name('job-post.approval');
Route::post('job-post/status', [JobPostController::class, 'status'])->name('job-post.status');
Route::delete('/job-post/force-delete/{id}', [JobPostController::class, 'forceDelete'])->name('job-post.force_destroy');
// Route::get('/job-post/edit/{id}', [JobPostController::class, 'edit'])->name('job-post.edit');
Route::get('/job-post/restore/{id}', [JobPostController::class, 'restore'])->name('job-post.restore');
Route::get('/job-post/deleted-list', [JobPostController::class, 'deletedListIndex'])->name('job-post.deleted_list');
Route::get('/job-post/new-for-admin', [JobPostController::class, 'newJobPostsForAdmin'])->name('job-post.new_job_posts_for_admin');
Route::apiResource('job-post',JobPostController::class);




// notice event
Route::get('notice-event-single-page', [NoticeEventController::class, 'singlepage'])->name('singlepage');
Route::get('notice-event-paginated-list', [NoticeEventController::class, 'paginatedlist'])->name('paginatedlist');
Route::apiResource('notice-event', NoticeEventController::class);




Route::post('ccc-updates/status', [CccUpdatesController::class, 'status'])->name('ccc-updates.status');
Route::delete('/ccc-updates/force-delete/{id}', [CccUpdatesController::class, 'forceDelete'])->name('ccc-updates.force_destroy');
Route::get('/ccc-updates/edit/{id}', [CccUpdatesController::class, 'edit'])->name('ccc-updates.edit');
Route::get('/ccc-updates/restore/{id}', [CccUpdatesController::class, 'restore'])->name('ccc-updates.restore');
Route::get('/ccc-updates/deleted-list', [CccUpdatesController::class, 'deletedListIndex'])->name('ccc-updates.deleted_list');
Route::apiResource('ccc-updates', CccUpdatesController::class);


Route::apiResource('ccc-news', CccNewsController::class);

Route::apiResource('partner', PartnerController::class);

Route::apiResource('resource', ResourceController::class);


Route::post('guideline/status', [GuidelineController::class, 'status'])->name('guideline.status');
Route::delete('/guideline/force-delete/{id}', [GuidelineController::class, 'forceDelete'])->name('guideline.force_destroy');
Route::get('/guideline/edit/{id}', [GuidelineController::class, 'edit'])->name('guideline.edit');
Route::get('/guideline/restore/{id}', [GuidelineController::class, 'restore'])->name('guideline.restore');
Route::get('/guideline/deleted-list', [GuidelineController::class, 'deletedListIndex'])->name('guideline.deleted_list');
Route::apiResource('guideline', GuidelineController::class);



Route::apiResource('whoWeAre', WhoWeAreController::class);

Route::apiResource('career-tips', CareerTipsController::class);

//import alumni data list via excel file
Route::post('alumnis-import', [AlumniController::class, 'import'])->name('alumni.import');

Route::apiResource('category', CategoryController::class);
Route::apiResource('sub-category', SubCategoryController::class);

// Education
Route::post('update-from-job/{resumeId}',[EducationController::class,'updateFromJob'])->name('update_from_job');
Route::apiResource('education', EducationController::class);

// Subject
Route::get('subject/ssc', [SubjectController::class, 'getSSC'])->name('subject.ssc');
Route::get('subject/hsc', [SubjectController::class, 'getHSC'])->name('subject.hsc');
Route::get('subject/graduation', [SubjectController::class, 'getGraduation'])->name('subject.graduation');
Route::get('subject/masters', [SubjectController::class, 'getMasters'])->name('subject.masters');
Route::apiResource('subject', SubjectController::class);

// Institute
Route::get('institute/ssc', [InstituteController::class, 'getSSC'])->name('institute.ssc');
Route::get('institute/hsc', [InstituteController::class, 'getHSC'])->name('institute.hsc');
Route::get('institute/graduation', [InstituteController::class, 'getGraduation'])->name('institute.graduation');
Route::get('institute/masters', [InstituteController::class, 'getMasters'])->name('institute.masters');
Route::apiResource('institute', InstituteController::class);

// Experience
Route::apiResource('experience', ExperienceController::class);






// News Feed
Route::apiResource('news-feed', NewsFeedController::class);

// Group
Route::apiResource('group', GroupController::class);

// Group News Feed
Route::apiResource('group-news-feed', GroupNewsFeedController::class);

// group member routes
Route::get('get-suggestion-groups', [GroupMemberController::class, 'getSuggestionGroups']);
Route::post('send-group-join-request/{group_id}', [GroupMemberController::class, 'sendGroupJoinRequest']);
Route::post('cancel-group-join-request/{group_id}', [GroupMemberController::class, 'cancelGroupJoinRequest']);
Route::post('leave-this-group/{group_id}', [GroupMemberController::class, 'leaveThisGroup']);

Route::get('get-user-joining-group-list/{user_id}', [GroupMemberController::class, 'getUserJoiningGroupList']);

Route::get('get-send-joining-request-group-list/{user_id}', [GroupMemberController::class, 'getSendJoiningRequestGroupList']);
Route::get('get-receive-joining-request-group-member-list/{group_id}', [GroupMemberController::class, 'getReceiveJoiningRequestGroupMemberList']);

Route::post('deny-group-join-request/{group_id}/{member_id}', [GroupMemberController::class, 'denyGroupJoinRequest']);
Route::post('accept-group-join-request/{group_id}/{member_id}', [GroupMemberController::class, 'acceptGroupJoinRequest']);

Route::get('get-group-member-list/{group_id}', [GroupMemberController::class, 'getGroupMemberList']);
Route::get('get-group-roles', [GroupMemberController::class, 'getGroupRoles']);

Route::post('add-group-permission/{group_member_id}', [GroupMemberController::class, 'addGroupPermission']);


// chapter
Route::apiResource('chapter', ChapterController::class);

// chapter member routes
Route::get('chapter-suggestion-list', [ChapterMemberController::class, 'chapterSuggestionList']);
Route::post('chapter-send-joining-request/{chapter_id}', [ChapterMemberController::class, 'chapterSendJoiningRequest']);
Route::post('chapter-cancel-joining-request/{chapter_id}', [ChapterMemberController::class, 'chapterCancelJoiningRequest']);

Route::get('chapter-list-where-you-member/{alumni_id}', [ChapterMemberController::class, 'chapterListWhereYouMember']);
Route::post('chapter-leave-where-you-member/{chapter_id}', [ChapterMemberController::class, 'chapterLeaveWhereYouMember']);

Route::get('chapter-incomming-member-request-list/{chapter_id}', [ChapterMemberController::class, 'chapterIncommingMemberRequestList']);
Route::post('chapter-accept-member-joining-request/{chapter_id}/{member_id}', [ChapterMemberController::class, 'chapterAcceptMemberJoiningRequest']);
Route::post('chapter-deny-member-joining-request/{chapter_id}/{member_id}', [ChapterMemberController::class, 'chapterDenyMemberJoiningRequest']);

Route::get('chapter-member-list/{chapter_id}', [ChapterMemberController::class, 'chapterMemberList']);


// Connection routes
// friendship routes
Route::get('get-suggestion-alumnis', [ConnectionController::class, 'getSuggestionAlumnis']);
Route::post('get-single-friendship/{sender_id}/{recipient_id}', [ConnectionController::class, 'getSingleFriendship']);
Route::post('send-friend-request/{recipientAlumniId}', [ConnectionController::class, 'sendFriendRequest']);

Route::post('get-pending-friend-requests', [ConnectionController::class, 'getPendingFriendRequests']);
Route::post('cancel-friend-request/{recipientAlumniId}', [ConnectionController::class, 'cancelFriendRequest']);

Route::post('get-invitation-friend-requests', [ConnectionController::class, 'getInvitationFriendRequests']);
Route::post('accept-friend-request/{senderAlumniId}', [ConnectionController::class, 'acceptFriendRequest']);
Route::post('deny-friend-request/{senderAlumniId}', [ConnectionController::class, 'denyFriendRequest']);

Route::post('get-connection-friends', [ConnectionController::class, 'getConnectionFriends']);
Route::post('unfriend/{senderAlumniId}', [ConnectionController::class, 'unfriend']);

// block frined routes
Route::post('get-block-friend-lists', [ConnectionController::class, 'getBlockFriendLists']);
Route::post('get-block-friendship/{sender_id}/{recipient_id}', [ConnectionController::class, 'getBlockFriendship']);
Route::post('block/{friend_id}', [ConnectionController::class, 'block']);
Route::post('unblock/{friend_id}', [ConnectionController::class, 'unblock']);

// following routes
Route::get('get-suggestion-following-alumnis', [ConnectionController::class, 'getSuggestionFollowingAlumnis']);
Route::post('get-following-friends', [ConnectionController::class, 'getFollowingFriends']);
Route::post('get-follower-friends', [ConnectionController::class, 'getFollowerFriends']);
Route::post('follow/{target_id}', [ConnectionController::class, 'follow']);
Route::post('unfollow/{target_id}', [ConnectionController::class, 'unfollow']);
Route::post('is-following/{alumni_id}/{subject_id}', [ConnectionController::class, 'isFollowing']);

// count routes
Route::get('total-friends-count', [ConnectionController::class, 'totalFriendsCount']);
Route::get('total-pending-friend-request-count', [ConnectionController::class, 'totalPendingFriendRequestCount']);
Route::get('total-invitation-count', [ConnectionController::class, 'totalInvitationCount']);
Route::get('total-block-list-count', [ConnectionController::class, 'totalBlockListCount']);

// Mutual friend routes
Route::post('get-mutual-friends/{user_id}/{other_user_id}', [ConnectionController::class, 'getMutualFriends']);

// setting routes
Route::get('get-helper-functions/{key}', [SettingController::class, 'getHelperFunctions']);

// event
Route::apiResource('event', EventController::class);

// event member routes
Route::get('event-suggestion-list', [EventMemberController::class, 'eventSuggestionList']);
Route::get('get-interested-event', [EventMemberController::class, 'getInterestedEvent']);
Route::get('get-going-event', [EventMemberController::class, 'getGoingEvent']);
Route::post('event-interested/{event_id}', [EventMemberController::class, 'eventInterested']);
Route::post('event-not-interested/{event_id}', [EventMemberController::class, 'eventNotInterested']);
Route::post('event-going/{event_id}', [EventMemberController::class, 'eventGoing']);
Route::post('event-not-going/{event_id}', [EventMemberController::class, 'eventNotGoing']);


// endorsement routes
Route::get('endorsement-list', [EndorsementController::class, 'endorsementList']);
Route::post('add-endorsement', [EndorsementController::class, 'addEndorsement']);
Route::post('cancel-endorsement', [EndorsementController::class, 'cancelEndorsement']);


// class memories
Route::apiResource('class-memories', ClassMemoriesController::class);
// class memories member
Route::get('class-memories-suggestion-list', [ClassMemoriesMemberController::class, 'classMemoriesSuggestionList']);
Route::post('send-class-memories-join-request/{classMemoriesId}', [ClassMemoriesMemberController::class, 'sendClassMemoriesJoinRequest']);
Route::post('cancel-class-memories-join-request/{classMemoriesId}', [ClassMemoriesMemberController::class, 'cancelClassMemoriesJoinRequest']);
Route::post('leave-this-class-memories/{classMemoriesId}', [ClassMemoriesMemberController::class, 'leaveThisClassMemories']);

Route::get('get-class-memories-member-request-list/{classMemoriesId}', [ClassMemoriesMemberController::class, 'getClassMemoriesMemberRequestList']);
Route::post('deny-class-memories-member-request/{classMemoriesId}/{memberId}', [ClassMemoriesMemberController::class, 'denyClassMemoriesMemberRequest']);
Route::post('accept-class-memories-member-request/{classMemoriesId}/{memberId}', [ClassMemoriesMemberController::class, 'acceptClassMemoriesMemberRequest']);

Route::get('get-joined-class-memories-list/{userId}', [ClassMemoriesMemberController::class, 'getJoinedClassMemoriesList']);
Route::get('send-joining-request-class-memories-list/{userId}', [ClassMemoriesMemberController::class, 'sendJoiningRequestClassMemoriesList']);

Route::get('get-class-memories-member-list/{classMemoriesId}', [ClassMemoriesMemberController::class, 'getClassMemoriesMemberList']);
Route::get('get-class-memories-roles', [ClassMemoriesMemberController::class, 'getClassMemoriesRoles']);
Route::post('add-class-memories-permission/{classMemoriesMemberId}', [ClassMemoriesMemberController::class, 'addClassMemoriesPermission']);
// class memories news feed
Route::apiResource('class-memories-news-feed', ClassMemoriesNewsFeedController::class);

// report type routes
Route::apiResource('report-type', ReportTypeController::class);
// report routes
Route::apiResource('report', ReportController::class);






// chat routes
Route::get('user-message/{userId}/{authUserId}', [MessageController::class, 'userMessage'])->name('user.message');
Route::post('send-message', [MessageController::class, 'sendMessage'])->name('user.message.send');
Route::delete('delete-single-message/{messageId}', [MessageController::class, 'deleteSingleMessage'])->name('user.single.message.delete');
Route::delete('delete-all-message/{userId}/{authUserId}', [MessageController::class, 'deleteAllMessage'])->name('user.all.message.delete');



