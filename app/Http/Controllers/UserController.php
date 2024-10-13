<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\UserModel;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{   
    public $userModel;
    public $kelasModel;

    public function __construct()
    {
    $this->userModel = new UserModel();
    $this->kelasModel = new Kelas();
    }

    public function create()
    {
        return view('create_user',[
            'kelas' => Kelas::all(),
        ]); 
        $kelas = $this->kelas->getKelas();
        $data = [
        'title' => 'Create User',
        'kelas' => $kelas,
        ];
        return view('create_user', $data);

    }
    public function index()
    {
    $data = [
    'title' => 'Create User',
    'users' => $this->userModel->getUser(),
    ];
    return view('list_user', $data);
    }

    // public function store(Request $request) 
    // { 
    //     $data = $request->all(); 
    //     dd($data); 
    // }

    public function store(Request $request)
    {
    // Validasi input
    $request->validate([
    'nama' => 'required|string|max:255',
    'npm' => 'required|string|max:255',
    'kelas_id' => 'required|integer',
    'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk foto
    ]);
    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');
        // Buat nama file yang unik
        $fotoName = time() . '_' . $foto->getClientOriginalName();
        // Pindahkan file ke folder 'upload/img' di dalam public folder
        $foto->move(public_path('upload/img'), $fotoName);
        // Simpan path ke database
        $fotoPath = 'upload/img/' . $fotoName;
    } else {
        // Jika tidak ada file yang diupload, set fotoPath menjadi null atau default
        $fotoPath = null;
    }
    // Menyimpan data ke database termasuk path foto
    $this->userModel->create([
    'nama' => $request->input('nama'),
    'npm' => $request->input('npm'),
    'kelas_id' => $request->input('kelas_id'),
    'foto' => $fotoPath, // Menyimpan path foto
    ]);
    return redirect()->to('/user')->with('success', 'User
    berhasil ditambahkan');
    }
    public function profile($id){

        $user = $this->userModel->find($id);

        if(!$user){
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        return view ('profile', ['user' => $user]);
    }
    public function show($id){
        $user = $this->userModel->getUser($id);

        $data = [
            'title' => 'Profile',
            'user' => $user,
        ];

        return view('profile', $data);
    }
    public function edit($id)
    {
        $user = UserModel::findOrFail($id);
        $kelasModel = new Kelas();
        $kelas = $kelasModel -> getKelas();
        $title = 'Edit User';
        return view ('edit_user', compact('user', 'kelas', 'title'));
    }

    public function update(Request $request, $id)
    {
        $user = UserModel::findOrFail($id);
        $user->nama = $request->nama;
        $user->npm = $request->npm;
        $user->kelas_id = $request->kelas_id;

        if($request->hasFile('foto')){
            $fileName = time(). '.'. $request->foto->extension();
            $request->foto->move(public_path('upload'),$fileName);
            $user->foto = 'upload/'. $fileName;
        }

        $user->save();

        return redirect()->route('user.list')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = UserModel::findOrFail($id);
        $user->delete();

        return redirect()->to('/user')->with('success', 'User sudah berhasil dihapus');
    }

    public function read($id)
    {
        $user = UserModel::findOrFail($id);
        $kelas = Kelas::find($user->kelas_id);

        $title = 'Detail'.$user->nama;

        return view('show.user', compact('user', 'kelas', 'title'));
    }
}   
