<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/tes', function () {
    return response()->json(
        [
            [
                "message" => "halo dunia",
                "title" => "Selamat Malam Dunia",
                "description" => "Lorem ipsum dolor sit amet
                                  consectetur adipisicing elit. Provident, doloribus."
            ],
            [
                "message" => "halo Oemat Manoesia",
                "title" => "Selamat Malam Oemat Manoesia",
                "description" => "Lorem ipsum dolor sit amet
                                  consectetur adipisicing elit. doloribus, Provident."
            ],
        ]
    );
});