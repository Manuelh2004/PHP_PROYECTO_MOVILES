<?php
function ValidarUsuario($email, $password) {
    // Establecer conexión a la BD
    require_once("conexion.php");

    // Query
    $sql = "SELECT * FROM usuario WHERE em_usuario = '$email' AND pas_usuario = '$password'";

    // Ejecutar la consulta
    $result = mysqli_query($con, $sql);

    $datos = array();
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $datos[] = $row;
    }

    // Cerrar conexión
    mysqli_close($con);

    return $datos;
}
function ObtenerUsuario($id_usuario) {
    // Incluir la conexión a la base de datos
    require_once("../../configuracion/conexion.php");

    // Obtener la conexión
    $con = conectar();

    // Consulta SQL para obtener los datos del usuario por id_usuario
    $query = "SELECT nom_usuario, ape_usuario, num_usuario, fna_usuario, tel_usuario 
              FROM usuario 
              WHERE id_usuario = '$id_usuario'";

    // Ejecutar la consulta
    $result = mysqli_query($con, $query);

    // Verificar si se encontró el usuario
    if (mysqli_num_rows($result) > 0) {
        // Si se encontró el usuario, obtener los datos
        $row = mysqli_fetch_assoc($result);

        // Convertir los datos a formato JSON y devolverlos
        return json_encode($row);
    } else {
        // Si no se encuentra el usuario, devolver error
        return "error";
    }

    // Cerrar la conexión
    mysqli_close($con);
}

function ActualizarUsuario($id_usuario, $nombres, $apellidos, $documento, $fecha_nacimiento, $telefono) {
  // Incluir el archivo de conexión
    require_once("../../configuracion/conexion.php");

    // Obtener la conexión
    $con = conectar();

    // Validar los datos recibidos
    if (empty($id_usuario) || empty($nombres) || empty($apellidos) || empty($documento) || empty($fecha_nacimiento) || empty($telefono)) {
        return "Todos los campos son requeridos";
    }

    // Preparar la consulta SQL para actualizar los datos del usuario
    $sql = "UPDATE usuario SET 
                nom_usuario = ?, 
                ape_usuario = ?, 
                num_usuario = ?, 
                fna_usuario = ?, 
                tel_usuario = ? 
            WHERE id_usuario = ?";

    // Preparar la sentencia
    if ($stmt = $con->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param("sssssi", $nombres, $apellidos, $documento, $fecha_nacimiento, $telefono, $id_usuario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si se actualizó correctamente, devolver 'success'
            return "success";
        } else {
            // Si hubo un error al ejecutar la consulta, devolver el mensaje de error
            return "Error al actualizar los datos: " . $stmt->error;
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        // Si hubo un error al preparar la consulta, devolver el mensaje de error
        return "Error en la preparación de la consulta: " . $con->error;
    }

    // Cerrar la conexión
    $con->close();
}

function ObtenerRol($uid_firebase){
    require_once("../../configuracion/conexion.php");
    // Obtener la conexión
    $con = conectar();

    // Consulta para obtener el id_tipo_usuario basado en el uid_firebase
    $query = "SELECT id_tipo_usuario FROM usuario WHERE uid_firebase = ?";
    
    // Preparar la consulta para evitar inyecciones SQL
    if ($stmt = $con->prepare($query)) {
        // Vincular el parámetro
        $stmt->bind_param("s", $uid_firebase);

        // Ejecutar la consulta
        $stmt->execute();
        
        // Guardar el resultado
        $stmt->store_result();

        // Verificar si se encontró el usuario con el uid_firebase
        if ($stmt->num_rows > 0) {
            // Obtener el id_tipo_usuario
            $stmt->bind_result($id_tipo_usuario);
            $stmt->fetch();
            
            // Ahora obtener el nombre del rol desde la tabla tipo_usuario usando el id_tipo_usuario
            $query_rol = "SELECT nom_tipo_usuario FROM tipo_usuario WHERE id_tipo_usuario = ?";
            
            if ($stmt_rol = $con->prepare($query_rol)) {
                // Vincular el parámetro id_tipo_usuario
                $stmt_rol->bind_param("i", $id_tipo_usuario);
                
                // Ejecutar la consulta
                $stmt_rol->execute();
                
                // Obtener el resultado del nombre del rol
                $stmt_rol->store_result();
                $stmt_rol->bind_result($rol);
                $stmt_rol->fetch();

                // Verificar si se encontró el rol
                if ($rol) {
                    // Retornar el rol en formato JSON
                    return json_encode(array("success" => true, "rol" => $rol));
                } else {
                    // Si no se encuentra el rol en la tabla tipo_usuario
                    return json_encode(array("success" => false, "message" => "Rol no encontrado"));
                }
            } else {
                // Error en la consulta de tipo_usuario
                return json_encode(array("success" => false, "message" => "Error al obtener el rol desde tipo_usuario"));
            }
        } else {
            // Si el uid_firebase no está en la base de datos de usuarios
            return json_encode(array("success" => false, "message" => "Usuario no encontrado"));
        }

        // Cerrar la sentencia preparada
        $stmt->close();
    } else {
        // Error al preparar la consulta de usuario
        return json_encode(array("success" => false, "message" => "Error en la consulta de la base de datos"));
    }

    // Cerrar la conexión a la base de datos
    $con->close();
}


    function ListarUsuario()
    {
          // Establecer la conexión a la BD
        require_once("../../configuracion/conexion.php");

        // Obtener la conexión
        $con = conectar();  // <--- Aquí llamas a la función conectar()

        // Query con JOIN para filtrar por rol de usuario "ROL_ADMIN"
        $sql = "
            SELECT 
                u.nom_usuario as nombre, 
                u.ape_usuario as apellido, 
                u.em_usuario as correo, 
                u.est_usuario as estado
            FROM 
                usuario u
            INNER JOIN 
                tipo_usuario t ON u.id_tipo_usuario = t.id_tipo_usuario
            WHERE 
                t.nom_tipo_usuario = 'ROL_ADMIN'";

        // Ejecutar el query
        $result = mysqli_query($con, $sql);

        $datos = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            // Concatenar nombre y apellido
            $row['nombre'] = $row['nombre'] . ' ' . $row['apellido'];

            // Convertir el estado de 1 a 'Activo' y 0 a 'Inactivo'
            if ($row['estado'] == 1) {
                $row['estado'] = 'Activo';
            } else {
                $row['estado'] = 'Inactivo';
            }

            // Eliminar el campo 'apellido' si no es necesario
            unset($row['apellido']);

            // Añadir el usuario con nombre completo y estado convertido a la lista de datos
            $datos[] = $row;
        }

        // Cerrar la conexión a la BD
        mysqli_close($con);

        return $datos;
    }
?>