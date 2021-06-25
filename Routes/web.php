<?php

Route::prefix('epanel/features')->as('epanel.')->middleware(['auth', 'check.permission:Penghargaan'])->group(function() 
{
    Route::resources([
        'penghargaan' => 'PenghargaanController'
    ]);
});