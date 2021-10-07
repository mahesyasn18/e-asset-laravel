<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Auth::routes();

Route::get('/dashboard', 'AdminController@index')->name('dashboard');

Route::prefix('admin')->group(function () {
    Route::post('/logout', 'Auth\LoginController@adminLogout')->name("logout_admin");
});
Route::get("/invoice-print/{id}", "TransaksiController@invoice_print");

Route::middleware('auth:admin')->group(function () {
    Route::get("/data/notif", "AdminController@notif");
    Route::get('/data/dashboard', "AdminController@datas");
    //Root Page route
    Route::get("/asset/create", 'AssetController@create');
    Route::get("/akun/admin", "AkunController@admin");
    Route::get("/akun/siswa", "SiswaController@index");
    Route::get("/akun/guru", "GuruController@index");
    Route::get("transaksi/keluar/pending", "TransaksiController@transaksi_pending");
    Route::get("/sumber-barang", "AdminController@sumber");
    Route::get("/sumber-data", "AdminController@sumber_data");
    Route::get("/request-log", "RequestLogController@index");
    // Route::get("/pinjam", "PinjamController@index");

    //request log
    Route::get("/request-log", "RequestLogController@index");
    Route::get('/request-log/excel', "RequestLogController@exportexcel");

    //CRUD Barang
    Route::post("/asset/insert", "AssetController@insert");
    Route::get("/asset/show/{id}", "AssetController@show");
    Route::get("/asset/edit/{id}", "AssetController@edit");
    Route::put("/asset/update/{id}", "AssetController@update");
    Route::delete("/asset/delete/{id}", "AssetController@destroy");
    Route::put("/asset/status", "AssetController@detail_status");
    Route::get("/eksport/excel", "AssetController@excel_eksport");
    Route::get("/eksport/pdf", "AssetController@pdf_eksport");
    Route::post("/asset/create/excel", "AssetController@insert_excel");
    Route::post("/barcode/print/{id}", "AssetController@barcode");
    //End CRUD Barang

    //CRUD Category
    Route::post("/category/insert", "AssetController@category_insert");
    Route::delete('/category/delete/{id}', "AssetController@category_destroy");
    //End CRUD Category

    // Siswa
    Route::get('/akun/siswa/create', "SiswaController@create");
    Route::post("/siswa/insert", "SiswaController@insert");
    Route::get("/siswa/detail/{id}", "SiswaController@show");
    Route::put("/siswa/block/{id}", "SiswaController@block");
    Route::put("/siswa/unblock/{id}", "SiswaController@unblock");
    Route::get("/siswa/edit/{id}", "SiswaController@edit");
    Route::put("/siswa/update/{id}", "SiswaController@update");
    Route::post("/eksport/siswa/excel", "SiswaController@export");
    Route::post("/siswa/create/excel", "SiswaController@upload");
    //EndSiswa

    //Guru
    Route::get("/akun/guru/create", "GuruController@create");
    Route::post("/guru/insert", "GuruController@insert");
    Route::get("/guru/detail/{id}", "GuruController@show");
    Route::put("/guru/block/{id}", "GuruController@block");
    Route::put("/guru/unblock/{id}", "GuruController@unblock");
    Route::get("/guru/edit/{id}", "GuruController@edit");
    Route::put("/guru/update/{id}", "GuruController@update");
    Route::post("/eksport/guru/excel", "GuruController@export");
    Route::post("/guru/create/excel", "GuruController@upload");
    //End Guru

    //create account user
    Route::get('/akun/admin/create', "AkunController@createadmin");
    Route::post('/admin/insert', "AkunController@insert_admin");
    Route::get('/admin/edit/{id}', 'AkunController@admin_edit');
    Route::put('/admin/update/{id}', "AkunController@update_admin");
    Route::put("/admin/block/{id}", "AkunController@admin_block");
    Route::put("/admin/unblock/{id}", "AkunController@admin_unblock");
    Route::post("/users/create/excel_guru", "AkunController@upload_excel_guru");
    Route::get("/users/export/{id}", "AkunController@exportuser");
    // End create account user

    //transaksi keluar
    Route::get('/transaksi/keluar/pending', "TransaksiController@transaksipending");
    Route::get('/transaksi/keluar/approve', "TransaksiController@transaksiapprove");
    Route::get('/transaksi/keluar/ongoing', "TransaksiController@transaksiongoing");
    Route::get('/transaksi/keluar/completed', "TransaksiController@transaksicompleted");
    Route::get('/transaksi/keluar/cancel', "TransaksiController@transaksicancel");
    // Ent transaksi keluar

    //Transaksi Status Ubah
    Route::put('/pending/changed', "TransaksiController@ubah_status_pending");
    Route::put('/pending-refuse/changed', "TransaksiController@transaksi_refuse");
    Route::put('/approve/changed', "TransaksiController@ubah_status_approve");
    Route::put("/ongoing/changed", "TransaksiController@ubah_status_ongoing");
    Route::put("/approve/scan_barang", "TransaksiController@scan_barang_approve");
    Route::put("/ongoing/scan_barang", "TransaksiController@scan_barang_ongoing");
    Route::get('/report/peminjaman',  "TransaksiController@export_to_Excel");
    Route::get(' /report/peminjaman/cancel',  "TransaksiController@export_Excel");
    //End Status Transaksi

    //Log Activity
    Route::get('/activity-log', "ActivityLogController@index");
    Route::get('/admin/log-activity/{id}', "ActivityLogController@admin_activity");
    Route::get('/activity/excel', "ActivityLogController@exportexcel");
    //End Log Activity

    Route::get("/tahun-ajaran", "TahunAjaranController@index");
    Route::put("/active/{id}", "TahunAjaranController@update_data");
    Route::post("/insert/tahun/ajaran", "TahunAjaranController@insertTA");


    //pinjamlangsung
    Route::get("/pinjam/langsung", "PinjamLangsungController@index");
    Route::post("/request/langsung", "PinjamLangsungController@create_req");
    Route::delete("/cart/delete/{id}", 'PinjamLangsungController@remove');
    Route::post("/scan/kartu_pelajar", "PinjamLangsungController@get_users");
    Route::post("/request/create", "PinjamLangsungController@insert_transaksi");

    //daftar jurusan
    Route::get('/daftar/jurusan', "AdminController@jurusan");
    Route::post('/sumber/insert', "AdminController@sumber_insert");

    //Kelas
    Route::get("/daftar-kelas", "KelasController@index");
    Route::post("/kelas/create", "KelasController@create");
    Route::get('/class/{id}', "KelasController@show");
    Route::get('/class/student/{id}', "KelasController@detail");
    Route::get('/get-data-kelas', "KelasController@data");
    Route::post("/naik-kelas", "KelasController@naik_kelas");
});

Route::middleware('auth:web')->group(function () {
    Route::get('/index', 'UserController@index')->name('index');

    //request barang (user)
    Route::get('/request/barang', "UserController@request")->name("request.barang");

    //keranjang (user)
    Route::get('/keranjang', "UserController@keranjang");

    //riwayat transaksi (user)
    Route::get('/riwayat/transaksi', "UserController@riwayat");

    //detail barang pinjam
    Route::get("/detail/barang/{id}", "UserController@detail");

    //make request
    Route::get("/create/request", "UserController@create_request");
    Route::post("/process", "UserController@process_request");
    Route::delete("/cart/remove/{id}", 'UserController@remove');
});
