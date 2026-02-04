<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Aide\ParticipationController;
use App\Http\Controllers\Aide\NouveauController;
use App\Http\Controllers\Admin\AideController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\NouveauController as AdminNouveauController;
use App\Http\Controllers\Admin\StatistiqueController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UserController;

// Page d'accueil - Redirige selon l'état d'authentification
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/aide/dashboard');
    }
    return redirect('/login');
});

// Routes d'authentification
require __DIR__.'/auth.php';

// Redirection après login
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if (!$user) {
        return redirect('/login');
    }
    
    if ($user->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    
    return redirect('/aide/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== ROUTES AIDE ====================
Route::prefix('aide')->name('aide.')->middleware(['auth'])->group(function () {
    
    // Dashboard Aide
    Route::get('/dashboard', [\App\Http\Controllers\Aide\DashboardController::class, 'index'])
        ->name('dashboard');
    
    // ===== ROUTES POUR LES NOUVEAUX =====
    Route::resource('nouveaux', NouveauController::class);
    Route::get('/nouveaux/{nouveau}/details', [NouveauController::class, 'details'])->name('nouveaux.details');
    Route::get('/nouveaux/{nouveau}/historique', [NouveauController::class, 'historique'])->name('nouveaux.historique');
    Route::get('/nouveaux/{nouveau}/stats', [NouveauController::class, 'stats'])->name('nouveaux.stats');
    
    // ===== ROUTES POUR LES PARTICIPATIONS =====
    Route::get('/participations/programmes/{nouveau?}', [ParticipationController::class, 'programmes'])
        ->name('participations.programmes');
    Route::post('/participations/enregistrer', [ParticipationController::class, 'enregistrer'])
        ->name('participations.enregistrer');
    Route::get('/participations/{nouveau}/historique', [ParticipationController::class, 'historique'])
        ->name('participations.historique');
    Route::get('/participations/{nouveau}/stats', [ParticipationController::class, 'stats'])
        ->name('participations.stats');
    Route::get('/participations/{nouveau}/recentes', [ParticipationController::class, 'recentes'])
        ->name('participations.recentes');
    
    // ===== ROUTES POUR LE PROFIL ET MOT DE PASSE =====
    Route::get('/profile/password', [UserController::class, 'showChangePasswordForm'])
        ->name('profile.password');
    Route::post('/profile/password', [UserController::class, 'changePassword'])
        ->name('profile.password.update');
    Route::get('/profile', [UserController::class, 'showProfile'])
        ->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])
        ->name('profile.update');

        // Routes pour les filtres d'historique
Route::get('/nouveaux/{nouveau}/historique/filtre', [NouveauController::class, 'historiqueFiltre'])
    ->name('nouveaux.historique.filtre');
});


// ==================== ROUTES ADMIN ====================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour les aides
    Route::resource('aides', AideController::class)->parameters([
        'aides' => 'user'
    ]);
    
    // Routes supplémentaires pour aides
    Route::get('/aides/{user}/nouveaux', [AideController::class, 'nouveaux'])
        ->name('aides.nouveaux');
    Route::post('/aides/{user}/assign-nouveau', [AideController::class, 'assignNouveau'])
        ->name('aides.assignNouveau');
    Route::delete('/aides/{user}/remove-nouveau/{nouveau}', [AideController::class, 'removeNouveau'])
        ->name('aides.removeNouveau');
    Route::post('/aides/{user}/reset-password', [AideController::class, 'resetPassword'])
        ->name('aides.resetPassword');
    
    // Routes pour les programmes
    Route::resource('programmes', ProgrammeController::class);
    
    // Routes pour les nouveaux (admin)
    Route::resource('nouveaux', AdminNouveauController::class)->parameters([
        'nouveaux' => 'nouveau'
    ]);
    
    // Routes pour les statistiques
    Route::prefix('statistiques')->name('statistiques.')->group(function () {
        Route::get('/', [StatistiqueController::class, 'index'])->name('index');
        Route::get('/data', [StatistiqueController::class, 'getData'])->name('data');
        Route::get('/export', [StatistiqueController::class, 'export'])->name('export');
        Route::get('/details/{type}', [StatistiqueController::class, 'details'])->name('details');
    });
    
    Route::get('/rapports', function() {
        $user = Auth::user();
        if ($user->role !== 'admin') abort(403);
        return "Page Rapports - À développer";
    })->name('rapports.index');
});