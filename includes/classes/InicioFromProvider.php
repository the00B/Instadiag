<?php

class InicioFromProvider
{

    public function formRegister()
    {
        $inputNumeroEmail = $this->createInput('text', 'numeroEmail', 'Número de teléfono o correo electrónico');
        $inputNombreCompleto = $this->createInput('text', 'nombreCompleto', 'Nombre completo');
        $inputUsuario = $this->createInput('text', 'usuario', 'Usuario');
        $inputPassword = $this->createInput('password', 'password', 'Contraseña');
        $buttonSubmit = $this->buttonSubmit('botonRegistro', 'Registro');


        $formulario = "
                        $inputNumeroEmail
                        $inputNombreCompleto
                        $inputUsuario
                        $inputPassword
                        $buttonSubmit
        ";

        return $formulario;
    }

    public function formLogin()
    {
        $inputUsuario = $this->createInput('text', 'usuario', 'Nombre de usuario');
        $inputPassword = $this->createInput('password', 'password', 'Contraseña');
        $buttonSubmit = $this->buttonSubmit('botonLogin', 'Iniciar sesión');


        $formulario = "
                        $inputUsuario
                        $inputPassword
                        $buttonSubmit
        ";

        return $formulario;
    }


    private function createInput($tipo, $name, $placeholder)
    {
        $value = $this->getInputValue($name);
        $input = "<input type='$tipo' name='$name' placeholder='$placeholder' value='$value'>";
        return $input;
    }

    private function getInputValue($name)
    {

        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
    }
    private function buttonSubmit($name, $texto)
    {
        $buttonSubmit = "<button type='submit' name='$name' class='boton'>$texto</button>";
        return $buttonSubmit;
    }
}
