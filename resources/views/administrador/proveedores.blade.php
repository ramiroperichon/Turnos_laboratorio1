
@extends('layouts.app')

@section('content')
    <h1>Administrar Proveedores</h1>

    <h2>Crear Nuevo Proveedor</h2>
    <form action="{{ route('admin.crearUsuario') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Crear Proveedor</button>
    </form>

    <h2>Lista de Proveedores</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                        <form action="{{ route('admin.modificarUsuario', $usuario->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <input type="text" name="name" value="{{ $usuario->name }}">
                            <input type="email" name="email" value="{{ $usuario->email }}">
                            <button type="submit">Modificar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
