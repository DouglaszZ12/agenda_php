<?php

include_once("connection.php");

// Conexão com o banco de dados
$conectar=new DB;
$conectar=$conectar->conectar();
$conectar->set_charset("utf8");  

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="theme-color" content="#000000" />
        <meta name="description" content="Teste criação de agenda" />
        <meta name="keywords" content="agenda, teste, processo seletivo">

        <link rel="stylesheet" href="./style.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;700;800;900&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,900;1,300;1,700&display=swap;https://fonts.googleapis.com/css?family=Oswald:400,700,300">

        <title>Agenda</title>        
    </head>
    <body>
        <div class="container">
            <div class="topic">
                <div class="page_title">Agenda Telefônica</div>
                <div class="new_data" onclick="DataPage(0)">
                    <div class="">+ contato</div>
                </div>                
            </div>
            <div class="list_data">
                <table cellPadding="0" cellSpacing="0">
                    <thead>                        
                        <tr>
                            <th width="20">#</th>
                            <th>Nome</th>
                            <th width="150">E-mail</th>
                            <th width="150">Data de nascimento</th>
                            <th width="150">CPF</th>
                            <th width="150">Telefone</th>
                            <th width="60"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        $inf=mysqli_query($conectar, "SELECT id, name, email, cpf, date_birth, phone FROM agenda ORDER BY name ASC");
                        if(mysqli_num_rows($inf) !=0){
                            while($show_inf=mysqli_fetch_object($inf)){
                                $count = $count + 1;
                                echo '
                                <tr>
                                    <td>' . $count . '</td>
                                    <td>' . $show_inf->name . '</td>
                                    <td>' . $show_inf->email . '</td>
                                    <td>' . $show_inf->date_birth . '</td>
                                    <td>' . $show_inf->cpf . '</td>
                                    <td>' . $show_inf->phone . '</td>
                                    <td>
                                        <div class="list_opt">
                                            <div onclick="DeleteData(' . $show_inf->id . ')">
                                                <img alt="delete" src="./delete.png" class="icons" />
                                            </div>
                                            <div onclick="DataPage(' . $show_inf->id . ')">
                                                <img alt="edit" src="./edit.png" class="icons" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>';
                            }
                        }else {
                            echo '
                            <tr>
                                <td colspan="7" align="center">Nenhum contato encontrada...</td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
                <div id="show_contact_data"></div>
                <div id="delete_data"></div>
            </div>
        </div>
        <script>
            function DataPage(id){
                let title = '';
                if(id == 0){
                    document.getElementById('show_contact_data').insertAdjacentHTML('beforeend', `
                    <div class="PopUp" id="fechar_">
                        <div class="all">
                            <div class="div_data">
                                <div class="title">
                                    Registrar novo contato
                                </div>
                                <div class="close" onclick="document.getElementById('fechar_').remove();">X</div>
                            </div>
                            <div class="div_data" style="padding-top: 0">
                                <form action="./register.php?acao=EditOrNewRegister/${ id }" method="post">
                                    <div class="content">
                                        <div class="div_input space_input">
                                            <input type="text" name="name" maxlength="100" required>
                                            <span class="name_input">Nome</span>
                                        </div>
                                        <div class="div_input space_input">
                                            <input type="text" name="email" maxlength="40" required>
                                            <span class="name_input">E-mail</span>
                                        </div>
                                    </div>
                                    <div class="content" style="padding-top: 16px">
                                        <div class="div_input">
                                            <input type="text" name="cpf" class="cpf" maxlength="14">
                                            <span class="name_input">CPF</span>
                                        </div>
                                        <div class="div_input">
                                            <input type="date" name="date_birth" class="date_birth">
                                            <span class="name_input">Data nascimento</span>
                                        </div>
                                        <div class="div_input">
                                            <input type="text" name="phone" class="phone" maxlength="20" required>
                                            <span class="name_input">Telefone</span>
                                        </div>
                                    </div>
                                    <button class="register_data">
                                        Cadastrar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>`);
                }else {
                    let show_data = '';
                    fetch('./register.php?acao=show_contact_data/' + id)
                    .then(response => {
                        return response.json();
                    })
                    .then(return_data => { 
                        console.log(return_data[0].list_data);
                        show_data = return_data[0].list_data;
                        document.getElementById('show_contact_data').insertAdjacentHTML('beforeend', return_data[0].list_data);
                    });
                }
            }

            function DeleteData(id){
                document.getElementById('delete_data').insertAdjacentHTML('beforeend', `
                <div class="PopUp" id="fechar_">
                    <div class="all delete">
                        <div class="div_data">
                            <div class="title">
                                Deletar contato
                            </div>
                        </div>
                        <div class="div_data" style="padding-top: 0">
                            <div class="content" style="justify-content:center; flex-grow: 1">
                                <a href="./register.php?acao=delete/${ id }">
                                    <div>
                                        <div class="opt_select yes_update">Sim</div>
                                    </div>
                                </a>
                                <div onclick="document.getElementById('fechar_').remove();">
                                    <div class="opt_select not_update">Não</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`);              
            }
        </script>
    </body>
</html>
