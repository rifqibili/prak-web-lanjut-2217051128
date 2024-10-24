<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\UserModel;
use App\Http\Requests\UserRequest;
use App\Models\Fakultas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
            'fakultas'=> Fakultas::all(),
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
            'nama' => 'required',
            'kelas_id' => 'required',
            'jurusan' => 'required|string',
            'semester' => 'required|integer|min:1|max:14',
            'fakultas_id' => 'required|integer',
            'foto' => 'image|file|max:2048', // Validasi foto
            ]);
            if($request->hasFile('foto')){
                $tmp = time(). '.'. $request->foto->extension();
                $request->foto->move(public_path('upload'),$tmp);
                $filename = 'upload/'. $tmp;
            }
            
            $this->userModel->create([
            'nama' => $request->input('nama'),
            'kelas_id' => $request->input('kelas_id'),
            'jurusan' => $request->input('jurusan'),
            'semester' => $request->input('semester'),
            'fakultas_id' =>$request->input('fakultas_id') ,
            'foto' => $filename, // Menyimpan nama file ke database
            ]);
             
    return redirect()->to('/')->with('success', 'User Berhasil
    dibuat');
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
        // Fetch the user based on the provided ID
        $user = UserModel::findOrFail($id);
        
        // Assuming you have a Kelas model to fetch the class data
        $kelasModel = new Kelas();
        $kelas = $kelasModel->getKelas();
    
        // Fetch the list of faculties
        $fakultas = DB::table('fakultas')->get(); // Replace with your model if necessary
    
        // Define the title for the edit page
        $title = 'Edit User';
    
        // Return the edit view with user, kelas, and fakultas data
        return view('edit_user', compact('user', 'kelas', 'fakultas', 'title'));
    }
    

    public function update(Request $request, $id)
    {
        $user = UserModel::findOrFail($id);
        $user->nama = $request->nama;
        $user->kelas_id = $request->kelas_id;
        $user->jurusan = $request->jurusan; 
        $user->semester = $request->semester; 
        $user->fakultas_id = $request->fakultas_id;

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
