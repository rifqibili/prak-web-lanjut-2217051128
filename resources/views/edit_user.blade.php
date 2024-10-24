@extends('layouts.app')

@section('content')
    <div style="text-align: center; padding: 20px;">
        <!-- Logo Barcelona -->
        <img src="https://upload.wikimedia.org/wikipedia/en/thumb/4/47/FC_Barcelona_%28crest%29.svg/1200px-FC_Barcelona_%28crest%29.svg.png" 
             alt="FC Barcelona Logo" 
             style="width: 100px; margin-bottom: 20px;">

        <h1 style="color: #A50044; font-family: 'Arial', sans-serif; margin-bottom: 20px;">Edit User</h1>

        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data" style="background-color: #004D98; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div style="margin-bottom: 15px;">
                <label style="color: white;">Nama</label><br>
                <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" style="padding: 10px; border-radius: 5px; border: 1px solid #ccc; width: 100%; max-width: 400px;">
                @foreach ($errors->get('nama') as $msg)
                    <p class="text-danger">{{ $msg }}</p>
                @endforeach
            </div>

            <!-- Kelas -->
            <div style="margin-bottom: 15px;">
                <label for="kelas_id" style="color: white;">Kelas</label><br>
                <select name="kelas_id" id="kelas_id" required style="padding: 10px; border-radius: 5px; border: 1px solid #ccc; width: 100%; max-width: 400px;">
                    @foreach ($kelas as $kelasitem)
                        <option value="{{ $kelasitem->id }}"
                            {{ $kelasitem->id == $user->kelas_id ? 'selected' : '' }}>
                            {{ $kelasitem->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Jurusan -->
            <div style="margin-bottom: 15px;">
                <label for="jurusan" style="color: white;">Jurusan</label><br>
                <select name="jurusan" id="jurusan" required style="padding: 10px; border-radius: 5px; border: 1px solid #ccc; width: 100%; max-width: 400px;">
                    @foreach (['fisika', 'kimia', 'biologi', 'matematika', 'ilmu komputer'] as $jurusan)
                        <option value="{{ $jurusan }}" {{ $user->jurusan == $jurusan ? 'selected' : '' }}>
                            {{ ucfirst($jurusan) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Semester -->
            <div style="margin-bottom: 15px;">
                <label for="semester" style="color: white;">Semester</label><br>
                <input type="number" name="semester" value="{{ old('semester', $user->semester) }}" min="1" max="14" required style="padding: 10px; border-radius: 5px; border: 1px solid #ccc; width: 100%; max-width: 400px;">
                @foreach ($errors->get('semester') as $msg)
                    <p class="text-danger">{{ $msg }}</p>
                @endforeach
            </div>

            <!-- Fakultas -->
            <div style="margin-bottom: 15px;">
                <label for="fakultas_id" style="color: white;">Fakultas</label><br>
                <select name="fakultas_id" id="fakultas_id" required style="padding: 10px; border-radius: 5px; border: 1px solid #ccc; width: 100%; max-width: 400px;">
                    @foreach ($fakultas as $fak)
                        <option value="{{ $fak->id }}" {{ $fak->id == $user->fakultas_id ? 'selected' : '' }}>
                            {{ $fak->nama_fakultas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Foto -->
            <div style="margin-bottom: 15px;">
                <label for="foto" style="color: white;">Foto:</label><br>
                @if ($user->foto)
                    <img src="{{ asset($user->foto) }}" alt="User Photo" width="100" style="display: inline-block; margin: 0 auto;">
                @endif
            </div>

            <div>  
                <input type="file" id="foto" name="foto" style="padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            </div><br>

            <input type="submit" name="submit" value="Submit" style="background-color: #A50044; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
        </form>
    </div>
@endsection
