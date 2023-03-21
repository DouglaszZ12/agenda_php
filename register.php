<?php

$sepInf = explode('/', $_GET['acao']);

if($sepInf[0]){    
    include_once("connection.php");

    // Conexão com o banco de dados
    $conectar=new DB;
    $conectar=$conectar->conectar();
    $conectar->set_charset("utf8");  

    // delete
    if($sepInf[0] == 'delete'){
        mysqli_query($conectar, "DELETE FROM agenda WHERE id='" . $sepInf[1] . "'");
        header('Location: ./index.php');
    }

    // edit or new register
    if($sepInf[0] == 'EditOrNewRegister'){    
        $alte = array('"',     "'",     '‘',       '’',       '“',       '”');
        $para = array('&#34;', '&#39;', '&lsquo;', '&rsquo;', '&ldquo;', '&rdquo;');

        $name  = str_replace($alte, $para, $_POST['name']);
        $email = str_replace(' ', '', $_POST['email']);
        if($sepInf[1] == 0){
            mysqli_query($conectar, "INSERT INTO agenda (`name`, `email`, `date_birth`, `cpf`, `phone`) VALUES ('$name', '$email', '" . $_POST['date_birth'] . "', '" . $_POST['cpf'] . "', '" . $_POST['phone'] . "')");
        }else {
            mysqli_query($conectar, "UPDATE agenda SET name='$name', email='$email', date_birth='" . $_POST['date_birth'] . "', cpf='" . $_POST['cpf'] . "', phone='" . $_POST['phone'] . "' WHERE id='" . $sepInf[1] . "'");
        }
        header('Location: ./index.php');
    }

    // show data
    if($sepInf[0] == 'show_contact_data'){
        $list_data = '';
        $inf=mysqli_query($conectar, "SELECT id, name, email, cpf, date_birth, phone FROM agenda WHERE id='" . $sepInf[1] . "'");
        while($show_inf=mysqli_fetch_object($inf)){
            $list_data .= '
            <div class="PopUp" id="fechar_">
                <div class="all">
                    <div class="div_data">
                        <div class="title">Editar dados</div>
                        <div class="close" onclick="document.getElementById(\'fechar_\').remove();">X</div>
                    </div>
                    <div class="div_data" style="padding-top: 0">
                        <form action="./register.php?acao=EditOrNewRegister/' . $sepInf[1] . '" method="post">
                            <div class="content">
                                <div class="div_input space_input">
                                    <input type="text" name="name" maxlength="100" value="' . $show_inf->name . '" required>
                                    <span class="name_input">Nome</span>
                                </div>
                                <div class="div_input space_input">
                                    <input type="text" name="email" maxlength="40" value="' . $show_inf->email . '" required>
                                    <span class="name_input">E-mail</span>
                                </div>
                            </div>
                            <div class="content" style="padding-top: 16px">
                                <div class="div_input">
                                    <input type="text" name="cpf" class="cpf" value="' . $show_inf->cpf . '" maxlength="14">
                                    <span class="name_input">CPF</span>
                                </div>
                                <div class="div_input">
                                    <input type="date" name="date_birth" class="date_birth" value="' . $show_inf->date_birth . '">
                                    <span class="name_input">Data nascimento</span>
                                </div>
                                <div class="div_input">
                                    <input type="text" name="phone" class="phone" maxlength="20" value="' . $show_inf->phone . '" required>
                                    <span class="name_input">Telefone</span>
                                </div>
                            </div>
                            <button class="register_data">
                                Cadastrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>';
        }
        $details[] = array(
            'list_data' => $list_data
        );
        echo (json_encode($details, JSON_UNESCAPED_UNICODE));
    }
}
?>