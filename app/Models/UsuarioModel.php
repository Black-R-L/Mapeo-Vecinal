<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UsuarioModel
 * 
 * Gestiona todas las operaciones CRUD para usuarios
 * Soporta roles: ciudadano, autoridad, admin
 * 
 * @package App\Models
 */
class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['nombre', 'email', 'password', 'rol', 'barrio', 'estado'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $validationRules  = [
        'nombre'  => 'required|string|min_length[3]|max_length[100]',
        'email'   => 'required|valid_email|is_unique[usuarios.email,id,{id}]',
        'password' => 'required|min_length[6]|max_length[255]',
        'rol'     => 'required|in_list[ciudadano,autoridad,admin]',
        'barrio'  => 'required|string|max_length[100]',
        'estado'  => 'in_list[activo,inactivo]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Este email ya está registrado',
            'valid_email' => 'Ingresa un email válido',
        ],
        'password' => [
            'min_length' => 'La contraseña debe tener al menos 6 caracteres',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtener usuario por email
     * 
     * @param string $email
     * @return array|null
     */
    public function getByEmail(string $email)
    {
        return $this->where('email', $email)
                    ->where('estado', 'activo')
                    ->first();
    }

    /**
     * Obtener todos los usuarios activos con paginación
     * 
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function getActivos(int $perPage = 10, int $page = 1)
    {
        return $this->where('estado', 'activo')
                    ->paginate($perPage, 'default', $page);
    }

    /**
     * Obtener usuarios por rol
     * 
     * @param string $rol
     * @return array
     */
    public function getByRol(string $rol)
    {
        return $this->where('rol', $rol)
                    ->where('estado', 'activo')
                    ->findAll();
    }

    /**
     * Obtener usuarios por barrio
     * 
     * @param string $barrio
     * @return array
     */
    public function getByBarrio(string $barrio)
    {
        return $this->where('barrio', $barrio)
                    ->where('estado', 'activo')
                    ->findAll();
    }

    /**
     * Validar credenciales de usuario
     * 
     * @param string $email
     * @param string $password
     * @return bool|array
     */
    public function validarCredenciales(string $email, string $password)
    {
        $usuario = $this->getByEmail($email);
        
        if (!$usuario) {
            return false;
        }

        if (!password_verify($password, $usuario['password'])) {
            return false;
        }

        return $usuario;
    }

    /**
     * Crear usuario con contraseña encriptada
     * 
     * @param array $data
     * @return int|false ID del usuario creado
     */
    public function crearUsuario(array $data)
    {
        // Encriptar contraseña
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->insert($data);
    }

    /**
     * Cambiar contraseña de usuario
     * 
     * @param int $id
     * @param string $passwordNueva
     * @return bool
     */
    public function cambiarPassword(int $id, string $passwordNueva)
    {
        return $this->update($id, [
            'password' => password_hash($passwordNueva, PASSWORD_BCRYPT),
        ]);
    }

    /**
     * Desactivar usuario
     * 
     * @param int $id
     * @return bool
     */
    public function desactivar(int $id)
    {
        return $this->update($id, ['estado' => 'inactivo']);
    }

    /**
     * Contar total de usuarios
     * 
     * @return int
     */
    public function contarTotal()
    {
        return $this->countAllResults();
    }

    /**
     * Contar usuarios por rol
     * 
     * @param string $rol
     * @return int
     */
    public function contarPorRol(string $rol)
    {
        return $this->where('rol', $rol)->countAllResults();
    }
}
